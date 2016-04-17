<?php

class user extends api
{
  protected function Reserve()
  {
    return
    [
      'design' => 'user/login',
    ];
  }

  protected function login($username = null)
  {
    session_start();

    $login = false;
    if (!is_null($username))
    {
      $_SESSION['username'] = $username;
      $login = true;
    }

    return
    [
      'design' => 'user/login',
      'data' =>
      [
        'login' => $login,
        'username' => $this->GetUserName(),
      ],
    ];
  }

  protected function GetUserName()
  {
    if(isset($_SESSION['username']))
      return $_SESSION['username'];

    return false;
  }

}
