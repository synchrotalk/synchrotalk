<?php

class inbox extends api
{
  protected function Reserve()
  {
    session_start();
    if(isset($_SESSION['username']))
    {
      $threads = $this->get_all_user_threads($_SESSION['username']);
    }
    return
    [
      'design' => 'inbox/main',
      'data' => [
        'threads' => $threads,
      ],
    ];
  }

  protected function add($data)
  {
    session_start();
    if(!empty($data->title) AND !empty($data->members[0])){
      db::Query("INSERT INTO threads (title) VALUES(:title)", [':title' => $data->title]);
      $lastInsertId = db::lastInsertId();

      $data->members[] = $_SESSION['username'];
      foreach ($data->members as $member) {
        $sql_params = [
          ':thread_id' => $lastInsertId,
          ':username' => $member
        ];
        db::Query("INSERT INTO thread_users (thread_id, username) VALUES(:thread_id, :username)", $sql_params);
      }
      return $lastInsertId;
    }
    return false;
  }

  protected function get_all_user_threads($username)
  {
    $sql="SELECT threads.* FROM threads ";
    $sql.="INNER JOIN thread_users ";
    $sql.="ON threads.id=thread_users.thread_id ";
    $sql.="WHERE thread_users.username = :username ";
    $sql.="ORDER BY threads.id ";

    $threads = db::Query($sql,[':username'=>$username]);
    return $threads;
  }

}
