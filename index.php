<?php
/*
 here we output everything
 */
/* 
 * we are handling our own errors... 
 * if you want php error display you
 * can always enable it within your plugin.php
 */
ini_set("display_errors","On");

//avoid XSS
if(isset($_REQUEST)){
	$_REQUEST = array_map("strip_tags",$_REQUEST);
}

//avoid Injection
if(isset($_REQUEST)){
	$_REQUEST = array_map("htmlentities",$_REQUEST);
}

//loading core functionality
require_once("loader.php");

//check if in general there was a page defined...
//if theres no page defined we reroute to default or
//bail out
if(!isset($_REQUEST["page"]) && DEFAULT_STARTPAGE){
	header("Location: ?page=".DEFAULT_STARTPAGE);
} else {
	$request = (string) $_REQUEST["page"];
}

//loading all our plugins
$arr_plugins = explode(",",ACTIVATE_PLUGINS);
foreach($arr_plugins AS $key => $plugin){
	include_once("plugins/".$plugin."/plugin.php");
}

//if page is unknown or not defined, we output an error...
if($plugin_out[$request] == ""){
	die($errors->returnError("page_missing",$request));
}

//creating our views
if($plugin_out[$request] != ""){
	include_once("plugins/".$plugin_out[$request]);
	if($page[$request] != ""){
		echo $page[$request];
	} else {
		die($errors->returnError("nothing_here_yet",""));
	}
}
?>
