<?php

class tokens extends api
{
  public function connected()
  {
    $accounts = db::Query("SELECT *
      FROM personal.tokens
      WHERE uid=$1", [db::UID()]);

    foreach ($accounts as $account)
    {
      $ret = $account->__2array();
      $ret['token_data'] = json_decode($ret['token_data'], true);

      yield new phpa2o\phpa2o($ret);
    }
  }

  public function itemize()
  {
  }
}
