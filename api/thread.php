<?php

class thread extends api
{

  protected function Reserve($t_id)
  {
    session_start();
    $res = db::Query("SELECT * FROM threads WHERE id=:id LIMIT 1",[':id' => (int)$t_id]);
    if($res[0]){
      $t_members = $this->get_thead_members($t_id);
      $t_messages = $this->get_messages($t_id);
    }
    return
    [
      'design' => 'thread/main',
      'data' => [
        'members' => $t_members,
        'messages' => $t_messages,
        'username' => $_SESSION['username'],
        't_id' => $t_id,
      ],
    ];
  }

  protected function get_thead_members($t_id)
  {
    $res = db::Query("SELECT * FROM thread_users WHERE thread_id = :t_id LIMIT 200", [':t_id' => (int)$t_id]);
    return $res;
  }

  protected function get_messages($t_id, $last_id=0)
  {
    $sql = "SELECT * FROM thread_messages WHERE thread_id = :t_id AND id > :last_id LIMIT 20";
    $res = db::Query($sql, [':t_id'=>$t_id, ':last_id'=>$last_id]);

    return $res;
  }

  protected function add_message($t_id, $username, $message)
  {
    if($message!=="")
    {
      $s_params = [
        ':t_id' => $t_id,
        ':username' => $username,
        ':message' => $message,
      ];
      $res = db::Query("INSERT INTO thread_messages (thread_id, username, message) VALUES (:t_id, :username, :message)", $s_params);
    }
  }

}
