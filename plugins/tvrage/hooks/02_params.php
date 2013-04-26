<?php 
if(isset($_REQUEST["limit"])){
	$limit_set = (string) $_REQUEST["limit"];
} else {
	$limit_set = 20;
}

if(isset($_REQUEST["offset"])){
	$offset_set = (string) $_REQUEST["offset"];
} else {
	$offset_set = 0;
}
?>