<?php
include 'Board.php';
$pid = $_GET ['pid'];
$shot = $_GET ['shot'];
$coord = explode ( ",", $shot );
$gameInfo = fopen("../new/$pid", "r");
$createBoard(10);

// Pid not specified
if ($pid == null) {
	$noPid = array (
			"response" => false,
			"reason" => "Pid not specified" 
	);
	exit ( json_encode ( $noPid ) );
}

// Unknown pid
if (! $gameInfo ) {
	$unknownPid = array (
			"response" => false,
			"reason" => "Unknown pid" 
	);
	exit ( json_encode ( $unknownPid ) );
}

while (($line = fgets($gameInfo)) != false){
      $explodedLine = explode(",", $line);
      fillBoard($explodedLine[1], $explodedLine[2], $explodedLine[4],$explodedLine[3],$explodedLine[0]);
}

printBoard();

// Shot not specified
if ($shot == null) {
	$noShot = array (
			"response" => false,
			"reason" => "Shot not specified" 
	);
	exit ( json_encode ( $noShot ) );
}

// Shot not well-formed
if (count ( $coord ) != 2) {
	$shotNotWellFormed = array (
			"response" => false,
			"reason" => "Shot not well-formed" 
	);
	exit ( json_encode ( $shotNotWellFormed ) );
}

// Invalid shot position
if ($coord [0] < 1 || $coord [0] > 10 || $coord [1] < 1 || $coord [1] > 10) {
	$invalidShot = array (
			"response" => false,
			"reason" => "Invalid shot position" 
	);
	exit ( json_encode ( $invalidShot ) );
}

?>
