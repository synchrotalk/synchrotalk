<?php
  include("vendor/autoload.php");
  $less = new lessc;
  try
  {
    $css = $less->compileFile("less/style.less");
    header("Content-Type: text/css");
    header("Cache-Control: public, max-age=3600");

    echo $css;
  } catch (Exception $e)
  {
    echo $e->getMessage();
  }
?>
