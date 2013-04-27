<?php
/*
 writing categories into tvguide database
 */
if (!isset($_SERVER['argc'])) {
    die("no website here...");
}

ini_set("max_execution_time",1200);
libxml_use_internal_errors(true);

require_once("../../loader.php");

$filename = "tvrage_data.xml";
$sub_arr = $parser->getFileArray(TVRAGE_FEED,$filename,$data_con);
$data_arr = $source_con->parseQuickData($sub_arr);

foreach($data_arr AS $key => $value){
	set_time_limit(0);
	foreach($value["show"] AS $out_arr){
		$out_arr = explode("^",$out_arr);
		$check = $db_core->getQuery(
		"channels",
		array("channame"),
		NULL,
		array("channame" => addslashes($out_arr[2])),
		NULL,NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
		);

		if($check == ""){
			$db_core->setQuery("channels",array("channame" => $out_arr[2]),array(),$db_con,"INSERT");
		}
	}
}
?>
