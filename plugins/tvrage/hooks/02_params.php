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

//base parms
define("ADMIN_EMAIL", 'Shadow333@gmail.com');
define("ADMIN_URL", '/index.php?page=feed');
define('ADMIN_LOGO', '/tvguide/images/icon-35468_150.png');
?>