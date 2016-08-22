<?php

class network extends api
{
  public function by_account_id($account_id)
  {
    $account = phoxy::Load('accounts/tokens')->info($account_id);
    return $this->by_account_object($account);
  }

  public function by_account_object($account_object)
  {
    $network = $this->get_network_object($account_object->network);
    $network->sign_in($account_object->token_data);
    return $network;
  }

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

    $this->init_network_object($obj, $network);

    return $obj;
  }

  private function init_network_object($obj, $network)
  {
    $settings = conf()->networks->init->$network;

    $obj->init($settings);
  }
}
