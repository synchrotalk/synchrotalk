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

    $inbox = $_SESSION['inbox'];

    if (!count($inbox))
    foreach ($accounts as $network => $account)
    {
      $network = $networks->get_network_object($network);
      $login = $network->log_in($account['login'], $account['password']);

      $threads = $network->threads();
      $inbox = array_merge(
        $inbox
        , $threads['requests']
        , $threads['inbox']['threads']);
    }
    $_SESSION['inbox'] = $inbox;

    return
    [
      "design" => "inbox/itemize",
      "data" =>
      [
        "inbox" => $inbox,
      ],
    ];
  }
}
