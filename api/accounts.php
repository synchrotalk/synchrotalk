<?php

class accounts extends api
{
  protected function reserve()
  {
    return
    [
      "design" => "accounts/inbox",
    ];
  }

  protected function create()
  {
    return
    [
      "design" => "accounts/create/page",
    ];
  }

  protected function add($network, $login, $password)
  {
    $storage = &phoxy::Load('user')->GetSessionStorage();
    $accounts = &$storage['accounts'];

    phoxy_protected_assert(!isset($accounts[$network]), "In demo mode one account per social network");

    $networks = phoxy::Load('networks');

    $supported = $networks->supported()->__2array();
    phoxy_protected_assert(in_array($network, $supported['networks']), "Social network unsupported");

    $res = $networks->$network->log_in($login, $password);
    phoxy_protected_assert(!is_null($res), "Login/password invalid");

    $accounts[$network] =
    [
      "login" => $login,
      "password" => $password,
    ];

    return "success";
  }

  public function connected()
  {
    $uid = phoxy::Load('user')->uid();
    return db::Query("SELECT * FROM connections WHERE uid=$1", [$uid]);
  }

  protected function itemize()
  {
    return
    [
      "design" => "accounts/list",
      "data" =>
      [
        "accounts" => $this->connected(),
      ],
    ];
  }
}
