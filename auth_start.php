<?php

$r = explode("\n",shell_exec("ruby oauth.rb"));

file_put_contents(dirname(__FILE__).'/tokens/'.rawurlencode($r[0]), $r[1]);
header('Location: '.$r[2],true,303);

?>
