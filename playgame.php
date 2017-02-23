<?php
$prevHitBool = array ();
$prevX;
$prevY;
$shotsTaken = array ();
$lastHit = array ();
$shotCount = 0;
$directionCount;
$boardSize;

$range = array ();

$count = 1;
while ( $count < 100 ) {
	
	$range [$count] = $count;
	$count ++;
}

randomStrategy ();
smart ();
function smart() {
	
	/*For this method to work we need: 
	 * 	If the last shot made by the computer hit, set $prevHitBool[0] and $prevHitBool[1] = true
	 * 	If the last shot made by the computer hit, set $lastHit[0] = the X coordinate, and set $lastHit[1] = the Y coordinate
	 *	If the last shot made by the computer did not hit, set $prevHitBool[0] = false 
	 * 	If the last shot sunk a ship, set $prevHitBool[0] and $prevHitBool[1] = false
	 * 	Each time a shot is made, set $prevX and $prevY = x and y, respectively
	 * 
	 */
	
	
	global $prevHitBool;
	global $shotsTaken;
	global $prevX;
	global $prevY;
	global $directionCount;
	global $boardSize;
	global $lastHit;
	
	$shot = array ();
	
	if ($prevHitBool [0] || $prevHitBool [1] && $directionCount < 4) { // = if(lastShot || previousShot)
		
		if ($prevHitBool [0] != $prevHitBool [1]) {
			$prevX = $lastHit [0];
			$prevY = $lastHit [1];
		}
		
		pickShot:
		
		if ($directionCount == 0) { // The next shot will be to the right, unless invalid
			
			$shot [0] = $prevX + 1;
			$shot [1] = $prevY;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevX == $boardSize || in_array ( $seek, $shotsTaken )) {
				$directionCount = 1;
				goto pickShot;
			} 

			else {
				$shotsTaken [$shotCount] = $seek;
				$shotCount ++;
				return $shot;
			}
		}
		if ($directionCount == 1) { // The next shot will be to the left, unless invalid
			
			$shot [0] = $prevX - 1;
			$shot [1] = $prevY;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevX == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 2;
				goto pickShot;
			} 

			else {
				$shotsTaken [$shotCount] = $seek;
				$shotCount ++;
				return $shot;
			}
		}
		if ($directionCount == 2) { // The next shot will be upwards, unless invalid
			
			$shot [0] = $prevX;
			$shot [1] = $prevY + 1;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevX == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 3;
				goto pickShot;
			} 

			else {
				$shotsTaken [$shotCount] = $seek;
				$shotCount ++;
				return $shot;
			}
		}
		
		if ($directionCount == 3) { // The next shot will be downwards, unless invalid
			
			$shot [0] = $prevX;
			$shot [1] = $prevY - 1;
			$seek = "$shot[0]" . "$shot[1]";
			
			if ($prevX == 1 || in_array ( $seek, $shotsTaken )) {
				$directionCount = 4;
				goto pickShot;
			} 

			else {
				$shotsTaken [$shotCount] = $seek;
				$shotCount ++;
				return $shot;
			}
		}
	} else {
		
		$directionCount = 0;
		global $range;
		a:
		shuffle ( $range );
		$cell = array_pop ( $range );
		
		$x;
		$y;
		
		if ($cell < 10) {
			$x = 1;
			$y = $cell % 10;
		} else {
			$x = floor ( ($cell / 10) );
			$y = $cell % 10;
		}
		
		if (in_array ( "$x" . "$y", $shotsTaken )) {
			
			goto a;
		} else {
			global $shotCount;
			$shotsTaken [$shotCount] = "$x" . "$y";
			$shotCount ++;
			$directionCount = 0;
			$shot [0] = $x;
			$shot [1] = $y;
			return $shot;
		}
	}
}
function randomStrategy() {
	$range = array ();
	
	$count = 1;
	while ( $count < 100 ) {
		
		$range [$count] = $count;
		$count ++;
	}
	$count = 1;
	while ( $count < 100 ) {
		
		shuffle ( $range );
		$cell = array_pop ( $range );
		
		$x;
		$y;
		
		if ($cell < 10) {
			$x = 1;
			$y = $cell % 10;
		} else {
			$x = floor ( ($cell / 10) );
			$y = $cell % 10;
		}
		
		echo "$x $y\n";
		$count ++;
	}
}

?>