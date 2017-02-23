<?php

function createBoard($size){
	$board = array(array());
	for ($i = 1; $i <= $size; $i++) {
		for ($j = 1; $j <= $size; $j++) {
			$board[$i][$j] = 0;
		}
	}
	return $board;
}

function fillBoard($boardToFill, $x,$y,$size,$direction,$name){
	
	$letter = $name[0];
	$intx = intval($x);
	$inty = intval($y);
	for ($i = 0; $i < $size; $i++) {
		if ($direction == true){
			$boardToFill[$intx+$i][$inty] = $letter;
// 			echo $boardToFill[$intx+$i][$inty];
// 			echo"\n";
// 			printBoard($boardToFill);
		}
		else{
			$boardToFill[$intx][$inty+$i] = $letter;
// 			echo $boardToFill[$intx+$i][$inty];
// 			echo"\n";
// 			printBoard($boardToFill);
		}
	}
	return $boardToFill;
}

function printBoard($boardToPrint){
	echo "printing board\n";

	for ($i = 1;$i<=count($boardToPrint);$i++){
		for ($j = 1;$j<=count($boardToPrint);$j++){
			echo $boardToPrint[$i][$j];
			echo " ";
		}
		echo "\n";
	}
}
function visited($x,$y){
	
	if ($board[$x][$y]){
		return true;
	}
	else{
		$board[$x][$y] = 1;
		return false;
	}
}

function isThereShip($x,$y){
	if (is_string($board[$x][$y])){
		return true;
	}
	else{
		return false;
	}
}



?>
