<?php
/*
 central plugin loader
 this gets loaded before our
 plugin, here we can influence
 how our actual pages are named etc...
 */
ini_set("display_errors","On");

//first we define our current name...
$plugin_name["tvrage"] = "tvrage";

//index output
$data = array(
	"feed" => "tvrage/views/browse.php",
	"channels" => "tvrage/views/channels.php",
);

//add our custom error info to errorsArray
//can be included too...
//you can overwrite errors too, by using existing names from
//languages/DEFAULT_LANG/errors.php
$customErrors = array(
	"tvrage_quickschedule_error" => "Unable to read from ".TVRAGE_FEED,
	"tvrage_details_error" => "Unable to read from ".TVRAGE_DETAILS,
	"tvrage_country_missing" => "Unable to find ".TVRAGE_COUNTRY
);
$errorsArray = array_merge($errorsArray,$customErrors);

//add our stuff to output.
$plugin_out = array_merge($plugin_out,$data);
?>
