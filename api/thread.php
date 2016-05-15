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

    $ret = $connection->fetch_messages($id);
    $ret['network'] = $network;
    return $ret;
  }

  protected function Send($network, $id, $text)
  {
    $networks = phoxy::Load('networks');
    $connection = $networks->get_network_object($network);

    return $connection->send_message($id, $text);
  }
}
