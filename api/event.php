<?php

class event extends api
{
  protected function Reserve()
  {

  }

  protected function add_event($target=null, $data=null, $target_group=null)
  {
    if($target && $data && $target_group){
      $sql = "INSERT INTO events (target,data,target_group) VALUES (:target,:data,:target_group)";
      $sql_params = [
        ':target' => $target,
        ':data' => json_encode($data, true),
        ':target_group' => $target_group,
      ];
      db::Query($sql, $sql_params);
    }
  }

  protected function get_events($target_group=null)
  {
    if(!$target_group)
    {
      error_log('Missed Target Group!');
      return false;
    }
    session_start();
    $sql = "SELECT * FROM events WHERE target=:target AND target_group=:target_group";
    $sql_params = [':target' => $_SESSION['username'], ':target_group' => $target_group];
    $events = db::Query($sql, $sql_params);
    foreach ($events->__2array() as &$event){
      $event['data'] = json_decode($event['data'], true);
    }
    return [
      'data' => [
        'events' => $events,
        'user' => $_SESSION['username'],
      ],
    ];
  }


}
