<?php

$url = " new?strategy=Smart&ships=Aircraft+carrier,1,6,false;Battleship,7,5,true;Frigate,2,1,false;Submarine,9,6,false;Minesweeper,10,9,false";
   
$input = explode("?",$url);

$strategy = explode("&",$input[1]);

$typeStrategy = explode("=",$strategy[0]);

if($typeStrategy[1] != "Smart" && $typeStrategy[1] != "Sweep" && $typeStrategy[1] != "Random"){
	echo "La cagaste";
}

//echo $typeStrategy[1];

$ship = explode("=",$strategy[1]);

$shippi = explode(";",$ship[1]);

$p1 = new shipInfo();
$p2 = new shipInfo();
$p3 = new shipInfo();
$p4 = new shipInfo();
$p5 = new shipInfo();
$i = 0;


foreach ($shippi as $individualShip){
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


?>