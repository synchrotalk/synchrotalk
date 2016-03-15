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
    ];
  }

}
