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
    $storage_functor = phoxy::Load('user')->StorageShortcut();
    $accounts = &$storage_functor()['accounts'];

    if (!is_array($accounts))
      $accounts = [];

    phoxy_protected_assert(!isset($accounts[$network]), "In demo mode one account per social network");

    $networks = phoxy::Load('networks');

    phoxy_protected_assert($networks->supported($network), "Social network unsupported");

    $insta = $networks->get_network_object($network);

    $user = $insta->log_in($login, $password);
    phoxy_protected_assert($user, "Login/password invalid");

    $accounts[$network] =
    [
      "network" => $network,
      "login" => $login,
      "password" => $password,
    ];

    return
    [
      "design" => "accounts/create/welcome",
      "data" => $user,
    ];
  }

  public function connected()
  {
    return phoxy::Load('user')->GetSessionStorage()['accounts'];
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
