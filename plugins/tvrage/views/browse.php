<?php
/*
 this is the feed functionality
 have phun with it;-)
 */
//loading our database stuff...
$out_includes = $dynamic->loadHooks("tvrage");
foreach($out_includes AS $key => $value){ require_once($value); }

//loading our plugins templates...
$templates = $dynamic->loadTemplates($plugin_name["tvrage"]);

//creating the items for our template...
if(isset($_REQUEST["genre"])){
	//want a series by genre listing?
	$results_series_genres = $db_core->getQuery($genrepmain,$gfields,$genrespkeys,$wherep,NULL,NULL,NULL,NULL,NULL,$limit,"MM");
	$out_entries = $parser->fillRepeatingTemplate($results_series_genres,$templates["entry"],"<item>","</item>",NULL);
} else if(isset($_REQUEST["q"])){
	//search functionality here...
	$results_items = $db_core->getQuery($maintable,$fieldset,$joinkeys,$where_search,$sqlAnds,$sqlOrs_search,$sort,NULL,NULL,$limit,"MM");
	$out_entries = "";
	foreach($results_items AS $key => $value){
		$genrewhere = array("episodes.seriesid" => " = ".$value["seriesid"]);
		$results_genres = $db_core->getQuery($maintable,$genrefields,$genrekeys,$genrewhere,NULL,NULL,NULL,NULL,$genregroup,NULL,"MM");
		$itemwhere = array("episodes.episodeid" => " = ".$value["episodeid"]);
		$results_item_single = $db_core->getQuery($maintable,$fields,$joinkeys,$itemwhere,$sqlAnds,NULL,NULL,NULL,NULL,$limit,"MM");
		$out_genres = $parser->fillRepeatingTemplate($results_genres,$templates["sub"],"<genre>","</genre>",NULL);
		$out_entries .= $parser->fillRepeatingTemplate(
		$results_item_single,
		$templates["entry"],
			"<item>",
			"</item>",
			"<genres>".$out_genres."</genres>"
			);
	}
} else {
	//full feed display
	$results_items = $db_core->getQuery($maintable,$fieldset,$joinkeys,$where,$sqlAnds,NULL,$sort,NULL,NULL,$limit,"MM");
	$out_entries = "";
	foreach($results_items AS $key => $value){
		$genrewhere = array("episodes.seriesid" => " = ".$value["seriesid"]);
		$results_genres = $db_core->getQuery($maintable,$genrefields,$genrekeys,$genrewhere,NULL,NULL,NULL,NULL,$genregroup,NULL,"MM");
		$itemwhere = array("episodes.episodeid" => " = ".$value["episodeid"]);
		$results_item_single = $db_core->getQuery($maintable,$fields,$joinkeys,$itemwhere,$sqlAnds,NULL,NULL,NULL,NULL,$limit,"MM");
		$out_genres = $parser->fillRepeatingTemplate($results_genres,$templates["sub"],"<genre>","</genre>",NULL);
		$out_entries .= $parser->fillRepeatingTemplate(
		$results_item_single,
		$templates["entry"],
			"<item>",
			"</item>",
			"<genres>".$out_genres."</genres>"
			);
	}
}

//then we fill the maintemplate with the subtemplateentries and a few extras...
$count_entries = $db_core->getCountRows("episodeid","episodes");

$arrValues = array(
	"adm_mail" => ADMIN_EMAIL,
	"self" => ADMIN_URL,
	"baseurl" => ADMIN_URL,
	"imgurl" => ADMIN_LOGO,
	"count_total" => $count_entries[0],
	"current_offset" => $limit["offset"]
);

$output = $parser->fillMainTemplate($arrValues,$out_entries,$templates["body"]);

//and the output part.
//call is ?page=$arraykey (in our case ?page=feed)
$page = array();
$page["feed"] = $parser->cleanup($output,"xml");
?>