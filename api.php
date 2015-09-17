<?php
session_start();


include("includes/header.php"); 
error_reporting(E_ALL);
ini_set("display_errors", 1); //Ошибки 

require 'vendor/autoload.php';
include_once('vendor/enelar/phpsql/phpsql.php'); //Включения для подключения 
include_once('vendor/enelar/phpsql/pgsql.php');
include_once('vendor/enelar/phpsql/wrapper.php');
$sql = new phpsql();
$pg = $sql->Connect("pgsql://postgres@localhost/synchrotalk");
include_once('vendor/enelar/phpsql/db.php');
db::Bind(new phpsql\utils\wrapper($pg)); 




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

