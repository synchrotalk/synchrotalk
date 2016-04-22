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

  protected function add_message($thread_id, $message)
  {
    phoxy_protected_assert(!empty($message), "Unable to send empty message");

    $transact = db::Begin();

    $message_id = phoxy::Load('message')->add($thread_id, $message);
    $this->EmmitEvent("new_message", $message_id);

    phoxy_protected_assert(db::Commit(), "Failure at message send");
  }

  private function EmmitEvent($type, $thread_id)
  {
    // TODO
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

  public function FindByUser($username = null)
  {
    if (is_null($username))
      $username = phoxy::Load('user')->MyName();

    $sql =
      "SELECT *
        FROM thread_users
        WHERE username = :username
      ";

    $threads = db::Query($sql, [':username' => $username]);

    $ret = [];
    foreach ($threads as $thread)
      $ret[] = $thread['id'];

    return $ret;
  }

  public function Info($thread)
  {
    $sql =
      "SELECT *
        FROM threads
        WHERE id=:id";

    return db::Query($sql, [':id' => $thread], true);
  }

  public function Create($title, $members)
  {
    phoxy_protected_assert(!empty($title), "Cant create room without title");
    phoxy_protected_assert(is_array($members) && count($members), "Cant create room without recepient");

    $members[] = phoxy::Load('user')->MyName();

    $transact = db::Begin();

    db::Query("INSERT INTO threads (title) VALUES(:title)",
      [
        ':title' => $data->title,
      ]);

    $room_id = db::AffectedID();

    foreach ($members as $member)
      $this->AppendUser($room_id, $member);

    phoxy_protected_assert($transact->Commit(), "Unable to save room");
    return $room_id;
  }

  private function AppendUser($room, $user)
  {
    db::Query(
      "INSERT INTO thread_users
        (thread_id, username)
        VALUES (:thread_id, :username)
      "
      ,
      [
        ':thread_id' => $room,
        ':username' => $user,
      ]);

    return db::AffectedID();
  }
}
