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
      session_start();

    return $_SESSION;
  }

  private function login()
  {
    db::Query("INSERT INTO users VALUES ()");

    $uid = db::AffectedID();

    $my_name = &$this->get_uid();
    $my_name = $uid;
  }

  private function get_uid()
  {
    return $this->GetSessionStorage()['username'];
  }

  private function is_logined()
  {
    return null == $this->get_uid();
  }

  public function uid()
  {
    if (!$this->is_logined())
      $this->login();
    return $this->get_uid();
  }
}
