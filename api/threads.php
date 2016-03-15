<?php

class threads extends api
{
  protected function Reserve()
  {

    return
    [
      'design' => 'threads/main',
    ];
  }

  protected function add($data)
  {
    if(!empty($data->title) AND !empty($data->members[0])){
      $res = db::Query("INSERT INTO threads (title) VALUES(:title)", [':title' => $data->title]);
    }
  }

}
