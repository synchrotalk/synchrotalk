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

  private function ReqursiveRemoveCandidates($media)
  {
    if (!is_array($media))
      return $media;

    $ret = [];
    foreach ($media as $key => $val)
      if ($key === 'candidates')
        return $val[0];
      else
        $ret[$key] = $this->ReqursiveRemoveCandidates($val);

    return $ret;
  }

  private function MarkWithNetwork($network, $inbox)
  {
    return array_map(function($thread) use ($network)
    {
      $thread['network'] = $network;
      return $this->ReqursiveRemoveCandidates($thread);
    }, $inbox);
  }

  protected function itemize()
  {
    $accounts = phoxy::Load('accounts')->connected();
    $networks = phoxy::Load('networks');

    $inbox = [];
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
