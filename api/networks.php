<?php

class networks extends api
{
  protected function supported()
  {
    return
    [
      "design" => "networks/list",
      "data" =>
      [
        "supported" => db::Query("SELECT * FROM plugins"),
      ],
    ];
  }
}
