
<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1); //Ошибки 

require 'vendor/autoload.php';
include_once('vendor/enelar/phpsql/phpsql.php'); //Включения
include_once('vendor/enelar/phpsql/pgsql.php');
$sql = new phpsql();
$pg = $sql->Connect("pgsql://postgres@localhost/synchrotalk");
$verynew=$pg -> Query ("select * from users WHERE username='Misha'");
//$a=$pg -> Query ("select * from sometable");
//$numa=pg_num_rows($a);
//echo "$numa";
//$a=$pg -> Query ("select * from sometable WHERE id=5");
//$a=$pg -> Query ("select * from sometable WHERE id=$1",[5]);
//var_dump($verynew); 

  
if(isset($_POST["register"])){                                                            //Если пользователь нажал на кнопку
  if(!empty($_POST['username']) && !empty($_POST['password'])) {                          //Если поля не пустые
    $username=htmlspecialchars($_POST['username']);                                       //Возьми параметр с поля "Юзер"
    $password=htmlspecialchars($_POST['password']);                                       //И параметр с поля "Пасс"   
    $sameusers=$pg -> Query ("select * from users WHERE username='".$username."'");       //Узнай сколько в базе 
    $numrows=count($sameusers);                                                    //Таких же колонок
    if($numrows==0){                                                                      //И если 0 то отправь их 
      $useradd="INSERT INTO users (username,password) VALUES ('$username', '$password')"; //В таблицу
      $result= $pg->Query ($useradd);                                                     //Наших чарующих баз
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
