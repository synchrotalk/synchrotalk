<?php

class auth extends api
{

  protected function Reserve()
  {
    return
    [
      'design' => 'user/main',
    ];
  }

  protected function site($username)
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

}
