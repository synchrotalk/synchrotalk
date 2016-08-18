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

  protected function grant_demo_access()
  {
    $this->revoke_demo_access();
    return $this->add(
      conf()->demo->network,
      conf()->demo->login,
      conf()->demo->password
      );
  }

  protected function revoke_demo_access()
  {
    $storage_functor = phoxy::Load('user')->StorageShortcut();
    $accounts = &$storage_functor()['accounts'];
    $accounts = [];
  }

  protected function demo_me()
  {
    $storage_functor = phoxy::Load('user')->StorageShortcut();
    $accounts = &$storage_functor()['accounts'];

    phoxy_protected_assert(count($accounts), "User not logined");

    return
    [
      "script" => "user",
      "before" => "user.login",
      "data" =>
      [
        "user" => array_values($accounts)[0]['user'],
      ],
    ];
  }

  public function save_network($network, $token, $expiration = null)
  {
    if (is_null($expiration))
      $expiration = time() + 10 * 365 * 3600;

    $res = db::Query("
      INSERT INTO personal.tokens
        (uid, network, expiration, token_data)
        VALUES ($1, $2, timestamptz 'epoch' + $3 * interval '1 second', $4)
        RETURNING account_id",
        [ db::UID(), $network, $expiration, json_encode($token) ], true);

    return $res->account_id;
  }
/*
  protected function add($network, $login, $password)
  {
    $accounts = $this->access_accounts_private_storage()();

    phoxy_protected_assert(!isset($accounts[$network]), "In demo mode one account per social network");

    $networks = phoxy::Load('networks');

    phoxy_protected_assert($networks->supported($network), "Social network unsupported");

    $insta = $networks->get_network_object($network);

    $user = $insta->log_in($login, $password);
    phoxy_protected_assert($user, "Login/password invalid");

    $user['network'] = $network;

    $accounts[$network] =
    [
      "network" => $network,
      "login" => $login,
      "password" => $password,
      "user" => $user,
    ];

    return
    [
      "design" => "accounts/create/welcome",
      "data" =>
      [
        'user' => $user,
        'next' => 'inbox',
      ],
      "script" => "user",
      "before" => "user.login",
    ];
  }

  public function &access_accounts_private_storage()
  {
    $storage_functor = phoxy::Load('user')->StorageShortcut();
    $accounts = &$storage_functor()['accounts'];

    if (!is_array($accounts))
      $accounts = [];

    return function &() use (&$accounts)
    {
      return $accounts;
    };
  }
*/
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
