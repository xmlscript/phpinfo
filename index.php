<!doctype html>
<title><?=PHP_SAPI,' ',PHP_VERSION?></title>

<link href="https://fonts.googleapis.com/css?family=Fira+Mono|Fira+Sans|Source+Sans+Pro" rel="stylesheet">
<style {{{>
html {
 font-size: 100%;
}
body {
line-height: 1.5;
font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
font-weight: 400;
font-size: 1rem;
background: #f2f2f2;
margin: 1.5rem;
}
li{
font-family: "Fira Mono", "Source Code Pro", monospace;
font-size: 1rem;
letter-spacing: -.0625rem;
}
h1,h2,h3 {
line-height: 3rem;
font-weight: 500;
font-family: 'Oswald', sans-serif;
text-rendering: optimizeLegibility;
}
h3:after {
display: table;
width: 100%;
content: ' ';
margin-top: -1px;
border-bottom: 1px dotted;
}
h3 {
  color: #793862;
}
a:link {
color: #369;
text-decoration: none;
}
a:hover {
color: #ae508d;
}
dfn {
  font-style: normal;
}
.server dfn:after,
ol.ini dfn:after,
ol.const dfn:after {
  content: ' = ';
  white-space: pre;
  color: #bbb;
}
ol.func li.deprecated:after {
  content: ')  DEPRECATED !';
}
ol.func mark:before {
  content: ', ';
  color: #bbb;
}
ol.func mark:first-child:before {
  content: '';
}
ol.func .type:not(:empty){
  color: green;
  margin-right: 1em;
}
ol.func q mark{
  color: blue;
  //opacity: .5;
}
ol.func mark { /* required */
  color: red;
}
mark,dfn,var {
  display: inline-block;
}
mark{
  background-color: transparent;
}
ol.func q:before {
  content: ' [, ';
  color: #bbb;
}
ol.func q:first-child:before {
  content: '[';
  color: #bbb;
}
ol.func q:after {
  content: ']';
  color: #bbb;
}
li.bool span,
li.null span {
  color: red;
}
li.str span {
  color: blue;
}
li.int span,
li.double span {
  color: red;
}
ol.func .args:before {
  content: ' (';
  color: gray;
}
ol.func span:after {
  content: ')';
  color: gray;
}
ol.func li:before {
  content: 'function ';
}
ol.cls li:before {
  content: 'class ';
}
ol.cls li[data-before]:before {
  content: attr(data-before) ' class ';
}
ol.const li:before {
  content: 'const ';
}
li:before {
  color: #bbb;
}
.server dfn:before {
  content: "$_SERVER['";
  color: #bbb;
}
.server dfn:after {
  content: "'] = ";
  color: #bbb;
}
ol.cls li:after {
  color: #bbb;
  content: ' { ... }';
}
output:before {
  content: ' // return ' attr(class) ': ';
}
output {
  color: green;
}
*:before,
*:after {
  cursor: default;
}
.extends:before {
  content: ' extends ';
  color: gray;
}
.implements:before {
  content: ' implements ';
  color: gray;
}
.implements a:not(:first-child):before {
  content: ', ';
}
mark:before {
  color: gray;
}
q {
  quotes: '"' '"';
  word-wrap: break-word;
}
section {
margin: 2rem;
}
h2 {
  position: sticky;
  top:0;
  margin-top: 3em;
  padding: 0 2rem;
  background-color: #8892bf;
  z-index:99;
}
</style }}}>

<header>
<h1><?=PHP_SAPI,' ',PHP_VERSION?></h1>
</header>

<?php
function xxx($arr){ // {{{
  foreach($arr as $k=>$v){
    switch(gettype($v)){
    case 'integer':
      echo '<li class=int>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo $v;
      break;
    case 'double':
      echo '<li class=double>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo is_infinite($v)||is_nan($v)?"<var>$v":$v;
      break;
    case 'boolean':
      echo '<li class=bool>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo '<var>',$v?'true':'false';
      break;
    case 'NULL':
      echo '<li class=null>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo '<var>null';
      break;
    case 'string':
      echo '<li class=str>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo '<q>';
      echo $v===PHP_EOL?str_replace(["\r","\n"],['\r','\n'],PHP_EOL):$v;
      break;
    case 'object':
      switch(get_class($v)){
      case 'ReflectionFunction':
$demo = [
  'zend_version',
  'time',
  'timezone_version_get',
  'date_default_timezone_get',
  'mhash_count',
  'session_name',
  'session_module_name',
  'session_save_path',
  'phpversion',
  'php_sapi_name',
  'php_uname',
  'php_ini_scanned_files',
  'php_ini_loaded_file',
  'rand',
  'getrandmax',
  'mt_getrandmax',
  'getmyuid',
  'getmygid',
  'getmypid',
  'getmyinode',
  'getlastmod',
  'pi',
  'microtime',
  'uniqid',
  'get_current_user',
  'memory_get_usage',
  'memory_get_peak_usage',
  'get_include_path',
  'gethostname',
  'umask',
  'getcwd',
  'realpath_cache_size',
  'lcg_value',
  'sys_get_temp_dir',
  'easter_date',
  'easter_days',
  'unixtojd',
  'imagetypes',
  'json_last_error_msg',
];
        echo '<li class="obj',$v->isDeprecated()?' deprecated':null,'">';
        echo '<a href=//php.net/',str_replace('_','-',$k),"><dfn>$k</dfn></a>";
        echo '<span class=args>';
        foreach($v->getParameters() as $arg){
          echo $arg->isOptional()?'<q>':null;
          echo '<mark>';
          echo '<i class=type>',$arg->getType(),'</i>';
          echo $arg->isPassedByReference()?'&amp;':null;
          echo '$',$arg->getName();
          echo '</mark>';
        }
        echo '</span>';

        if(in_array($k,$demo)){
          //TODO: 返回类型用不同颜色表示
          $tmp1 = $k();
          $tmp2 = gettype($tmp1);
          echo "<output class=$tmp2>$tmp1</output>";
        }
        break;
      case 'ReflectionClass':
        echo '<li class=',gettype($v),' data-before="',join(Reflection::getModifierNames($v->getModifiers())),'">';
        echo '<a href=//php.net/class.',$k,"><dfn>$k</dfn></a>";
        if($v->getParentClass()){
          echo '<span class=extends><mark><a href=//php.net/class.',get_class($v->getParentClass()),'>',get_class($v->getParentClass()),'</a></mark>';
        }
        if($v->getInterfaceNames()){
          echo '<span class=implements>';
          foreach($v->getInterfaceNames() as $i){
            echo "<a href=//php.net/class.$i>$i</a>";
          }
        }
        break;
      default:
        echo '<li class=',gettype($v),'>';
        echo "<dfn>$k</dfn>";
        echo '<span>';
        echo '{}', get_class($v);
      }
      break;
    default:
      echo '<li class=',gettype($v),'>';
      echo "<dfn>$k</dfn>";
      echo '<span>';
      echo $v, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    }

  }
} // }}}
?>

<main>

<?php
echo '<h3 id=server>$_SERVER</h3>';
echo '<ol class=server>';
xxx($_SERVER);
echo '</ol>';


foreach(get_loaded_extensions() as $ext){
  $obj = new ReflectionExtension($ext);

  echo "<h2 id=$ext>$ext ",$obj->getVersion(),'</h2>';
  echo '<section>';

  $a = $obj->getDependencies();
  if($a){
    echo '<h3>Dependencies</h3>';
    echo '<ol class=dep>';
    foreach($a as $k=>$v){
      echo "<li><a href=#$k>$k</a> $v";
    }
    echo '</ol>';
  }

  $a = $obj->getConstants();
  if($a){
    echo '<h3>Constants</h3>';
    echo '<ol class=const>';
    xxx($a);
    echo '</ol>';
  }

  $a = $obj->getINIEntries();
  if($a){
    echo '<h3>INIEntries</h3>';
    echo '<ol class=ini>';
    xxx($a);
    echo '</ol>';
  }

  $a = $obj->getFunctions();
  if($a){
    echo '<h3>Functions</h3>';
    echo '<ol class=func>';
    xxx($a);
    echo '</ol>';
  }

  $a = $obj->getClasses();
  if($a){
    echo '<h3>Classes</h3>';
    echo '<ol class=cls>';
    xxx($a);
    echo '</ol>';
  }

  echo '</section>';

}

// vim: se fdm=marker:
