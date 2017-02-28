<?php
 $strategy = $_GET ['strategy'];
 $ships = $_GET ['ships'];
//$strategy = "Smart";
//$ships = "Aircraft carrier,1,1,true;Battleship,2,2,false;Frigate,5,7,true;Submarine,1,10,true;Minesweeper,10,1,false";

$board = createBoard ( 10 );

$AIs = createRandShips ();
$AIboard = randomPlacement ( 10, $AIs );

// Check if ship info is provided
if ($ships == null) {
	$s = createRandShips ();
	$board = randomPlacement ( 10, $s );
	$success = array (
			"response" => true,
			"pid" => uniqid ( round ( microtime ( true ) * 1000 ) ) 
	);
	exit ( json_encode ( $success ) );
}

// Strategy not specified
if ($strategy == null) {
	$strategyNotSpecified = array (
			"response" => false,
			"reason" => "Strategy not specified" 
	);
	exit ( json_encode ( $strategyNotSpecified ) );
}

// Unknown strategy
if ($strategy != "Smart" && $strategy != "Sweep" && $strategy != "Random") {
	$unknownStrategy = array (
			"response" => false,
			"reason" => "Unknown strategy" 
	);
	exit ( json_encode ( $unknownStrategy ) );
}

// $ships = explode ( "=", $strategy [1] );

$ship = explode ( ";", $ships );
$shipSize = array (
		"Aircraft carrier" => 5,
		"Battleship" => 4,
		"Frigate" => 3,
		"Submarine" => 3,
		"Minesweeper" => 2 
);
for($i = 0; $i < count ( $ship ); $i ++) {
	list ( $name, $x, $y, $direction ) = explode ( ",", $ship [$i] );
	
	if ($direction == "true")
		$direction = true;
	
	else if ($direction == "false")
		$direction = false;
	
	else {
		$invalidDir = array (
				"response" => false,
				"reason" => "Invalid ship direction" 
		);
		exit ( json_encode ( $invalidDir ) );
	}
	
	// Unknown ship name
	if ($name != "Aircraft carrier" && $name != "Battleship" && $name != "Frigate" && $name != "Submarine" && $name != "Minesweeper") {
		$uknownName = array (
				"response" => false,
				"reason" => "Unknown ship name" 
		);
		exit ( json_encode ( $uknownName ) );
	}
	
	// Invalid ship position
	if ($direction == true) {
		if ($x + $shipSize [$name] - 1 > 10 || $x < 1 || $y > 10 || $y < 1) {
			$invalidPos = array (
					"response" => false,
					"reason" => "Invalid ship position" 
			);
			exit ( json_encode ( $invalidPos ) );
		}
	} else {
		if ($x > 10 || $x < 1 || $y + $shipSize [$name] - 1 > 10 || $y < 1) {
			$invalidPos = array (
					"response" => false,
					"reason" => "Invalid ship position" 
			);
			$sum = $y + $shipSize [$name] - 1;
			
			exit ( json_encode ( $invalidPos ) );
		}
	}
}

// Place ships in board
// Error: Conflicting ship deployments
$shipsArray = array ();
$i = 0;
foreach ( $ship as $individualShip ) {
	list ( $name, $x, $y, $direction ) = explode ( ",", $individualShip );
	$direction = $direction === 'true';
	$shipsArray [$i] = new Ship ( $name, $x, $y, $direction, $shipSize [$name] );
	if (! placeInBoard ( $board, $shipsArray [$i] )) {
		$conflicting = array (
				"response" => false,
				"reason" => "Conflicting ship deployments" 
		);
		exit ( json_encode ( $conflicting ) );
	}
	$i ++;
}
// Incomplete ship deployments
if (count ( $ship ) != 5) {
	$incompleteShips = array (
			"response" => false,
			"reason" => "Incomplete ship deployments" 
	);
	exit ( json_encode ( $incompleteShips ) );
}

for($i = 0; $i < count ( $ship ); $i ++) {
	for($j = $i + 1; $j < count ( $ship ); $j ++) {
		if ($shipsArray [$i]->name == $shipsArray [$j]->name) {
			$incompleteShips = array (
					"response" => false,
					"reason" => "Incomplete ship deployments" 
			);
			exit ( json_encode ( $incompleteShips ) );
		}
	}
}

$pid = uniqid ( round ( microtime ( true ) * 1000 ) );
$success = array (
		"response" => true,
		"pid" => $pid 
);

$file = fopen ( "$pid", "w" );
$AIfile = fopen ( "$pid.AI", "w" );
echo json_encode ( $file );


foreach ( $shipsArray as $s ) {
	fwrite ( $file, "$s->name,$s->x,$s->y,$s->horizontal,$s->size,0" );
	fwrite ( $file, PHP_EOL );
}

foreach ( $AIs as $AIship ) {
	fwrite ( $AIfile, "$AIship->name,$AIship->x,$AIship->y,$AIship->horizontal,$AIship->size,0" );
	fwrite ( $AIfile, PHP_EOL );
}

exit ( json_encode ( $success ) );
function prettyPrint($board) {
	for($i = 1; $i <= 10; $i ++) {
		for($j = 1; $j <= 10; $j ++)
			echo $board [$i] [$j];
		echo "\n";
	}
}
function placeInBoard(&$board, $ship) {
	if (! isAvailable ( $board, $ship->size, $ship->x, $ship->y, $ship->horizontal )) {
		return false;
	}
	
	if (! $ship->horizontal) {
		for($i = 0; $i < $ship->size; $i ++)
			$board [$ship->x] [$ship->y + $i] = $ship->name;
	} else {
		for($i = 0; $i < $ship->size; $i ++)
			$board [$ship->x + $i] [$ship->y] = $ship->name;
	}
	
	return true;
}
class Ship { // Ship object. One for every ship, 5 per board per game.
	public $horizontal; // if false, ship is vertical
	public $name; // Aircraft carrier, etc.
	public $x; // x coordinate
	public $y; // y coordinate
	public $sunk; // if false, ship is alive :D
	public $size;
	public function __construct($name, $x, $y, $horizontal, $size) {
		$this->name = $name;
		$this->x = $x;
		$this->y = $y;
		$this->horizontal = $horizontal;
		$sunk = 0;
		$this->size = $size;
	}
}
function randomPlacement($boardSize, &$ships) {
	$board = createBoard ( $boardSize );
	
	foreach ( $ships as $individualShip )
		placeRandomly ( $board, $individualShip );
	
	return $board;
}
function createRandShips() {
	$shipNames = array (
			
			"Aircraft carrier",
			"Battleship",
			"Frigate",
			"Submarine",
			"Minesweeper" 
	);
	
	$shipSize = array (
			5,
			4,
			3,
			3,
			2 
	);
	
	$ships = array ();
	
	for($i = 0; $i <= 4; $i ++) {
		$ships [$i] = new Ship ( $shipNames [$i], 0, 0, true, $shipSize [$i] );
	}
	
	return $ships;
}
function createBoard($boardSize) {
	$board = array (
			array () 
	);
	
	for($i = 0; $i <= $boardSize; $i ++)
		for($j = 0; $j <= $boardSize; $j ++) {
			
			$board [$i] [$j] = 0;
		}
	
	return $board;
}
function placeRandomly(&$board, &$ship) {
	$randomXcoordinate;
	$randomYcoordinate;
	
	
	
	$randomDirection = rand ( 0, 1 );
	$ship->horizontal = $randomDirection;
	
	if ($ship->vertical == true) {
		$randomXcoordinate = rand ( 1, 10 );
		$randomYcoordinate = rand ( 1, (10 - $ship->size) );
	} else {
		$randomXcoordinate = rand ( 1, (10 - $ship->size) );
		$randomYcoordinate = rand ( 1, 10 );
	}
	
	while ( ! isAvailable ( $board, $ship->size, $randomXcoordinate, $randomYcoordinate, $ship->horizontal ) ) {
		
		if ($ship->horizontal == false) {
			$randomXcoordinate = rand ( 1, 10 );
			$randomYcoordinate = rand ( 1, (10 - $ship->size) );
		} else {
			$randomXcoordinate = rand ( 1, (10 - $ship->size) );
			$randomYcoordinate = rand ( 1, 10 );
		}
	}
	$ship->x = $randomXcoordinate;
	$ship->y = $randomYcoordinate;
	
	placeInBoard ( $board, $ship );
}
function isAvailable($board, $size, $rx, $ry, $horizontal) {
	if ($horizontal) {
		if ($rx + $size-1 > 10)
			return false;
	} else {
		if ($ry + $size-1 > 10)
			return false;
	}
	// This method will return false if the cell is already occupied.
	if (! $horizontal) {
		
		for($i = 0; $i < $size; $i ++) {
			if ($board [$rx] [$ry + $i])
				return false;
		}
		
		return true;
	} else {
		
		for($i = 0; $i < $size; $i ++) {
			if ($board [$rx + $i] [$ry])
				return false;
		}
		
		return true;
	}
}

?>
