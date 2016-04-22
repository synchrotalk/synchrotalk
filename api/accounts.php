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

  protected function supported()
  {
    return
    [
      "data" =>
      [
        "supported" => db::Query("SELECT * FROM connector_plugins"),
      ],
    ];
  }

  protected function itemize()
  {
    $uid = phoxy::Load('user')->uid();

    $nets = db::Query("SELECT * FROM connections WHERE uid=$1", [$uid]);

    return
    [
      "design" => "accounts/list",
      "data" =>
      [
        "accounts" => $nets,
      ],
    ];
  }
}
