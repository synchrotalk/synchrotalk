<?php

class users extends api
{
  protected function info($account_id, $uid)
  {
    $network = phoxy::Load('networks/network')->by_account_id($account_id);
    $user = $network->user($uid);

    return
    [
      'design' => 'thread/users/one',
      'data' => $user,
    ];
  }
}
