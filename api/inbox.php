<?php

class inbox extends api
{
  protected function Reserve()
  {
    $inbox = $this->fork()->brief_inbox();

    return
    [
      'design' => 'inbox/main',
      'data' =>
      [
        'threads' => $inbox,
      ],
    ];
  }

  protected function add($title, $members)
  {
    return phoxy::Load('thread')->Create($title, $members);
  }

  protected function brief_inbox()
  {
    $threads = phoxy::Load('thread')->FindByUser();

    $ret = [];
    foreach ($threads as $thread)
      $ret[] = phoxy::Load('thread')->info($thread);

    return
    [
      "data" =>
      [
        "inbox" => $ret,
      ]
    ];
  }
}
