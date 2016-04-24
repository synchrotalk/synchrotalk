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

  private function MarkWithNetwork($network, $inbox)
  {
    return array_map(function($thread) use ($network)
    {
      $thread['network'] = $network;
      return $thread;
    }, $inbox);
  }

  protected function itemize()
  {
    $accounts = phoxy::Load('accounts')->connected();
    $networks = phoxy::Load('networks');

    $inbox = $_SESSION['inbox'];

    if (!count($inbox))
    foreach ($accounts as $network => $account)
    {
      $connection = $networks->get_network_object($network);
      $login = $connection->log_in($account['login'], $account['password']);

      $threads = $connection->threads();
      $inbox = array_merge
      (
        $inbox
        , $this->MarkWithNetwork($network, $threads['requests'])
        , $this->MarkWithNetwork($network, $threads['inbox']['threads'])
      );
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
