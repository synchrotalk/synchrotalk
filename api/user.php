<?php

class user extends api
{

  protected function Reserve()
  {
    return
    [
      'design' => 'user/main',
    ];
  }

  protected function login()
  {
    return
    [
      'design' => 'user/login',
      'data' => [
        'username' => $this->GetUserName(),
      ],
    ];
  }

  protected function auth($username)
  {
    $login = false;
    if($username!=="")
    {
      session_start();
      $_SESSION['username'] = $username;
      $login = true;
    }
    return
    [
      'design' => 'user/login',
      'login' => $login,
    ];
  }


  protected function GetUserName()
  {
    session_start();
    if(isset($_SESSION['username'])){
      return $_SESSION['username'];
    }

    return false;
  }

}
