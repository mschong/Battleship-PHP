<?php
include 'board.php';
// $pid = $_GET ['pid'];
// $shot = $_GET ['shot'];
$pid = "148772130011158acd3541b28f";
$shot = "6,8";
$coord = explode ( ",", $shot );
$coord[0] = intval($coord[0]);
$coord[1] = intval($coord[1]);
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
// printBoard ( $board );
$hit;
$hitResponse = hitBoard ( $board, $coord [0], $coord [1] );

if ($hitResponse==10) {
	$invalidShot = array (
			"response" => false,
			"reason" => "Invalid shot position" 
	);
	exit ( json_encode ( $invalidShot ) );
} else {
	$ship = $hitResponse;
	if ($ship == null) {
		$hit = array (
				"response" => true,
				"ack_shot" => array (
						"x" => $coord [0],
						"y" => $coord [1],
						"isHit" => false,
						"isSunk" => false,
						"isWin" => false,
						"ship" => array () 
				) 
		);
	}
	else if($ship->sunk) {
		$coordArray = array ();
		$countSunk ++;
		
		if ($ship->horizontal) {
			for($i = 0; $i < 2 * $ship->size; $i ++) {
				$coordArray [$i] = $ship->x + $i;
				$coordArray [++ $i] = $ship->y;
			}
		} else {
			for($i = 0; $i < 2 * $ship->size; $i ++) {
				$coordArray [$i] = $ship->x;
				$coordArray [++ $i] = $ship->y + $i;
			}
		}
		if ($countSunk == 5) {
			$hit = array (
					"response" => true,
					"ack_shot" => array (
							"x" => $coord [0],
							"y" => $coord [1],
							"isHit" => true,
							"isSunk" => true,
							"isWin" => true,
							"ship" => $coordArray 
					) 
			);
		} else {
			$hit = array (
					"response" => true,
					"ack_shot" => array (
							"x" => $coord [0],
							"y" => $coord [1],
							"isHit" => true,
							"isSunk" => true,
							"isWin" => false,
							"ship" => $coordArray 
					) 
			);
		}
	} 

	else {
		$hit = array (
				"response" => true,
				"ack_shot" => array (
						"x" => $coord [0],
						"y" => $coord [1],
						"isHit" => true,
						"isSunk" => false,
						"isWin" => false,
						"ship" => array () 
				) 
		);
	}
}

exit ( json_encode ( $hit ) );

?>
