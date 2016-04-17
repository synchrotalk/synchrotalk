<?php

class thread extends api
{

  protected function Reserve($thread_id)
  {
    session_start();

    $res = db::Query("SELECT * FROM threads WHERE id=:id LIMIT 1",
      [
        ':id' => $thread_id,
      ], true);

    if(!$res())
      return
      [
        'error' => 'Thread not found',
      ];


    return
    [
      'design' => 'thread/main',
      'data' =>
      [
        'members' => $this->get_thead_members($thread_id),
        'messages' => $this->get_messages($thread_id),
        'username' => $_SESSION['username'],
        't_id' => $t_id,
      ],
      'script' => '/js/thread.js',
    ];
  }

  protected function get_thead_members($thread_id)
  {
    $res = db::Query("SELECT * FROM thread_users WHERE thread_id = :t_id LIMIT 200", [
        ':t_id' => (int)$thread_id,
      ]);
    return $res;
  }

  protected function get_messages($thread_id, $last_id=0)
  {
    $sql = "SELECT * FROM thread_messages WHERE thread_id = :t_id AND id > :last_id LIMIT 20";

    $res = db::Query($sql,
      [
        ':t_id' => $thread_id,
        ':last_id' => $last_id,
      ]);

    return $res;
  }

  protected function add_message($thread_id, $username, $message, $members)
  {
    if($message == "")
      return
      [
        "error" => "You trying send empty message",
      ];

    $params =
    [
      ':t_id' => $t_id,
      ':username' => $username,
      ':message' => $message,
    ];

    $res = db::Query("INSERT INTO thread_messages (thread_id, username, message) VALUES (:t_id, :username, :message)", $params);
    //send event
    $this->emmit_event($thread_id, $members, $message, $username, db::AffectedID());
  }

  protected function emmit_event($thread_id, $members, $message, $username, $lastInsertId)
  {
    $event_data =
    [
      'thread_id' => $thread_id,
      'messsage_id' => $lastInsertId,
      'message' => $message,
      'username' => $username,
    ];

    foreach ($members as $member)
      phoxy::Load('event')->add_event($member->username, $event_data, 'thread');
  }

}
