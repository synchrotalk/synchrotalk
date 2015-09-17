<?php
class user extends api{
  protected function Reserve(){
    return "User reserve";
  }

  protected function login(){
    if(isset($_SESSION["session_username"])){
      if(isset($_POST["login"])){
        if(!empty($_POST['username']) && !empty($_POST['password'])) {
          $username=($_POST["username"]);
          $password=($_POST["password"]);
          $users_query=db::Query("SELECT * FROM users WHERE username=$1 AND password=$2",[$username,$password],true); //Запрос
          var_dump($users_query);

          if($users_query())
          {
            $dbusername=$users_query ->username; 
            $dbpassword=$users_query ->password;
      
            if ($dbusername==$username && $dbpassword==$password){
              $_SESSION['session_username']=$username;
              return "Вы вошли, приятного дня.";
            }
          }
            else {
            return "Не правильный Логин или Пароль";} 
      
          }
          else {
          return "Поля не заполнены.";
        }
      }    
  }
}
  protected function register(){
    if(isset($_POST["register"])){
      if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $username=$_POST['username'];              
        $password=$_POST['password'];                                             
        $sameusers=db::Query("select * from users WHERE username=$1",[$username]);
        if($sameusers()==0){                                                      
          $useradd="INSERT INTO users (username,password) VALUES ($1,$2) RETURNING id"; 
          $result= db::Query ($useradd,[$username,$password],true);
          if($result() != 0){
            return "Аккаунт создан, вы прекрасны";
          } else {
            return "Ошибка :p";
          }
        } else {
          return "Такой чувак уже есть.";
        }
      } else {
        return "Поля незаполнены!";
      }
    }

  }

  protected function music(){
    return "Hello";
  }
  protected function main(){
    return "world";
  } 
}
