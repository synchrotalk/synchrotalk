<?php
var_dump($_GET);
function phoxy_conf(){
  $config=phoxy_default_conf();
  $config["api_xss_prevent"]=false;
  $config["api_dir"]="/api";
  return $config;
}

$arr = explode('REDIRECTIT', $_SERVER['QUERY_STRING']);
if (count($arr) != 3)
  die('RPC: Invalid htaccess redirect');
$rpc_string = $arr[1];
if ($rpc_string == '/api/')
  $rpc_string = '/api/main/Home';
$_GET['api'] = $rpc_string;



include 'vendor/phoxy/phoxy/index.php';

