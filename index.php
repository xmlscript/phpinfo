#! /usr/bin/php
<?php
ini_get('output_buffering') and ob_clean();

if(PHP_SAPI==='cli') die('usage: `php -S 127.0.0.1` and `lynx 127.0.0.1` or php --r[fcezi]'.PHP_EOL);

function xxx(array $arr){ // {{{
  foreach($arr as $k=>$v){
    $t = gettype($v);
    switch($t){
    case 'integer':
      switch($k){
        case 'REQUEST_TIME':
        case 'PHP_MAJOR_VERSION':
        case 'PHP_MINOR_VERSION':
        case 'PHP_RELEASE_VERSION':
        case 'PHP_VERSION_ID':
        case 'PHP_ZTS':
        case 'PHP_DEBUG':
        case 'PHP_INT_MIN':
        case 'PHP_INT_MAX':
        case 'PHP_FLOAT_DIG':
        case 'DEBUG_BACKTRACE_PROVIDE_OBJECT':
        case 'DEBUG_BACKTRACE_IGNORE_ARGS':
        case 'LIBXML_VERSION':
        case 'OPENSSL_VERSION_NUMBER':
        case 'PCRE_VERSION_MAJOR':
        case 'PCRE_VERSION_MINOR':
        case 'ZLIB_ENCODING_RAW':
        case 'GD_MAJOR_VERSION':
        case 'GD_MINOR_VERSION':
        case 'INTL_MAX_LOCALE_LEN':
        case 'SODIUM_LIBRARY_MAJOR_VERSION':
        case 'SODIUM_LIBRARY_MINOR_VERSION':
        case 'LIBXSLT_VERSION':
        case 'LIBEXSLT_VERSION':
        case 'SWOOLE_VERSION_ID':
        case 'SWOOLE_MAJOR_VERSION':
        case 'SWOOLE_MINOR_VERSION':
        case 'SWOOLE_RELEASE_VERSION':
        case 'SWOOLE_TIMER_MIN_MS':
        case 'SWOOLE_TIMER_MAX_MS':
        case 'SWOOLE_DEFAULT_MAX_CORO_NUM':
        case 'SWOOLE_CORO_MAX_NUM_LIMIT':
          echo "<li><dfn>$k</dfn> = <var class=$t>$v</var></li>";
          break;
        default:
          if($v > 0){
            $bit = [];
            for($i=0;$i<64;$i++)
              if($v & 2**$i)
                $bit[] =  $i;
            $bits = count($bit)>1?join($bit,'|'):"<span class=bit>{$bit[0]}</span>";
            echo "<li><dfn>$k</dfn> = <var class=$t>$v</var> <small>= 2 ** $bits</small></li>";
          }
          else
            echo "<li><dfn>$k</dfn> = <var class=$t>$v</var></li>";
      }
      break;
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
      echo "<li><dfn>$k</dfn> = \"<var class=$t>$v</var>\"";
      if(strpos($v,'#')===0&&preg_match('/^#[[:xdigit:]]{6}$/i',$v)) echo "<input type=color value=$v disabled>";
      if(is_file($v))
      echo ' <mark>',round(($byte=filesize($v))/pow(1024,($i=floor(log($byte,1024)))),2),@['byte','kb','mb','gb','tb'][$i],'</mark>';
      break;
    case 'object':
      $cls = get_class($v);
      switch($cls){
      case 'ReflectionFunction':
        echo '<li>';
        echo "<a href=//php.net/function.{$v->name}><dfn>{$v->name}</dfn></a>";
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
        echo ';';
        $f = $v->getFileName();
        //FIXME: How to open local file
        if($f) echo " <a href=file://$f>$f</a> ", $v->getStartLine();

        if($v->isDeprecated()) echo ' 🚫 <strong>DEPRECATED</strong>';


        if(in_array($v->name,[
          'zend_version',
          'func_num_args',
          'gc_mem_caches',
          'gc_collect_cycles',
          'gc_enabled',
          'gc_status',
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
          'get_defined_vars',
          'get_loaded_extensions',
          'timezone_abbreviations_list',
          'openssl_get_cert_locations',
          'openssl_get_curve_names',
          'zlib_get_coding_type',
          'filter_list',
          'hash_algos',
          'hash_hmac_algos',
          'spl_classes',
          'session_get_cookie_params',
          'session_status',
          'localeconv',
          'get_included_files',
          'get_required_files',
          'get_class',
          'get_called_class',
          'get_parent_class',
          'get_declared_classes',
          'get_declared_traits',
          'get_declared_interfaces',
          'get_defined_functions',
          'get_resources',
          'get_defined_constants',
          'debug_backtrace',
          'func_get_args',
          'error_reporting',
          'mktime',
          'gmmktime',
          'localtime',
          'getdate',
          'date_create',
          'date_create_immutable',
          'timezone_identifiers_list',
          'openssl_pkey_new',
          'openssl_get_md_methods',
          'openssl_get_cipher_methods',
          'openssl_error_string',
          'spl_autoload_extensions',
          'spl_autoload_register',
          'spl_autoload_functions',
          'session_id',
          'session_create_id',
          'session_regenerate_id',
          'session_cache_limiter',
          'session_cache_expire',
          'get_html_translation_table',
          'srand',
          'mt_rand',
          'mt_srand',
          'getenv',
          'gettimeofday',
          'getrusage',
          'ini_get_all',
          'libxml_use_internal_errors',
        ])){
          $ret = ($v->name)();
          $t = gettype($ret);
          if($t==='array') $ret = array_slice($ret,0,100);//FIXME json_encode无法处理太大的数组
          $str = json_encode($ret,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
          if(strlen($str)> 128) $str = substr($str,0,128).' ...';

          $obj = $t==='object'?'<small style=color:grey>('.get_class($ret).')</small> ':'';

          echo "<output class=$t>$obj $str</output>";
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
        echo ';';
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

        echo '<span class=syn>';

        echo $has = join(Reflection::getModifierNames($v->getModifiers()),' ');
        if($has) echo ' ';

        echo $v->isInterface()?'interface':($v->isTrait()?'trait':'class'),'</span> ';

        echo '<a href=//php.net/class.',str_replace('\\','_',$v->name),'><dfn>',$v->name,'</dfn></a>';

        if($p = $v->getParentClass())
          echo ' <span class=syn>extends</span> <a href=//php.net/class.',str_replace('\\','_',$p->name),'>',$p->name,'</a>';

        if($i = $v->getInterfaceNames()){
          echo ' <span class=syn>implements</span> ';
          echo join(array_map(function($v){return '<a href=//php.net/class.'.str_replace('\\','_',$v).'>'.$v.'</a>';}, $i),', ');
        }

        echo ' <small>{ ',str_repeat('.',count($methods)),' }</small>';
        if($count){
          echo '</summary>';
          $more=16;
          if($const){
            if(count($const)>$more)
            echo '<ol class=const>',xxx(array_slice($const,0,$more)),'<details<summary>MORE ',count($const)-$more,' ...</summary>',xxx(array_slice($const,$more)),'</details>','</ol>';
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
var{
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
  text-indent: 18px;
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

.boolean {
  color: #75507b;
}
.NULL {
  color: #3465a4;
}
.prop,
.string {
  color: #c00;
}
.integer {
  color: #4e9a06;
}
.double {
  color: #f57900;
}
.array,.object {
  color: darkred;
}
output.resource:before {
  content: ' === (resource)';
}
output.array:before {
  content: ' === (arr)';
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
input[type=color]{
  display: inline-block;
  margin-left: .5em;
  padding: 0;
  height: 1em;
  width: 2em;
  border:none;
}
output:before {
  color: grey;
  content: ' === ';
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
.bit {
  background-color: yellow;
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
