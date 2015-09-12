
<?php 
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

  
if(isset($_POST["register"])){
  if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username=$_POST['username'];              
    $password=$_POST['password'];                                             
    $sameusers=db::Query("select * from users WHERE username=$1",[$username]);                             
    if($sameusers()==0){                                                      
      $useradd="INSERT INTO users (username,password) VALUES ('$username', '$password')"; 
      $result= $pg -> Query ($useradd);
      if($result != 0){
        $message = "Аккаунт создан, вы прекрасны";
      } else {
        $message = "Ошибка :p";
      }
    } else {
      $message = "Такой чувак уже есть.";
    }
  } else {
    $message = "Поля незаполнены!";
  }
}

?>
<?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>

<div class="container mregister">
<div id="login">
<h1>Регистрация</h1>
<form action="register.php" id="registerform" method="post"name="registerform">
<p><label for="user_pass">Имя пользователя<br>
<input class="input" id="username" name="username"size="20" type="text" value=""></label></p>
<p><label for="user_pass">Пароль<br>
<input class="input" id="password" name="password"size="32"   type="password" value=""></label></p>
<p class="submit"><input class="button" id="register" name= "register" type="submit" value="Зарегистрироваться"></p>
<p class="regtext">Уже зарегистрированы? <a href= "login.php">Введите имя пользователя</a>!</p>
 </form>
</div>
</div>

<?php include("includes/footer.php"); ?>
