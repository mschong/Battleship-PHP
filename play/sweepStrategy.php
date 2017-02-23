<?php

function sweep($board){
	
	for ($x = 0;$x<count($board);$x++){
		for ($y = 0;$y<count($board[0]);$y++){
			$coor = array($x,$y);
			yield $coor;
		}
	}
	
	
}

?>