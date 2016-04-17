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
    session_start();
    if(empty($data->title) || empty($data->members[0]))
      return false;

    $data->members[] = $_SESSION['username'];

    db::Query("INSERT INTO threads (title) VALUES(:title)",
      [
        ':title' => $data->title,
      ]);

    $sql_params =
    [
      ':thread_id' => db::AffectedID(),
    ];

    foreach ($data->members as $member)
    {
      $sql_params[':username'] = $member;
      db::Query("INSERT INTO thread_users (thread_id, username) VALUES(:thread_id, :username)", $sql_params);
    }

    return $lastInsertId;
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
