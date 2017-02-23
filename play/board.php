<?php

$board;

function createBoard($size){
	$board = array(array());
	for ($i = 0; $i < $size; $i++) {
		for ($e = 0; $e < $size; $e++) {
			board[$i][$e] = 0;
		}
	}
}

function fillBoard($x,$y,$size,$direction,$name){
	
	$letter = substr($name, 0);
	
	for ($i = 0; $i < $size; $i++) {
		if ($direction == true){
			$board[$x][$y+$i] = $letter;
		}
		else{
			$board[$x+$i][$y] = $letter;
		}
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

function printBoard(){
	for ($i = 0;$i<count($board);$i++){
		for ($j = 0;$j<count($board[0]);$j++){
			echo $board[$i][$j];
		}
		echo "\n";
	}
}

?>
