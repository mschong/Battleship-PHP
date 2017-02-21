<?php

$pid = $_GET ['pid'];
$shot = $_GET ['shot'];
$coord = explode(",", $shot);


//Pid not specified
if($pid == null){
	$noPid = array("response" => false, "reason" => "Pid not specified");
	exit( json_encode($noPid));
}

//Shot not specified
if ($shot == null){
	$noShot = array("response" => false, "reason" => "Shot not specified");
	exit( json_encode($noShot));
}

//Shot not well-formed
if(count($coord) != 2){
	$shotNotWellFormed = array("response" => false, "reason" => "Shot not well-formed");
	exit(json_encode($shotNotWellFormed));
}

//Invalid shot position
if($coord[0] < 1 || $coord[0] > 10 || $coord[1] < 1 || $coord[1] > 10 ){
	$invalidShot = array("response" => false, "reason" => "Invalid shot position");
	exit(json_encode($invalidShot));
}


?>