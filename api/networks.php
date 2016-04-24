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
        "networks" => array_map(function($plugin)
        {
          return['name' => $plugin];
        }, conf()->plugins->available->__2array()),
      ],
    ];
  }

  protected function icon($name)
  {
    return
    [
      "design" => "networks/icon",
      "data" =>
      [
        "icon" => conf()->networks->icons->$name,
      ],
    ];
  }

  public function __get($name)
  {
    return $this->get_network_object($name);
  }

  public function get_network_object($name)
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
