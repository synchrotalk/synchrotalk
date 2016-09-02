<?php

class user extends api
{
  protected function Reserve()
  {
    return
    [
      'design' => 'accounts/login/index',
    ];
  }

  public function StorageShortcut()
  {
    return function &()
    {
      return $this->GetSessionStorage();
    };
  }

  public function &GetSessionStorage()
  {
    if (session_status() != PHP_SESSION_ACTIVE)
    {
      session_cache_limiter("private_no_expire");
      session_start();
    }

    return $_SESSION;
  }

  public function ResetSession()
  {
    $this->GetSessionStorage();
    session_destroy();
  }

  private function login()
  {
    $uid = phoxy::Load('user/store')->Register();

    $my_name = &$this->get_uid();
    $my_name = $uid;

    return $uid;
  }

  private function &get_uid()
  {
    return $this->GetSessionStorage()['username'];
  }

  protected function is_logined()
  {
    return !is_null($this->get_uid());
  }

  public function uid()
  {
    if (!$this->is_logined())
      $this->login();
    return $this->get_uid();
  }
}
