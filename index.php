#! /usr/bin/php
<?php
ini_get('output_buffering') and ob_clean();

if(PHP_SAPI==='cli') die('usage: `php -S 127.0.0.1` and `lynx 127.0.0.1` or php --r[fcezi]'.PHP_EOL);

function xxx($arr){ // {{{
  foreach($arr as $k=>$v){
    $t = gettype($v);
    switch($t){
    case 'integer':
    case 'double':
      echo "<li><dfn>$k</dfn> = <var class=$t>$v</var></li>";
      break;
    case 'boolean':
      $v = $v?'true':'false';
      echo "<li><dfn>$k</dfn> = <var class=$t>$v</var></li>";
      break;
    case 'NULL':
      echo "<li><dfn>$k</dfn> = <var class=$t>null</var></li>";
      break;
    case 'string':
      $v=str_replace(["\r","\n"],['\r','\n'],$v);
      echo "<li><dfn>$k</dfn> = \"<var class=$t>$v</var>\"</li>";
      break;
    case 'object':
      $cls = get_class($v);
      switch($cls){
      case 'ReflectionFunction':
        echo '<li>';
        echo '<a href=//php.net/',str_replace('_','-',$v->name),"><dfn>{$v->name}</dfn></a>";
        echo ' ( <span class=args>';
        foreach($v->getParameters() as $key=>$arg){
          $c = [',',null][!$key];
          $n = ['&',null][!$key].'$'.$arg->getName();
          $t = $arg->getType();
          if($t) $t = "<a href=//php.net/$t class=type>$t</a> ";
          echo $arg->isOptional()?"<span class=optional> [$c <dfn>$t</dfn> <var>$n</var> ":"<strong class=required>$c <dfn>$t</dfn> <var>$n</var></strong>";
        }
        echo str_repeat(']</span>',$v->getNumberOfParameters()-$v->getNumberOfRequiredParameters());
        echo '</span> )';

        $r = $v->getReturnType();
        if($r) echo " :<span class=syn>$r</span>";
        echo ' <small>{ ... }</small>';
        $f = $v->getFileName();
        //FIXME: How to open local file
        if($f) echo " <a href=file://$f>$f</a> ", $v->getStartLine();

        if($v->isDeprecated()) echo ' 🚫 <strong>DEPRECATED</strong>';


        if(in_array($v->name,[
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
])){
          $tmp1 = ($v->name)();
          $tmp2 = gettype($tmp1);

          echo ' ',is_string($tmp1)?"<output>\"$tmp1\"</output>":"<output>$tmp1</output>";

        }
        break;
      case 'ReflectionProperty':
        echo '<li>';
        echo '<span class=syn>',join(Reflection::getModifierNames($v->getModifiers()),' '), ' ','</span> ';
        echo '<var class=prop>$',$v->name,'</var> ;';
        break;
      case 'ReflectionMethod':
        echo '<li>';
        echo '<span class=syn>',join(Reflection::getModifierNames($v->getModifiers()),' '), ' ','</span> function ';
        echo '<a href=//php.net/',str_replace('\\','-',$v->class),'.',$v->name,"><dfn>{$v->name}</dfn></a>";
        echo ' ( <span class=args>';
        foreach($v->getParameters() as $key=>$arg){
          $c = [',',null][!$key];
          $n = ['&',null][!$key].'$'.$arg->getName();
          $t = $arg->getType();
          if($t) $t = "<a href=//php.net/$t class=type>$t</a> ";
          echo $arg->isOptional()?"<span class=optional> [$c <dfn>$t</dfn> <var>$n</var> ":"<strong class=required>$c <dfn>$t</dfn> <var>$n</var></strong>";
        }
        echo str_repeat(']</span>',$v->getNumberOfParameters()-$v->getNumberOfRequiredParameters());
        echo '</span> )';

        $r = $v->getReturnType();
        if($r) echo " :<span class=syn>$r</span>";
        echo ' <small>{ ... }</small>';
        $f = $v->getFileName();
        //FIXME: How to open local file
        if($f) echo " <a href=file://$f>$f</a> ", $v->getStartLine();

        if($v->isDeprecated()) echo ' <strong>DEPRECATED</strong>';

        break;
      case 'ReflectionClass':
        $methods=$v->getMethods();
        $const = $v->getConstants();
        $prop = $v->getProperties();
        $count = count($methods)+count($const)+count($prop);
        if($count)
        echo "<li class=$t><details><summary>";
        else
        echo "<li class=\"$t nodetails\">";
        echo '<span class=syn>',join(Reflection::getModifierNames($v->getModifiers()),' '), ' ',$v->isInterface()?'interface':($v->isTrait()?'trait':'class'),'</span> ';
        echo '<a href=//php.net/class.',str_replace('\\','_',$v->name),"><dfn>{$v->name}</dfn></a>";

        $p = $v->getParentClass();
        if($p){
          echo ' <span class=syn>extends</span> <a href=//php.net/class.',str_replace('\\','_',$p->name),'>',$p->name,'</a>';
        }

        $i = $v->getInterfaceNames();
        if($i){
          echo ' <span class=syn>implements</span> ';
          echo join(array_map(function($v){return '<a href=//php.net/class.'.str_replace('\\','_',$v).'>'.$v.'</a>';}, $i),', ');
        }
        echo ' <small>{ '.str_repeat('.',max(min(count($methods),32),1)).' }</small>';
        if($count){
          echo '</summary>';
          $n=10;
          if($const){
            if(count($const)>$n)
            echo '<ol class=const>',xxx(array_slice($const,0,$n)),'<details><summary>MORE ',count($const)-$n,' ...</summary>',xxx(array_slice($const,$n)),'</details>','</ol>';
            else
            echo '<ol class=const>',xxx($const),'</ol>';
          }
          if($prop) echo '<ul>',xxx($prop),'</ul>';
          if($methods) echo '<ol>',xxx($methods),'</ol>';
          echo '</details>';
        }
        break;
      default:
        echo "<li class=$t><dfn>{$v->name}</dfn>{} $cls</li>";
      }
      break;
    default:
      echo "<li class=\"$t\"><dfn>{$v->name}</dfn>$v xxxxxxxxxxxxxxxxx</li>";
    }

  }
} // }}}
?>
<!doctype html>
<meta charset=utf-8>
<title><?=PHP_SAPI,' ',PHP_VERSION?></title>

<link href="https://fonts.googleapis.com/css?family=Fira+Mono|Fira+Sans|Source+Sans+Pro" rel="stylesheet">
<style>
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

.args {
  color: #aaa;
}
.args:empty:before{
  content: 'void';
  color: #737373;
}
.args .type{
  color: #693;
}
.args .optional var{
  color: blue;
}
.args .required {
  color: #369;
  font-weight: normal;
}

.nodetails{
  text-indent: 26px;
}

details ul,
details ol{
  margin: .5em .2em;
}

details ol{
  border-left: solid 3px #aae;
}

details ul{
  border-left: solid 3px #aea;
}

details ul.const{
  border-left: solid 3px #aea;
}

.boolean,
.null {
  color: red;
}
.prop,
.string {
  color: blue;
}
.integer,
.double {
  color: red;
}

.syn {
  color: #793862;
}

ul.const li:before,
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
  content: "'] ";
  color: #bbb;
}
output:before {
  content: '//🎉 return ';
}
output {
  color: green;
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
</style>


<header>
<h1><?=PHP_SAPI,' ',PHP_VERSION?></h1>
</header>

<?php
if($_SERVER['SCRIPT_FILENAME']!==__FILE__){

  echo '<p>', __FILE__;

  echo '<h3>User Defined Constants</h3>';
  echo '<ol>';
  echo xxx(get_defined_constants(1)['user']);
  echo '</ol>';

  echo '<h3>User Defined Functions</h3>';
  echo '<ol>';
  echo xxx(array_map(function($v){return new ReflectionFunction($v);}, get_defined_functions()['user']));
  echo '</ol>';

  echo '<h3>User Declared Classes</h3>';
  echo '<ol>';
  echo xxx(array_filter(array_map(function($v){$c = new ReflectionClass($v);if($c->isUserDefined())return $c;}, get_declared_classes())));
  echo '</ol>';

  echo '<h3>User Declared Interfaces</h3>';
  echo '<ol>';
  echo xxx(array_filter(array_map(function($v){$c = new ReflectionClass($v);if($c->isUserDefined())return $c;}, get_declared_interfaces())));
  echo '</ol>';
  die();
}
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
    echo '<ol>';
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
