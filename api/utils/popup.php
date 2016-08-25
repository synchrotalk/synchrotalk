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
        "params" => $params,
      ],
    ];
  }

  protected function Design($design, $context = [], $params = [])
  {
    return
    [
      "design" => "utils/popup/cockpit.design",
      "data" =>
      [
        "design" => $design,
        "context" => $context,
        "params" => $params,
      ],
    ];
  }

  protected function bring_design($design, $context = [])
  {
    return
    [
      "design" => $design,
      "data" => $context,
    ];
  }
}
