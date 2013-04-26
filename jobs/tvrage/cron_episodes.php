<?php
/*
 writing episodes into tvguide database
 */
if (!isset($_SERVER['argc'])) {
    die("no website here...");
}

ini_set("max_execution_time",1200);
libxml_use_internal_errors(true);

require_once("../../loader.php");

$filename = "tvrage_data.xml";
$sub_arr = $parser->getFileArray(DEFAULT_FEED,$filename,$data_con);
$data_arr = $source_con->parseQuickData($sub_arr);

foreach($data_arr AS $key => $value){
	set_time_limit(0);
	foreach($value["show"] AS $out_arr){
		$out_arr = explode("^",$out_arr);
		$row_seriesid = $db_core->getQuery(
			"series",
		array("seriesid"),
		NULL,
		array("seriesname" => addslashes($out_arr[3])),
		NULL,NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
		);

		$check = $db_core->getQuery(
			"episodes",
		array("seriesid,episodenum"),
		NULL,
		array("seriesid" => addslashes($row_seriesid[0])),
		array("episodenum" => addslashes($out_arr[4])),
		NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
		);

		if(isset($row_seriesid[0]) && $check == ""){
			$ret_id = $db_core->setQuery("episodes",array("episodenum" => addslashes($out_arr[4]),"seriesid" => addslashes($row_seriesid[0])),array(),$db_con,"INSERT");
			
			$row = $db_core->getQuery(
				"channels",
			array("chanid"),
			NULL,
			array("channame" => addslashes($out_arr[2])),
			NULL,NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
			);

			$row_sched = $db_core->getQuery(
				"schedules",
			array("scheduleid"),
			NULL,
			array("combined" => addslashes($out_arr[1])),
			NULL,NULL,NULL,NULL,NULL,NULL,$db_con,"SINGLE"
			);

			$db_core->setQuery("channels_mm_episodes",array("chanid" => addslashes($row[0]),"episodeid" => addslashes($ret_id)),array(),$db_con,"INSERT");
			$db_core->setQuery("schedules_mm_episodes",array("scheduleid" => addslashes($row_sched[0]),"episodeid" => addslashes($ret_id)),array(),$db_con,"INSERT");
		}
	}
}
?>
