<?php

class tokens extends api
{
  public function connected()
  {
    $accounts = db::Query("SELECT *
      FROM personal.tokens
      WHERE uid=$1", [db::UID()]);

    return $accounts;
  }

  public function itemize()
  {
  }
}
