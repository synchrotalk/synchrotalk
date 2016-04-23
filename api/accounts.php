<?php

class accounts extends api
{
  protected function reserve()
  {
    return
    [
      "design" => "accounts/inbox",
    ];
  }

  protected function add()
  {
    return
    [
      "design" => "accounts/add/page",
    ];
  }

  public function connected()
  {
    $uid = phoxy::Load('user')->uid();
    return db::Query("SELECT * FROM connections WHERE uid=$1", [$uid]);
  }

  protected function itemize()
  {
    return
    [
      "design" => "accounts/list",
      "data" =>
      [
        "accounts" => $this->connected(),
      ],
    ];
  }
}
