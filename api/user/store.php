<?php

class store extends api
{
  public function MyUID()
  {
    return phoxy::Load("user")->uid();
  }

  private $db;

  public function GetDB()
  {
    if (isset($db))
      return $db;

    $lib_dir = "vendor/enelar/phpsql";
    $conf = conf()->db->connection_string;

    require_once("{$lib_dir}/phpsql.php");
    require_once("{$lib_dir}/wrapper.php");

    list($scheme) = explode(":", $conf);
    require_once("{$lib_dir}/{$scheme}.php");

    $phpsql = new \phpsql\phpsql();
    $connection = $phpsql->Connect($conf);

    $this->db = new \phpsql\utils\wrapper($connection);

    return $this->db;
  }

  public function Register()
  {
    return db::Query("INSERT INTO personal.users DEFAULT VALUES RETURNING uid", [], true)->uid;
  }
}
