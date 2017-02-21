<?php
// $url = " new?strategy=Smart&ships=Aircraft+carrier,1,6,false;Battleship,7,5,true;Frigate,2,1,false;Submarine,9,6,false;Minesweeper,10,9,false";
$url = " new?strategy=Smart&";

$input = explode ( "?", $url );

$strategy = explode ( "&", $input [1] );

if ($strategy [0] == null) {
	$strategyNotSpecified = array (
			"response" => false,
			"reason" => "Strategy not specified" 
	);
	exit ( json_encode ( $strategyNotSpecified ) );
}

if ($strategy [1] == null) {
	echo "Hola\n";
	prettyPrint ( createShipsNC ( 10 ) );
}

$typeStrategy = explode ( "=", $strategy [0] );

if ($typeStrategy [1] != "Smart" && $typeStrategy [1] != "Sweep" && $typeStrategy [1] != "Random") {
	$unknownStrategy = array (
			"response" => false,
			"reason" => "Unknown strategy" 
	);
	exit ( json_encode ( $unknownStrategy ) );
}

// echo $typeStrategy[1];

$ships = explode ( "=", $strategy [1] );

$ship = explode ( ";", $ships [1] );

list ( $name, $x, $y, $direction ) = explode ( ",", $ship [0] );

if ($name != "Aircraft+carrier" && $name != "Battleship" && $name != "Frigate" && $name != "Submarine" && $name != "Minesweeper") {
	$uknownName = array (
			"response" => false,
			"reason" => "Unknown ship name" 
	);
	exit ( json_encode ( $uknownName ) );
}

if($direction == true){
	
	
	
}
if ($x > 10 || $x < 1 || $y > 10 || $y < 1) {
	$invalidPos = array (
			"response" => false,
			"reason" => "Invalid ship position" 
	);
	exit ( json_encode ( $invalidPos ) );
}

if ($direction != "true" && $direction != "false") {
	$invalidDir = array (
			"response" => false,
			"reason" => "Invalid ship direction" 
	);
	exit ( json_encode ( $invalidDir ) );
}

if (count ( $ship ) != 5) {
	$incompleteShips = array (
			"response" => false,
			"reason" => "Incomplete ship deployments" 
	);
	exit ( json_encode ( $incompleteShips ) );
}

$shipsArray = array ();
$i = 0;
foreach ( $ship as $individualShip ) {
	list ( $name, $x, $y, $direction ) = explode ( ",", $individualShip );
	$shipsArray [$i] = new shipInfo ();
	$shipsArray [$i]->name = $name;
	$shipsArray [$i]->x = $x;
	$shipsArray [$i]->y = $y;
	$shipsArray [$i]->dir = $direction;
	$i ++;
}
// echo $shipsArray [0]->name;
class shipInfo {
	public $name;
	public $x;
	public $y;
	public $dir;
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