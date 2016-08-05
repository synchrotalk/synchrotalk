<?php

class network extends api
{
  public function get_network_object($network)
  {
    $storage_functor = phoxy::Load('user')->StorageShortcut();
    $accounts = &$storage_functor()['accounts'];

    if (!is_array($accounts))
      $accounts = [];

    phoxy_protected_assert(!isset($accounts[$network]), "In demo mode one account per social network");

    $networks = phoxy::Load('networks');

    phoxy_protected_assert($networks->supported($network), "Social network unsupported");

    $obj = $networks->get_network_object($network);

    $this->init_network_object($obj);

    return $obj;
  }

  private function init_network_object($obj, $network)
  {
  }
}
