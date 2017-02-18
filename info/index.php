<?php
define ( "BOARDSIZE", 10 );
$strategies = array (
		"Smart",
		"Random",
		"Sweep" 
);

$ship_info = array (
		array (
				"name" => "Aircraft carrier",
				"size" => 5 
		),
		array (
				"name" => "Battleship",
				"size" => 4 
		),
		array (
				"name" => "Frigate",
				"size" => 3 
		),
		array (
				"name" => "Submarine",
				"size" => 3 
		),
		array (
				"name" => "Minesweeper",
				"size" => 2 
		) 
)
;

$info = array (
		"size" => BOARDSIZE,
		"strategies" => $strategies,
		"ships" => $ship_info 
);
echo json_encode ( $info );

?>