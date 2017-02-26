<?php
//Variables for smart Strategy
$prevHitBool = array ();
$prevShotXcoord;
$prevShotYcoord;
$shotsTaken = array ();
$lastHit = array ();
$shotCount = 0;
$directionCount;
$boardSize;

$range = array ();

$xcor = array ();
$ycor = array ();

function randomCoordinates($pid) {
	

	
	global $xcor;
	global $ycor;
	
	//If the file does not exist, it generate two arrays that will be used to create random coordinates
	if (!file_exists ( "../new/"."$pid"."S.txt" )) {
		
		for($i = 1; $i <= 10; $i ++)
			for($j = 1; $j <= 10; $j ++) {
				array_push ( $xcor, $j );
				array_push ( $ycor, $j );
			}
		
		shuffle($xcor);
		shuffle($ycor);
		write($pid);
	}
	
	read($pid);
	global $shotsTaken;
	
	createRandom:
	
		shuffle($xcor);
		shuffle($ycor);
		$x = array_pop($xcor);
		$y = array_pop($ycor);
		
		$shot = array($x,$y);
		$xy = "$x$y";
		//echo $xy;
		
		if(in_array($xy,$shotsTaken))
			goto createRandom;
		else{ 
			array_push($shotsTaken,$xy);
			write($pid);
			return $shot;
		}
}

function write($pid) {
	global $prevHitBool;
	global $prevShotXcoord;
	global $prevShotYcoord;
	
	global $shotsTaken;
	global $lastHit;
	global $shotCount;
	global $directionCount;
	global $boardSize;
	
	global $xcor;
	global $ycor;
	
	$writeToFile = array (
			$prevHitBool,
			$prevShotXcoord,
			$prevShotYcoord,
			$shotsTaken,
			$lastHit,
			$shotCount,
			$directionCount,
			$boardSize, 
			$xcor,
			$ycor
	);
	
	$gameInfoSmart = fopen ( "../new/"."$pid"."S.txt", "w+" );
	fwrite ( $gameInfoSmart, json_encode ( $writeToFile ) );
}
function read($pid) {
	
	// This method will read the info in the file and organize it in the respective variables.
	global $prevHitBool;
	global $prevShotXcoord;
	global $prevShotYcoord;
	
	global $shotsTaken;
	global $lastHit;
	global $shotCount;
	global $directionCount;
	global $boardSize;
	
	global $xcor;
	global $ycor;
	
	$myfile = fopen ( "../new/"."$pid"."S.txt", "r" ) or die ( "Unable to open file!" );
	$gameInfoSmart = fgets ( $myfile );
	$variables = json_decode ( $gameInfoSmart, true );
	
	$prevHitBool = $variables [0];
	$prevShotXcoord = $variables [1];
	$prevShotYcoord = $variables [2];
	$shotsTaken = $variables [3];
	$lastHit = $variables [4];
	$shotCount = $variables [5];
	$directionCount = $variables [6];
	$boardSize = $variables [7];
	
	$xcor = $variables[8];
	$ycor = $variables[9];
	
	
}
function smart($pid) {
	
	/*
	 * For this method to work we need:
	 * If the last shot made by the computer hit, set $prevHitBool[0] and $prevHitBool[1] = true
	 * If the last shot made by the computer hit, set $lastHit[0] = the X coordinate, and set $lastHit[1] = the Y coordinate
	 * If the last shot made by the computer did not hit, set $prevHitBool[0] = false
	 * If the last shot sunk a ship, set $prevHitBool[0] and $prevHitBool[1] = false
	 * Each time a shot is made, set $prevX and $prevY = x and y, respectively
	 *
	 */
	
	if (file_exists ( "../new/"."$pid"."S.txt" ))
		read($pid);
	
	
	global $prevHitBool;
	global $shotsTaken;
	global $prevShotXcoord;
	global $prevShotYcoord;
	global $directionCount;
	global $boardSize;
	global $lastHit;
	
	$shot = array ();
	
	if ($prevHitBool [0] || $prevHitBool [1] && $directionCount < 4) { // = if(lastShot || previousShot)
		
		if ($prevHitBool [0] != $prevHitBool [1]) {
			$prevShotXcoord = $lastHit [0];
			$prevShotYcoord = $lastHit [1];
		}
		
		pickShot:
		
		if ($directionCount == 0) { // The next shot will be to the right, unless invalid
			
			$shot [0] = $prevShotXcoord + 1;
			$shot [1] = $prevShotYcoord;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevShotXcoord == $boardSize || in_array ( $seek, $shotsTaken )) {
				$directionCount = 1;
				goto pickShot;
			} 

			else {
				array_push($shotsTaken,$seek);
				$shotCount ++;
				return $shot;
			}
		}
		if ($directionCount == 1) { // The next shot will be to the left, unless invalid
			
			$shot [0] = $prevShotXcoord - 1;
			$shot [1] = $prevShotYcoord;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevShotXcoord == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 2;
				goto pickShot;
			} 

			else {
				array_push($shotsTaken,$seek);
				$shotCount ++;
				return $shot;
			}
		}
		if ($directionCount == 2) { // The next shot will be upwards, unless invalid
			
			$shot [0] = $prevShotXcoord;
			$shot [1] = $prevShotYcoord + 1;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevShotXcoord == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 3;
				goto pickShot;
			} 

			else {
				array_push($shotsTaken,$seek);
				$shotCount ++;
				return $shot;
			}
		}
		
		if ($directionCount == 3) { // The next shot will be downwards, unless invalid
			
			$shot [0] = $prevShotXcoord;
			$shot [1] = $prevShotYcoord - 1;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevShotXcoord == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 4;
				goto pickShot;
			} 

			else {
				array_push($shotsTaken,$seek);
				$shotCount ++;
				return $shot;
			}
		}
	} else {
		
		$directionCount = 0;
		$shot = randomCoordinates();
		$seek = "$shot[0]" . "$shot[1]";
		
		write($pid);
		return $shot;
		}
	
	
}

?>