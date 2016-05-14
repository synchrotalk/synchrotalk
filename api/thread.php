<?php

class thread extends api
{
  protected function Reserve($network, $id)
  {
    return
    [
      "design" => "thread/index",
      "data" => $this->Read($network, $id),
    ];
  }

  protected function Read($network, $id)
  {
    $networks = phoxy::Load('networks');
    $connection = $networks->get_network_object($network);

    return $connection->fetch_messages($id);
  }
}
