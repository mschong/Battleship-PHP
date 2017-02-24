<?php
include 'board.php';
$pid = $_GET ['pid'];
$shot = $_GET ['shot'];
// $pid = "148772130011158acd3541b28f";
// $shot = "6,8";
$coord = explode ( ",", $shot );
$coord [0] = intval ( $coord [0] );
$coord [1] = intval ( $coord [1] );
$gameInfo = fopen ( "../new/$pid", "r" );
$board = createBoard ( 10 );
$ships = array ();
$countSunk = 0;


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
	$ships [$i] = createShip ( $explodedLine [1], $explodedLine [2], $explodedLine [4], $explodedLine [3], $explodedLine [0] );
	$board = fillBoard ( $board, $ships [$i] );
	$i ++;
}

$AIboard = createBoard ( 10 );

$AIShips = array();
$AIShipsNames = array("Aircraft carrier","Battleship","Frigate","Submarine","Minesweeper");

$shipSize = array (
		"Aircraft carrier" => 5,
		"Battleship" => 4,
		"Frigate" => 3,
		"Submarine" => 3,
		"Minesweeper" => 2
);
for($i = 0; $i < 5; $i ++){
	$name = $AIShipsNames[$i];
	$rx;
	$ry;
	$rd;
	
	do{
		$rx = rand(1,10);
		$ry = rand(1,10);
		$rd = rand(0,1);

	}while(!isAvailable($AIboard, $shipSize[$name], $rx, $ry, $rd));

	$AIShips[$i] = createShip($rx, $ry, $shipSize[$name], $rd, $name);
	$AIboard = fillBoard($AIboard, $AIShips[$i]);
}

hit($coord[0],$coord[1],$AIboard, "ack_shot");
hit(rand(1,10),rand(1,10),$board, "shot");

function isWin(){
	if($countSunk == 5){
		return true;
	}
	return false;
}

function hit($x,$y,$boardToHit,$shotType){
	$hitResponse = hitBoard ( $boardToHit, $x, $y );
	$hit;
// 	if($shotType == "ack_shot")
// 		$hit["response"] = true;
	
	if ($hitResponse == 10) {
		$invalidShot = array (
				"response" => false,
				"reason" => "Invalid shot position"
		);
		exit ( json_encode ( $invalidShot ) );
	} else{
		$ship = $hitResponse;
		$hit = array("response" => true, "ack_shot" => array("x" => $x, "y" => $y, "isHit" => isHit($AIboard, $ship, $x, $y), "isSunk" => isSunk($AIboard, $ship, $x, $y), "isWin" => isWin()), "shot" => array("x" => rand(1,10), "y" => rand(1,10), "isHit" => isHit($board, $ship, rand(1,10), rand(1,10)), "isSunk" => isSunk($board, $ship, rand(1,10), rand(1,10)), "isWin" => isWin()));
	
// 	} else {
// 		$ship = $hitResponse;
// 		if ($ship == null) {
// 			$hit["$shotType"] = 
// // 			array(
// // // 					"response" => true, 
// // 					"$shotType" => 
// 					array (
// 							"x" => $x,
// 							"y" => $y,
// 							"isHit" => false,
// 							"isSunk" => false,
// 							"isWin" => false,
// 							"ship" => array ()
// // 					)
// 			);
// 		} else if ($ship->sunk) {
// 			$coordArray = array ();
// 			$countSunk ++;
	
// 			if ($ship->horizontal) {
// 				for($i = 0; $i < 2 * $ship->size; $i ++) {
// 					$coordArray [$i] = $ship->x + $i;
// 					$coordArray [++ $i] = $ship->y;
// 				}
// 			} else {
// 				for($i = 0; $i < 2 * $ship->size; $i ++) {
// 					$coordArray [$i] = $ship->x;
// 					$coordArray [++ $i] = $ship->y + $i;
// 				}
// 			}
// 			if ($countSunk == 5) {
// 				$hit["$shotType"] = 
// // 				array (
// // // 						"response" => true, 
// // 						"$shotType" => 
// 						array(
// 								"x" => $x,
// 								"y" => $y,
// 								"isHit" => true,
// 								"isSunk" => true,
// 								"isWin" => true,
// 								"ship" => $coordArray
// // 						)
						
// 				);
// 			} else {
// 				$hit["$shotType"]= 
// // 				array (
// // // 						"response" => true,
// // 						"$shotType" => 
// 						array(
// 								"x" => $x,
// 								"y" => $y,
// 								"isHit" => true,
// 								"isSunk" => true,
// 								"isWin" => false,
// 								"ship" => $coordArray
// // 						)
						
// 				);
// 			}
// 		}
	
// 		else {
// 			$hit["$shotType"]= 
// // 			array (
// // // 					"response" => true,
// // 					"$shotType" => 
// 					array(
// 							"x" => $x,
// 							"y" => $y,
// 							"isHit" => true,
// 							"isSunk" => false,
// 							"isWin" => false,
// 							"ship" => array ()
// // 					)
					
// 			);
// 		}
// 	}
// 	echo ( json_encode ( $hit ) );
}




// print_r($hit);
function isAvailable($board, $size, $rx, $ry, $horizontal) {
	// This method will return false if the cell is already occupied.
	if (! $horizontal) {

		for($i = 0; $i < $size; $i ++) {
			if($ry + $i >= count($board))//board variable incomplete
				return false;
			if ($board [$rx] [$ry + $i]->ship != null)
				return false;
		}

		return true;
	} else {

		for($i = 0; $i < $size; $i ++) {
			if($rx + $i >= count($board))
				return false;
			if ($board [$rx + $i] [$ry]->ship != null)
				return false;
		}

		return true;
	}
}


// exit ( json_encode ( $hit ) );

?>
