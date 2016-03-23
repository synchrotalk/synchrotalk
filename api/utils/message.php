<?php

class message extends api
{
  protected function Reserve($data)
  {
    unset($this->addons['result']);
    return
    [
      "result" => "message_container",
      "design" => "utils/message",
      "data" => ["css" => $data[0], 'text' => $data[1]],
    ];
  }
}
