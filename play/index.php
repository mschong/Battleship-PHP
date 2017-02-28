<?php
include 'board.php';
include 'Smart.php';
// $pid = $_GET ['pid'];
// $shot = $_GET ['shot'];
$pid = "148825330647458b4f17a73acf";
$shot = "4,7";
$coord = explode ( ",", $shot );
$coord [0] = intval ( $coord [0] );
$coord [1] = intval ( $coord [1] );
$gameInfo = fopen ( "../new/$pid", "r+" );
$AIinfo = fopen ( "../new/$pid.AI", "r+" );
$board = createBoard ( 10 );
$AIboard = createBoard ( 10 );
$ships = array ();
$countSunk = 0; // asi nunca va a ganar nadie
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
$i = 0;
while ( ($line = fgets ( $gameInfo )) != false ) {
	
	$explodedLine = explode ( ",", $line );
	if ($explodedLine [3] == '1')
		$explodedLine [3] = true;
	else
		$explodedLine [3] = false;
	$ships [$i] = createShip ( $explodedLine [1], $explodedLine [2], $explodedLine [4], $explodedLine [3], $explodedLine [0], $explodedLine [5] );
	
	$board = fillBoard ( $board, $ships [$i] );
	$i ++;
}
$i = 0;
while ( ($AILine = fgets ( $AIinfo )) != false ) {
	$explodedLine = explode ( ",", $AILine );
	
	if ($explodedLine [3] == '1')
		$explodedLine [3] = true;
	else
		$explodedLine [3] = false;
	
	$AIships [$i] = createShip ( $explodedLine [1], $explodedLine [2], $explodedLine [4], $explodedLine [3], $explodedLine [0], $explodedLine [5] );
	$AIboard = fillBoard ( $AIboard, $AIships [$i] );
	$i ++;
}
AiX;
$AiY;
// $randomX = 2;
// $randomY = 4;
$strategy = "Smart";
$shot;
if ($strategy == "Smart") {
 	$shot = smart ( $pid );
 	$AiX = $shot [0];
 	$AiY = $shot [1];
 } else if ($strategy == "Random") {
 	$shot = randomCoordinates ( $pid );
 	$AiX = $shot [0];
 	$AiY = $shot [1];
 }
hit ( $coord [0], $coord [1], $AiX, $AiY, $board, $AIboard, $gameInfo, $AIinfo, $pid );
function isWin() {
	if ($countSunk == 5) {
		return true;
	}
	return false;
}
function hit($x, $y, $aiX, $aiY, $board, $AIboard, $gameInfo, $AIinfo, $pid) {
	$hitResponse = hitBoard ( $AIboard, $x, $y );
	$hitResponseAI = hitBoard ( $board, $aiX, $aiY );
	
	$hit;
	global $strategy;
	
	if ($strategy == "Smart") {
		global $prevHitBool;
		global $prevShotXcoord;
		global $prevShotYcoord;
		global $lastHit;
		
		if (isHit ( $hitResponseAI )) {
			
			// If the last shot made by the computer hit, set $prevHitBool[0] and $prevHitBool[1] = true
			// If the last shot made by the computer hit, set $lastHit[0] = the X coordinate, and set $lastHit[1] = the Y coordinate
			
			$prevHitBool [0] = true;
			$prevHitBool [1] = true;
			$lastHit [0] = $aiX;
			$lastHit [1] = $aiY;
		} else {
			$prevHitBool [0] = false;
			// If the last shot made by the computer did not hit, set $prevHitBool[0] = false
		}
		
		if (isSunk ( $hitResponseAI )) {
			// If the last shot sunk a ship, set $prevHitBool[0] and $prevHitBool[1] = false
			$prevHitBool [0] = false;
			$prevHitBool [1] = false;
		}
		
		// Each time a shot is made, set $prevX and $prevY = x and y, respectively
		$prevShotXcoord = $aiX;
		$prevShotYcoord = $aiY;
		
		write ( $pid );
	}
	
	if ($hitResponse == 10) {
		$invalidShot = array (
				"response" => false,
				"reason" => "Invalid shot position" 
		);
		exit ( json_encode ( $invalidShot ) );
	} else {
		
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
						"x" => $aiX,
						"y" => $aiY,
						"isHit" => isHit ( $hitResponseAI ),
						"isSunk" => isSunk ( $hitResponseAI ),
						"isWin" => isWin (),
						"ship" => sunkenShipCoordinates ( $hitResponseAI ) 
				) 
		);
		
		if (isHit ( $hitResponse )) {
			$fileArray = file ( "../new/$pid.AI" );
			// print_r($fileArray);
			file_put_contents ( "../new/$pid.AI", "" );
			for($i = 0; $i < count ( $fileArray ); $i ++) {
				$expl = explode ( ",", $fileArray [$i] );
				if ($expl [0] == $hitResponse->name) {
					$expl [5] = intval ( $expl [5] );
					$expl [5] = $expl [5] + 1;
					$expl [5] = "$expl[5]" . PHP_EOL;
					$fileArray [$i] = implode ( ",", $expl );
					// print_r($fileArray);
				}
				// print_r($fileArray);
				fwrite ( $AIinfo, $fileArray [$i] );
			}
		}
		if (isHit ( $hitResponseAI )) {
			$fileArray = file ( "../new/$pid" );
			// print_r($fileArray);
			file_put_contents ( "../new/$pid", "" );
			for($i = 0; $i < count ( $fileArray ); $i ++) {
				$expl = explode ( ",", $fileArray [$i] );
// 				print_r($expl);
				if ($expl [0] == $hitResponseAI->name) {
					$expl [5] = intval ( $expl [5] );
					$expl [5] = $expl [5] + 1;
					$expl [5] = "$expl[5]" . PHP_EOL;
					$fileArray [$i] = implode ( ",", $expl );
					// print_r($fileArray);
				}
// 				print_r($expl);
				// print_r($fileArray);
				fwrite ( $gameInfo, $fileArray [$i] );
			}
		}
		exit ( json_encode ( $hit ) );
	}
}

function sunkenShipCoordinates($ship) {
	$coordinates = array ();
	if ($ship->sunk) {
		
		$coorX = $ship->x;
		$coorX = intval ( $coorX );
		$coorY = $ship->y;
		$coorY = intval ( $coorY );
		
		if ($ship->horizontal) {
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
?>
