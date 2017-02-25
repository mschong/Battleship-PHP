<?php
include 'board.php';
// $pid = $_GET ['pid'];
// $shot = $_GET ['shot'];
$pid = "148798815352358b0e5b97fb44";
$shot = "3,10";
$coord = explode ( ",", $shot );
$coord [0] = intval ( $coord [0] );
$coord [1] = intval ( $coord [1] );
$gameInfo = fopen ( "../new/$pid", "r+" );
$board = createBoard ( 10 );

$ships = array ();
$countSunk = 0;

$AIboard = createBoard ( 10 );
$AIShips = array ();

// Pid not specified
if ($pid == null) {
	$noPid = array (
			"response" => false,
			"reason" => "Pid not specified" 
	);
	exit ( json_encode ( $noPid ) );
}
// Unknown pid
if (! $gameInfo) {
	$unknownPid = array (
			"response" => false,
			"reason" => "Unknown pid" 
	);
	exit ( json_encode ( $unknownPid ) );
}
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

for($i = 0; $i < 5; $i ++) {
	
	$line = fgets ( $gameInfo );
	$explodedLine = explode ( ",", $line );
	if ($explodedLine [3] == '1')
		$explodedLine [3] = true;
	else
		$explodedLine [3] = false;
	$ships [$i] = createShip ( $explodedLine [1], $explodedLine [2], $explodedLine [4], $explodedLine [3], $explodedLine [0], $explodedLine [5] );
	$board = fillBoard ( $board, $ships [$i] );
}
for($i = 0; $i < 5; $i ++) {
	$AILine = fgets ( $gameInfo );
	$explodedLine = explode ( ",", $AILine );
	if ($explodedLine [3] == '1')
		$explodedLine [3] = true;
	else
		$explodedLine [3] = false;
	
	$AIships [$i] = createShip ( $explodedLine [1], $explodedLine [2], $explodedLine [4], $explodedLine [3], $explodedLine [0], $explodedLine [5] );
	$AIboard = fillBoard ( $AIboard, $AIships [$i] );
}
// fwrite($gameInfo, "shupan");

$randomX = rand ( 1, 10 );
$randomY = rand ( 1, 10 );
// $randomX = 2;
// $randomY = 4;
hit ( $coord [0], $coord [1], $randomX, $randomY, $board, $AIboard, $gameInfo );
function isWin() {
	if ($countSunk == 5) {
		return true;
	}
	return false;
}
function hit($x, $y, $randomX, $randomY, $board, $AIboard, $gameInfo) {
	$hitResponse = hitBoard ( $AIboard, $x, $y );
	$hitResponseAI = hitBoard ( $board, $randomX, $randomY );
	
	$hit;
	
	if ($hitResponse == 10) {
		$invalidShot = array (
				"response" => false,
				"reason" => "Invalid shot position" 
		);
		exit ( json_encode ( $invalidShot ) );
	} else {
		$fileArray = file ( "../new/148798815352358b0e5b97fb44" );
		print_r ( $fileArray );
		
		$hit = array (
				"response" => true,
				"ack_shot" => array (
						"x" => $x,
						"y" => $y,
						"isHit" => isHit ( $hitResponse ),
						"isSunk" => isSunk ( $hitResponse ),
						"isWin" => isWin (),
						"ship" => sunkenShipCoordinates ( $hitResponse ) 
				),
				"shot" => array (
						"x" => $randomX,
						"y" => $randomY,
						"isHit" => isHit ( $hitResponseAI ),
						"isSunk" => isSunk ( $hitResponseAI ),
						"isWin" => isWin (),
						"ship" => sunkenShipCoordinates ( $hitResponseAI ) 
				) 
		);
		
		fwrite ( $gameInfo, "shupan" );
		exit ( json_encode ( $hit ) );
	}
}
// print_r($hit);
function isAvailable($board, $size, $rx, $ry, $horizontal) {
	// This method will return false if the cell is already occupied.
	if (! $horizontal) {
		for($i = 0; $i < $size; $i ++) {
			if ($ry + $i >= count ( $board )) // board variable incomplete
				return false;
			if ($board [$rx] [$ry + $i]->ship != null)
				return false;
		}
		return true;
	} else {
		for($i = 0; $i < $size; $i ++) {
			if ($rx + $i >= count ( $board ))
				return false;
			if ($board [$rx + $i] [$ry]->ship != null)
				return false;
		}
		return true;
	}
}
function sunkenShipCoordinates($ship) {
	$coordinates = array ();
	if ($ship->sunk) {
		
		$coorX = $ship->x;
		$coorX = intval ( $coorX );
		$coorY = $ship->y;
		$coorY = intval ( $coorY );
		
		if ($ship->direction) {
			$j = 0;
			for($i = 0; $i < 2 * ($ship->size); $i = $i + 2) {
				$coordinates [$i] = $coorX + $j;
				$coordinates [$i + 1] = $coorY;
				$j ++;
			}
		} else {
			$j = 0;
			for($i = 0; $i < 2 * ($ship->size); $i = $i + 2) {
				$coordinates [$i] = $coorX;
				$coordinates [$i + 1] = $coorY + $j;
				$j ++;
			}
		}
	}
	// print_r($coordinates);
	return $coordinates;
}

// exit ( json_encode ( $hit ) );
?>