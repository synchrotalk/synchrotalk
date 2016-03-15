<?php

class thread extends api
{

  protected function Reserve($t_id)
  {
    $res = db::Query("SELECT * FROM threads WHERE id=:id",[':id' => (int)$t_id]);
    if($res[0]){
      $t_members = $this->get_thead_members($t_id);
    }
    return
    [
      'design' => 'thread/main',
      'data' => [
        'members' => $t_members,
      ],
    ];
  }

  protected function get_thead_members($t_id)
  {
    $res = db::Query("SELECT * FROM thread_users WHERE thread_id = :t_id", [':t_id' => (int)$t_id]);
    return $res;
  }

}
