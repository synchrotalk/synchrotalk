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
?>



<div class="container mlogin">
<div id="login">
<h1>Вход</h1>
<form action="/api/user/login" id="loginform" method="post"name="loginform">
<p><label for="user_login">Имя пользователя<br>
<input class="input" id="username" name="username"size="20"
type="text" value=""></label></p>
<p><label for="user_pass">Пароль<br>
 <input class="input" id="password" name="password"size="20"
  type="password" value=""></label></p> 
	<p class="submit"><input class="button" name="login"type= "submit" value="Войти"></p>
	<p class="regtext">Еще не зарегистрированы?<a href= "register.php">Регистрация</a>!</p>
   </form>
 </div>
  </div>
<?php include("includes/footer.php"); ?>
