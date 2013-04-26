<?php
/*
 central plugin loader
 this gets loaded before our
 plugin, here we can influence
 how our actual pages are named etc...
 */

//first we define our current name...
$plugin_name["help"] = "help";

//index output
$data = array(
	"home" => "help/views/home.php",
	"plugin_dev" => "help/views/plugin.php",
	"source_dev" => "help/views/source.php",
	"database_dev" => "help/views/database.php",
	"tvrage" => "help/views/tvrage.php"
);

//add our stuff to output.
$plugin_out = array_merge($plugin_out,$data);
?>
