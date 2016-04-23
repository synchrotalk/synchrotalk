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
        "supported" => conf()->plugins->available,
      ],
    ];
  }

  private function get_network_object($name)
  {
    return $this->hub()->$name;
  }

  private $hub;
  private function hub()
  {
    if (isset($this->hub))
      return $this->hub;

    require_once(conf()->plugins->lib_path.'/rpc/hub.php');

    $paths = [];
    foreach (conf()->plugins->available as $plugin)
    {
      $path = conf()->plugins->root_path;
      $path .= "/{$plugin}.plugin/";
      $paths[] = $path;
    }

    return $this->hub = new hub($paths);
  }
}
