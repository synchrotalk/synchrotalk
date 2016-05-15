<?php

class demo extends api
{
  protected function Reserve()
  {
    return
    [
      "design" => "inbox/demo",
    ];
  }

  protected function Try_It()
  {
    phoxy::Load('user')->ResetSession();
    return phoxy::Load('accounts', true)->grant_demo_access();
  }

  protected function Login($network, $login, $password)
  {
    phoxy::Load('user')->ResetSession();
    return phoxy::Load('accounts', true)->add($network, $login, $password);
  }
}
