<?php

$url = " play/?pid=55ed3eb95f5a3&shot=4,5";
$input = explode("=", $url);  //input[1] = 55ed3eb95f5a3&shot , input[2] = 4,5
$divider = explode("&", $input[1]); // divider[0] = 55ed3eb95f5a3
$coordinates = explode(",", $input[2]);

$pid = $divider[0];
$x = $coordinates[0];
$y = $coordinates[1];

?>
