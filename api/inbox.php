<?php

class inbox extends api
{
  protected function reserve()
  {
    return
    [
      'design' => 'inbox/index',
    ];
  }

  protected function itemize()
  {
    $accounts = phoxy::Load('accounts')->connected();
    $networks = phoxy::Load('networks');

    $inbox = [];
    foreach ($accounts as $account)
    {
      $network = $networks->get_network_object($account['network']);
      $network->log_in($account['login'], $account['password']);
      $inbox = array_merge($inbox, $network->threads());
    }

    return
    [
      "design" => "inbox/itemize",
      "data" =>
      [
        "threads" => $inbox,
      ],
    ];
  }
}
