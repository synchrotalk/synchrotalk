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

  public function GetSessionStorage()
  {
    if (session_status() != PHP_SESSION_ACTIVE)
      session_start();

    return $_SESSION;
  }

  protected function login($username)
  {
    phoxy_protected_assert(strlen($username) > 3, "Minimum username length is 3 characters");

    $my_name = &$this->username();
    $my_name = $username;

    return
    [
      'data' =>
      [
        'login' => true,
      ],
    ];
  }

  private function username()
  {
    return $this->GetSessionStorage()['username'];
  }

  public function MyName()
  {
    $ret =  $this->username();
    phoxy_protected_assert($ret, "Login required to proceed");
    return $ret;
  }
}
