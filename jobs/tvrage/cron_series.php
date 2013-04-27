<?php
/*
 writing series into tvguide database
 */
/*
 * first a lil bit of security measure...
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
			"series",
		array("seriesname"),
		NULL,
		array("seriesname" => addslashes($out_arr[3])),
		NULL,NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
		);

		if($check == ""){
			$db_core->setQuery("series",array("seriesname" => addslashes($out_arr[3]),"serieslink" => addslashes($out_arr[5])),array(),$db_con,"INSERT");
		}
	}
}
?>
