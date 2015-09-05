<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); //Ошибки 

require 'vendor/autoload.php';

echo "Hello World"; //код программы


include_once('vendor/enelar/phpsql/phpsql.php'); //Включения
include_once('vendor/enelar/phpsql/pgsql.php');
$sql = new phpsql();
$pg = $sql->Connect("pgsql://postgres@localhost/synchrotalk");
//$a=$pg -> Query ("select * from sometable");
//$a=$pg -> Query ("select * from sometable WHERE id=5");
$a=$pg -> Query ("select * from sometable WHERE id=$1",[5]);
var_dump($a); 