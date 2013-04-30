<?php
/*
 get details for each episode into tvguide database
 this is where the main parser magic happens.
 */
if (!isset($_SERVER['argc'])) {
    die("no website here...");
}

ini_set("max_execution_time",1200);
libxml_use_internal_errors(true);

require_once("../../loader.php");

//check if episodename exists
$data_array = $db_core->getQuery(
	"episodes",
array("seriesid,episodenum,episodeid"),
NULL,
array("title" => ""),
array(
		"link" => "",
		"episodelength" => 0,
		"episodesid" => 0
),
NULL,NULL,NULL,NULL,NULL,"FULL"
);

foreach($data_array AS $row){
	set_time_limit(0);
	$seriesid = $db_core->getQuery(
		"series",
	array("seriesname"),
	NULL,
	array("seriesid" => addslashes($row[0])),
	NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
	);

	$xml = $source_con->getDetails(TVRAGE_DETAILS,array("show" => addslashes($seriesid[0]),"ep" => addslashes($row[1])));

	//and if it contains something of sense, we write it out.
	if($xml){
		$data = simplexml_load_string($xml);
		unset($xml);
	}

	if ($data !== false && $data != NULL) {
		$ep = array();

		$id = $data->attributes();
		$link = $data->link;
		$classification = $data->classification;
		$country = $data->country;
		$status = $data->status;
		$runtime = $data->runtime;
		$startdate = $data->started;
		$enddate = $data->ended;

		foreach($data AS $key => $episode){
			$ep["sid"] = $id;
			$ep["classification"] = $classification;
			$ep["country"] = $country;
			$ep["status"] = $status;
			$ep["runtime"] = $runtime;
			$ep["started"] = $startdate;
			$ep["ended"] = $enddate;
			$ep["link"] = $link;
			//if we got a detaillink, we use it
			$url = $data->episode->url;
			$title = $data->episode->title;
			if($url != ""){
				$ep["url"] = $url;
			}
			if($title != ""){
				$ep["title"] = $title;
			}
		}

		$genres = array();

		if(isset($data->genres->genre)){
			foreach($data->genres->genre AS $genre){
				$check_row = $db_core->getQuery(
					"genres",
				array("genreid"),
				NULL,
				array("genrename" => addslashes(trim($genre[0]))),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				$series_row = $db_core->getQuery(
					"series",
				array("seriesid"),
				NULL,
				array("seriesname" => addslashes($seriesid[0])),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				$check_mm = $db_core->getQuery(
					"genres_mm_series",
				array("genreid"),
				NULL,
				array("seriesid" => addslashes($series_row[0])),
				array("genreid" => addslashes($check_row[0])),
				NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				if($check_mm == ""){
					$db_core->setQuery("genres_mm_series",array("genreid" => addslashes($check_row[0]),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
				}
			}
		}

		if(isset($ep["title"][0])){
			$db_core->setQuery("episodes",array("title" => addslashes($ep["title"][0])),array("episodeid" => addslashes($row[2])),"UPDATE");
		}

		if(isset($ep["url"][0])){
			$db_core->setQuery("episodes",array("link" => addslashes($ep["url"][0])),array("episodeid" => addslashes($row[2])),"UPDATE");
		} else {
			$db_core->setQuery("episodes",array("link" => addslashes($ep["link"][0])),array("episodeid" => addslashes($row[2])),"UPDATE");
		}

		if(isset($ep["classification"][0])){
			$series_row = $db_core->getQuery(
				"series",
			array("seriesid"),
			NULL,
			array("seriesname" => addslashes($seriesid[0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			$check = $db_core->getQuery(
				"classes",
			array("classid"),
			NULL,
			array("classname" => addslashes($ep["classification"][0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			if($check == ""){
				$ret_id = $db_core->setQuery("classes",array("classname" => addslashes($ep["classification"][0])),array(),"INSERT");
				$db_core->setQuery("classes_mm_series",array("classid" => addslashes($ret_id),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
			} else {
				$ret_row = $db_core->getQuery(
					"classes",
				array("classid"),
				NULL,
				array("classname" => addslashes($ep["classification"][0])),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				$ret_check = $db_core->getQuery(
					"classes_mm_series",
				array("classid"),
				NULL,
				array("seriesid" => addslashes($series_row[0])),
				array("classid" => addslashes($ret_row[0])),
				NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				if($ret_check == ""){
					$db_core->setQuery("classes_mm_series",array("classid" => addslashes($ret_row[0]),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
				}
			}
		}

		if(isset($ep["country"][0])){
			$series_row = $db_core->getQuery(
				"series",
			array("seriesid"),
			NULL,
			array("seriesname" => addslashes($seriesid[0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			$check = $db_core->getQuery(
				"country",
			array("countryid"),
			NULL,
			array("countryname" => addslashes($ep["country"][0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			if($check == ""){
				$ret_id = $db_core->setQuery("country",array("countryname" => addslashes($ep["country"][0])),array(),"INSERT");
				$db_core->setQuery("country_mm_series",array("countryid" => addslashes($ret_id),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
			} else {
				$ret_row = $db_core->getQuery(
					"country",
				array("countryid"),
				NULL,
				array("countryname" => addslashes($ep["country"][0])),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				$ret_check = $db_core->getQuery(
					"country_mm_series",
				array("countryid"),
				NULL,
				array("seriesid" => addslashes($series_row[0])),
				array("countryid" => addslashes($ret_row[0])),
				NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				if($ret_check == ""){
					$db_core->setQuery("country_mm_series",array("countryid" => addslashes($ret_row[0]),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
				}
			}
		}

		if(isset($ep["status"][0])){
			$series_row = $db_core->getQuery(
				"series",
			array("seriesid"),
			NULL,
			array("seriesname" => addslashes($seriesid[0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			$check = $db_core->getQuery(
				"status",
			array("statusid"),
			NULL,
			array("statusname" => addslashes($ep["status"][0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);

			if($check == ""){
				$ret_id = $db_core->setQuery("status",array("statusname" => addslashes($ep["status"][0])),array(),"INSERT");
				$db_core->setQuery("status_mm_series",array("statusid" => addslashes($ret_id),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
			} else {
				$ret_row = $db_core->getQuery(
					"status",
				array("statusid"),
				NULL,
				array("statusname" => addslashes($ep["status"][0])),
				NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				$ret_check = $db_core->getQuery(
					"status_mm_series",
				array("statusid"),
				NULL,
				array("seriesid" => addslashes($series_row[0])),
				array("statusid" => addslashes($ret_row[0])),
				NULL,NULL,NULL,NULL,NULL,"SINGLE"
				);

				if($ret_check == ""){
					$db_core->setQuery("status_mm_series",array("statusid" => addslashes($ret_row[0]),"seriesid" => addslashes($series_row[0])),array(),"INSERT");
				}
			}
		}

		if(isset($ep["runtime"][0])){
			$db_core->setQuery("episodes",array("episodelength" => addslashes($ep["runtime"][0])),array("episodeid" => addslashes($row[2])),"UPDATE");
		}

		if(isset($ep["started"][0])){
			$series_row = $db_core->getQuery(
				"series",
			array("seriesid"),
			NULL,
			array("seriesname" => addslashes($seriesid[0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);
			$db_core->setQuery("series",array("startdate" => addslashes($ep["started"][0])),array("seriesid" => addslashes($series_row[0])),"UPDATE");
		}

		if(isset($ep["ended"][0])){
			$series_row = $db_core->getQuery(
				"series",
			array("seriesid"),
			NULL,
			array("seriesname" => addslashes($seriesid[0])),
			NULL,NULL,NULL,NULL,NULL,NULL,"SINGLE"
			);
			$db_core->setQuery("series",array("enddate" => addslashes($ep["ended"][0])),array("seriesid" => addslashes($series_row[0])),"UPDATE");
		}

		if(isset($ep["sid"][0])){
			$db_core->setQuery("episodes",array("episodesid" => addslashes($ep["sid"][0])),array("episodeid" => addslashes($row[2])),"UPDATE");
		}
	}
}
?>
