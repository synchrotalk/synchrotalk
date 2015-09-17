
<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1); //Ошибки 
include 'includes/header.php';
require 'vendor/autoload.php';
include_once('vendor/enelar/phpsql/phpsql.php'); //Включения для подключения
include_once('vendor/enelar/phpsql/pgsql.php');
include_once('vendor/enelar/phpsql/wrapper.php');
$sql = new phpsql();
$pg = $sql->Connect("pgsql://postgres@localhost/synchrotalk");
include_once('vendor/enelar/phpsql/db.php');
db::Bind(new phpsql\utils\wrapper($pg));



if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>

<div class="container mregister">
<div id="login">
<h1>Регистрация</h1>
<form action="/api/user/register" id="registerform" method="post"name="registerform">
<p><label for="user_pass">Имя пользователя<br>
<input class="input" id="username" name="username"size="20" type="text" value=""></label></p>
<p><label for="user_pass">Пароль<br>
<input class="input" id="password" name="password"size="20"   type="password" value=""></label></p>
<p class="submit"><input class="button" id="register" name= "register" type="submit" value="Зарегистрироваться"></p>
<p class="regtext">Уже зарегистрированы? <a href= "login.php">Введите имя пользователя</a>!</p>
 </form>
</div>
</div>

<?php include("includes/footer.php"); ?>
