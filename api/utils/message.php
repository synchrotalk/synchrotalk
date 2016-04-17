<?php

class message extends api
{
  protected function Reserve($css, $text)
  {
    unset($this->addons['result']);

    return
    [
      "result" => "message_container",
      "design" => "utils/message",
      "data" =>
      [
        "css" => $css,
        'text' => $text
      ],
    ];
  }
}
