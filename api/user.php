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

  protected function login($username="")
  {
    session_start();
    $login = false;
    if($username!=="")
    {
      $_SESSION['username'] = $username;
      $login = true;
    }
    return
    [
      'design' => 'user/login',
      'login' => $login,
      'data' => [
        'username' => $this->GetUserName(),
      ],
    ];
  }

  protected function GetUserName()
  {
    if(isset($_SESSION['username'])){
      return $_SESSION['username'];
    }

    return false;
  }

}
