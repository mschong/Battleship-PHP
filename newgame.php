<?php




$ships = createRandShips();
$board = randomPlacement(10, $ships);

for($i = 1; $i <= 10; $i++){
	for($j = 1; $j <= 10; $j++)
		echo $board[$i][$j];

		echo"\n";
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


function randomPlacement($boardSize, &$ships){
	
	
	$board = createBoard($boardSize);
	
	foreach ($ships as $individualShip)
	placeRandomly($board, $individualShip);
	
	return $board;
	
}

function createRandShips() {
	
	echo"Enter";
	
	$shipNames = array (
			
			"Aircraft+carrier",
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
	$ships = array (
	);
	
	for($i = 0; $i <= 4; $i++) {
		$ships[$i] = new Ship ( $shipNames [$i], 0, 0, true, $shipSize [$i] );
		
		
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
	} 

	else {
		$randomXcoordinate = rand ( 1, (10 - $ship->size) );
		$randomYcoordinate = rand ( 1, 10 );
	}
	
	while ( ! isEmpty ( $board, $ship->size, $randomXcoordinate, $randomYcoordinate, $ship->horizontal ) ) {
		
		if ($ship->horizontal == false) {
			$randomXcoordinate = rand ( 1, 10 );
			$randomYcoordinate = rand ( 1, (10 - $$ship ->size) );
		} 

		else {
			$randomXcoordinate = rand ( 1, (10 - $ship->size) );
			$randomYcoordinate = rand ( 1, 10 );
		}
	}
	
	echo $ship->x = $randomXcoordinate;
	echo $ship->y = $randomYcoordinate;
	echo $ship->name;
	
	
	if (! $ship->horizontal)
		for($i = 0; $i < $ship ->size; $i ++)
			$board [$ship->x] [$ship->y + $i] = $ship -> name;
	else
		for($i = 0; $i < $ship->size; $i ++)
			$board [$ship->x + $i] [$ship->y] = $ship ->name;
}

function isEmpty($board, $size, $rx, $ry, $horizontal) {
	
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