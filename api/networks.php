<?php

class networks extends api
{
  protected function supported($name = null)
  {
    $plugins = conf()->plugins->available->__2array();

    if (!is_null($name))
      return in_array($name, $plugins);

    return
    [
      "design" => "networks/list",
      "data" =>
      [
        "networks" => array_map(function($plugin)
        {
          return['name' => $plugin];
        }, $plugins),
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
