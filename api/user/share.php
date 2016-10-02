<?php

class share extends api
{
  protected function export()
  {

  }

  private function create_hash()
  {
    $account = phoxy::Load('user')->uid();

    $hash = db::Query("INSERT INTO
        personal.share(account)
        VALUES ($1)
        RETURNING hash",
      [$account], true);

    return $hash->hash;
  }

  private function import_hash($hash)
  {
    $account = db::Query("SELECT *
      FROM personal.share
      WHERE expired<now()
        AND hash=$1",
      [$hash], true);

    phoxy_protected_assert($account(), "Access link expired");

    phoxy::Load('user')->ResetSession();
    phoxy::Load('user')->login($account->uid);
  }
}
