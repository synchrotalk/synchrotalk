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


    echo "This causing extra signin request, refactor";
    list($obj, $user) = $this->network_signin($network, $token);

    $account = db::Query("SELECT account_id
        FROM personal.tokens
        WHERE uid=$1
          AND profile_id=$2", [db::UID(), $user->id], true);

    if ($account())
    { // account known, update token
      $res = db::Query("
        UPDATE personal.tokens
          SET token_data=$2
            , expiration=timestamptz 'epoch' + $3 * interval '1 second'
          WHERE account_id=$1
          RETURNING account_id",
          [ $account->account_id, json_encode($token), $expiration ], true);
    }
    else
    { // account unknown. store
      $res = db::Query("
        INSERT INTO personal.tokens
          (uid, network, expiration, token_data)
          VALUES ($1, $2, timestamptz 'epoch' + $3 * interval '1 second', $4)
          RETURNING account_id",
          [ db::UID(), $network, $expiration, json_encode($token) ], true);
    }

    return $res->account_id;
  }

  private $loaded_accounts = [];

  private function network_signin($network, $token)
  {
    $networks = phoxy::Load('networks');

    phoxy_protected_assert($networks->supported($account->network), "Social network unsupported");

    $obj = $networks->get_network_object($network);

    echo "TODO: Check if token expired";
    $user = $obj->sign_in($token);

    phoxy_protected_assert($user, "Unable to sign in");
    echo "TODO: Make token refresh sequence automatically";

    return [$obj, $user];
  }

  private function get_account_object($account_id)
  {
    if (!empty($this->loaded_accounts[$account_id]))
      return $this->loaded_accounts[$account_id];

    $account = db::Query("SELECT *
      FROM personal.tokens
      WHERE uid=$1
        AND account_id=$2",
        [db::UID(), $account_id], true);

    phoxy_protected_assert($account(), "Account not found. Please connect again");

    list($obj, $user)
      = $this->network_signin
        (
          $account->network,
          json_decode($account->token_data, true)
        );

    db::Query("UPDATE personal.tokens
        SET profile_id=$2
        WHERE account_id=$1", [$account_id, $user->id]);

    return $this->loaded_accounts[$account_id] = $obj;
  }

  public function user($account)
  {
    $ret = db::Query("SELECT *
      FROM personal.account_cache
      WHERE account_id=$1 AND key=$2",
      [$account, "user"], true);

    if (!$ret())
    {
      // Update cache. It can't endless loop
      $this->get_account_object($account);
      return $this->user($account);
    }

    return $ret->data;
  }

  protected function welcome($account)
  {
    return
    [
      "design" => "accounts/create/welcome",
      "data" =>
      [
        'user' => $this->user($account),
        'next' => 'inbox',
      ],
      "script" => "user",
      "before" => "user.login",
    ];
  }

  public function connected()
  {
    $accounts = phoxy::Load('accounts/tokens')->connected();

    $public_fields =
    [
      'network',
      'account_id',
      'expiration',
    ];


    foreach ($accounts as $account)
    {
      $ret = [];
      foreach ($account as $key => $value)
        if (in_array($key, $public_fields))
          $ret[$key] = $value;
      yield $ret;
    };
  }

  protected function itemize()
  {
    return
    [
      "design" => "accounts/list",
      "data" =>
      [
        "accounts" => iterator_to_array($this->connected()),
      ],
    ];
  }
}
