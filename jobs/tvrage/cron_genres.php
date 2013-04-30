<?php
/*
 parsing tvrage detailpage data into tvguide database
 */
if (!isset($_SERVER['argc'])) {
    die("no website here...");
}

ini_set("max_execution_time",1200);
libxml_use_internal_errors(true);
require_once("../../loader.php");
$data_array = $db_core->getQuery(
	"series",
array("serieslink"),
NULL, //keys..
NULL, //where..
NULL, //sqlands
NULL, //sqlors
NULL, //sortvalue
NULL, //sortorder
NULL, //group
NULL, //limit
	"FULL" //type
);

foreach($data_array AS $row){
	set_time_limit(0);
	$out = $source_con->getGenre($row[0]);
	if(is_array($out) && isset($out[1][0])){
		$pos = strpos($out[1][0], "|");
		if($pos !== false){
			$arr_genres = explode("|",$out[1][0]);
			foreach($arr_genres AS $genre){
				$check = $db_core->getQuery(
					"genres",
				array("genrename"),
				NULL,
				array("genrename" => addslashes(trim($genre))),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				if($check[0] == "" && trim($genre) != ""){
					$db_core->setQuery("genres",array("genrename" => addslashes(trim($genre))),array(),"INSERT");
				}
				unset($check);
			}
		} else if($pos === false) {
			$check = $db_core->getQuery(
				"genres",
			array("genrename"),
			NULL,
			array("genrename" => addslashes(trim($out[1][0]))),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			if($check[0] == "" && trim($out[1][0]) != ""){
				$db_core->setQuery("genres",array("genrename" => addslashes(trim($out[1][0]))),array(),"INSERT");
			}
			unset($check);
		} else {
			//do nothing...
		}
	}
	unset($out);
}
?>
