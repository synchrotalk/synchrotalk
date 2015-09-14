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


if(isset($_SESSION["session_username"])){
  header("Location:intropage.php");
}

if(isset($_POST["login"])){
  if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username=($_POST["username"]);
    $password=($_POST["password"]);
    $users_query=db::Query("SELECT * FROM users WHERE username=$1 AND password=$2",[$username,$password],true); //Запрос
    var_dump($users_query);
    if($users_query())
    {
     // while($users_query) //Преврщаем его в ассоц массив
     // {
        $dbusername=$users_query['username']; 
        $dbpassword=$users_query['password'];


     // }
      if ($dbusername==$username && $dbpassword==$password){
        $_SESSION['session_username']=$username;
        echo "Вы вошли, приятного дня.";
      }
    }
      else {
      echo "Не правильный Логин или Пароль";} 

    }
    else {
    echo "Поля не заполнены.";
  }
}

?>



<div class="container mlogin">
<div id="login">
<h1>Входs</h1>
<form action="" id="loginform" method="post"name="loginform">
<p><label for="user_login">Имя опльзователя<br>
<input class="input" id="username" name="username"size="20"
type="text" value=""></label></p>
<p><label for="user_pass">Пароль<br>
 <input class="input" id="password" name="password"size="20"
  type="password" value=""></label></p> 
	<p class="submit"><input class="button" name="login"type= "submit" value="Log In"></p>
	<p class="regtext">Еще не зарегистрированы?<a href= "register.php">Регистрация</a>!</p>
   </form>
 </div>
  </div>
<?php include("includes/footer.php"); ?>
