<?php
$countSunk;
function createBoard($size) {
	$board = array (
			array () 
	);
	for($i = 1; $i <= $size; $i ++) {
		for($j = 1; $j <= $size; $j ++) {
			$board [$i] [$j] = new Place ( 0, null );
		}
	}
	return $board;
}
function fillBoard($boardToFill, $ship) {
	$intx = intval ( $ship->x );
	$inty = intval ( $ship->y );
	for($i = 0; $i < $ship->size; $i ++) {
		if ($ship->horizontal == true) {
			$boardToFill [$intx + $i] [$inty]->ship = $ship;
		} else {
			$boardToFill [$intx] [$inty + $i]->ship = $ship;
		}
	}
	// printBoard($boardToFill);
	
	return $boardToFill;
}
function printBoard($boardToPrint) {
	echo "printing board\n";
	
	for($i = 1; $i <= count ( $boardToPrint ); $i ++) {
		for($j = 1; $j <= count ( $boardToPrint ); $j ++) {
			echo $boardToPrint [$i] [$j]->ship->name;
			echo " ";
		}
		echo "\n";
	}
}
function visited($x, $y) {
	if ($board [$x] [$y]) {
		return true;
	} else {
		$board [$x] [$y] = 1;
		return false;
	}
}
function isThereShip($x, $y) {
	if (is_string ( $board [$x] [$y] )) {
		return true;
	} else {
		return false;
	}
}
function hitBoard($board, $x, $y) {
	$place = $board [$x] [$y];
	
	if ($place->isHit) {
		return 10;
	}
	$place->isHit = true;
	
	if ($place->ship != null) {
		$place->ship->countHits = $place->ship->countHits + 1;
		if ($place->ship->countHits == $place->ship->size) {
			$place->ship->sunk = true;
		}
	}
	
	return $place->ship;
}
function isHit($ship) {
	if ($ship != null) {
		return true;
	}
	return false;
}
function isSunk($ship) {
	if ($ship == null)
		return false;
	if ($ship->sunk == null)
		return false;
	return $ship->sunk;
}
function createShip($x, $y, $size, $direction, $name, $hits) {
	$ship = new Ship ( $name, $x, $y, $direction, $size, $hits );
	return $ship;
}
class Ship { // Ship object. One for every ship, 5 per board per game.
	public $horizontal; // if false, ship is vertical
	public $name; // Aircraft carrier, etc.
	public $x; // x coordinate
	public $y; // y coordinate
	public $sunk; // if false, ship is alive :D
	public $size;
	public $countHits;
	public function __construct($name, $x, $y, $horizontal, $size, $hits) {
		$this->name = $name;
		$this->x = $x;
		$this->y = $y;
		$this->horizontal = $horizontal;
		$sunk = 0;
		$this->size = $size;
		$this->countHits = $hits;
	}
}
class Place {
	public $isHit;
	public $ship;
	public function __construct($isHit, $ship) {
		$this->isHit = $isHit;
		$this->ship = $ship;
	}
}

?>
