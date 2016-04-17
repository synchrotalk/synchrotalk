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

    $storage = &$this->GetSessionStorage();

    $storage['username'] = $username;

    return
    [
      'data' =>
      [
        'login' => true,
      ],
    ];
  }
}
