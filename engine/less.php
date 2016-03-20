<?php
$options = array( 'compress'=>true );
$parser = new Less_Parser();
header("Content-Type: text/css");
foreach(glob('./less'.'/*.less*') as $file) {
    $parser->parseFile( $file, '' );
}
$css = $parser->getCss();
echo $css;
?>
