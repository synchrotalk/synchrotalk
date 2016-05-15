<?php

class message extends api
{
  public function add($thread_id, $message)
  {
    phoxy_protected_assert(!empty($message), "Unable to send empty message");

    $author = phoxy::Load('user')->MyName();

    db::Query
    (
      "INSERT INTO thread_messages
        (thread_id, username, message)
        VALUES (:t_id, :username, :message)
      "
      ,
      [
        ':t_id' => $thread_id,
        ':username' => $author,
        ':message' => $message,
      ]
    );

    return db::AffectedID();
  }
}
