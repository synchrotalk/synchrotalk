<?php

class popup extends api
{
  protected function Reserve($url, $params = [])
  {
    return
    [
      "design" => "utils/popup/cockpit",
      "data" =>
      [
        "url" => $url,
        "context" => $params,
      ],
    ];
  }
}
