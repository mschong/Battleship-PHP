<?php

$url = " new?strategy=Smart&ships=Aircraft+carrier,1,6,false;Battleship,7,5,true;Frigate,2,1,false;Submarine,9,6,false;Minesweeper,10,9,false";
 
$input = explode("?",$url);

$strategy = explode("&",$input[1]);

if($strategy[0] == null){
	$strategyNotSpecified = array("response" => false, "reason"=> "Strategy not specified");
	exit(json_encode($strategyNotSpecified));
}

$typeStrategy = explode("=",$strategy[0]);

if($typeStrategy[1] != "Smart" && $typeStrategy[1] != "Sweep" && $typeStrategy[1] != "Random"){
	$unknownStrategy = array("response" => false, "reason" => "Unknown strategy");
	exit(json_encode($unknownStrategy));
}

//echo $typeStrategy[1];

$ships = explode("=",$strategy[1]);

$ship = explode(";",$ships[1]);


list($name, $x, $y, $direction) = explode(",", $ship[0]);

if($name != "Aircraft+carrier" && $name != "Battleship" && $name != "Frigate" && $name != "Submarine" && $name != "Minesweeper"){
	$uknownName = array("response" => false, "reason" => "Unknown ship name");
	exit(json_encode($uknownName));
}

if ($x > 10 || $x < 1 || $y > 10 || $y < 1){
	$invalidPos = array("response" => false, "reason" => "Invalid ship position");
	exit(json_encode($invalidPos));
}

if($direction != "true" && $direction != "false"){
	$invalidDir = array("response" => false, "reason" => "Invalid ship direction");
	exit(json_encode($invalidDir));
}

$p1 = new shipInfo();
$p2 = new shipInfo();
$p3 = new shipInfo();
$p4 = new shipInfo();
$p5 = new shipInfo();
$i = 0;


foreach ($ship as $individualShip){
	list($name, $x, $y, $direction) = explode(",", $individualShip);
	switch ($i){
		case 0:
			$p1->name = $name;
			$p1->x = $x;
			$p1->y = $y;
			$p1->dir = $direction;
			$i++;
			break;
		case 1:
			$p2->name = $name;
			$p2->x = $x;
			$p2->y = $y;
			$p2->dir = $direction;
			$i++;
			break;
		case 2:
			$p3->name = $name;
			$p3->x = $x;
			$p3->y = $y;
			$p3->dir = $direction;
			$i++;
			break;
		case 3:
			$p4->name = $name;
			$p4->x = $x;
			$p4->y = $y;
			$p4->dir = $direction;
			$i++;
			break;
		case 4:
			$p5->name = $name;
			$p5->x = $x;
			$p5->y = $y;
			$p5->dir = $direction;
			break;
	}
}


class shipInfo{
	public $name;
	public $x;
	public $y;
	public $dir;
}


//********************************************************* NO SE SI FUNCIONE 

class Ship {  //Ship object. One for every ship, 5 per board per game.

	public $vertical;	//if true, ship is vertical
	public $name;		//Aircraft carrier, etc.
	public $x;			//x coordinate
	public $y;			//y coordinate
	public $sunk;		//if false, ship is alive :D
	public $size;


	public function __construct($name, $x, $y, $vertical, $size) {
		$this->name = $name;
		$this->x = $x;
		$this->y = $y;
		$this->vertical = $vertical;
		$sunk = false;
		$this ->size = $size;
	}

}

function createShipsNC($size_board){

	$ships = array (
			"Aircraft carrier"
			=> 5,
			"Battleship"
			=> 4,
			"Frigate" => 3,
			"Submarine"
			=> 3,
			"Minesweeper"
			=> 2
	);


	$game_ships = array();
	$board = generateBoard($size_board);
	foreach ($ships as $name -> $size){
		generatePlacement($board, $name);




	}



}






function createBoard($size){

	$board = array(array());


	for($i = 0; $i <= $size; $i++)
		for($j = 0; $j <= $size; $j++){

			$board[$i][$j] = 0;
				
				
	}


	return $board;

}

function generatePlacement(&$board, &$ship){

	$size;

	if($ship->$name == "Battleship")
		$size = 4;
		else if($ship->$name == "Aircraft+carrier")
			$size = 5;

			else if($ship->$name == "Frigate" || $ship->$name == "Submarine")
				$size = 3;

				else
					$size = 2;

						


					$rx;
					$ry;

					if($ship->vertical == true){
						$rx = rand(1,10);
						$ry = rand(1,(10-$size));
					}

					else{
						$rx = rand(1,(10-$size));
						$ry = rand(1,10);


					}

					while(!check($board, $size, $rx, $ry, $vertical)){

						if($ship->vertical == true){
							$rx = rand(1,10);
							$ry = rand(1,(10-$size));
						}

						else{
							$rx = rand(1,(10-$size));
							$ry = rand(1,10);


						}

					}


					if($vertical)
						for($i = 0; $i < $size; $i++)
							$board[$rx][$ry + $i] =  $ship->$name;
							else

								for($i = 0; $i < $size; $i++)
									$board[$rx + $i][$ry] = $ship->$name;


}

function  check($board, $size, $rx, $ry, $vertical){

	if($vertical){

		for($i = 0; $i < $size; $i++){
			if($board[$rx][$ry + $i])
				return false;
		}

		return true;

	}
	else{

		for($i = 0; $i < $size; $i++){
			if($board[$rx + $i][$ry])
				return false;
		}

		return true;



	}

}






?>