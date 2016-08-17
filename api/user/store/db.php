<?php

class db extends api
{
  public static function __callStatic( $name, $args )
  {
    $db = self::GetDB();
    return call_user_func_array(array($db, $name), $args);
  }

  public static function GetDB()
  {
    return phoxy::Load('user/store')->GetDB();
  }

  public static function UID()
  {
    $uid = phoxy::Load('user/store')->MyUID();
    phoxy_protected_assert($uid, "Please log in to continue");
    return $uid;
  }
}
