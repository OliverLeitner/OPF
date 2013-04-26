<?php
/*
 do your database calls here
 or maybe overwrite existing functionality?
 if you dont want to, leave it just like it is.
 */

/*	advanced table query parameters main part	*/
$fields = array("*"); //we want to fetch all fields of the result set
$where = array("combined" => " > ".DEFAULT_DATE); //everything after now, just get whats coming up on tv
$sort = array("values" => "schedules.combined"); //show upcoming shows in correct order
$sort = NULL;
$limit = array(
	"limit" => $limit_set,
    "offset" => $offset_set
);

//do we have GET parameters, if so, have them influence our results.
$sqlAnds = array(
	"channame" => isset($_REQUEST["channel"]) ? $_REQUEST["channel"] : "",
    "seriesname" => isset($_REQUEST["series"]) ? $_REQUEST["series"] : "",
    "episodenum" => isset($_REQUEST["episode"]) ? $_REQUEST["episode"] : "",
    "episodesid" => isset($_REQUEST["sid"]) ? $_REQUEST["sid"] : "",
    "countryname" => isset($_REQUEST["country"]) ? $_REQUEST["country"] : "",
    "statusname" => isset($_REQUEST["status"]) ? $_REQUEST["status"] : "",
    "classname" => isset($_REQUEST["class"]) ? $_REQUEST["class"] : ""
);

//all tables to LEFT JOIN with our main table.
$joinkeys = array();
$joinkeys[0]["channels_mm_episodes"] = "episodeid";
$joinkeys[0]["episodes"] = "episodeid";
$joinkeys[1]["channels"] = "chanid";
$joinkeys[1]["channels_mm_episodes"] = "chanid";
$joinkeys[2]["schedules_mm_episodes"] = "episodeid";
$joinkeys[2]["episodes"] = "episodeid";
$joinkeys[3]["schedules"] = "scheduleid";
$joinkeys[3]["schedules_mm_episodes"] = "scheduleid";
$joinkeys[4]["classes_mm_series"] = "seriesid";
$joinkeys[4]["episodes"] = "seriesid";
$joinkeys[5]["classes"] = "classid";
$joinkeys[5]["classes_mm_series"] = "classid";
$joinkeys[6]["country_mm_series"] = "seriesid";
$joinkeys[6]["episodes"] = "seriesid";
$joinkeys[7]["country"] = "countryid";
$joinkeys[7]["country_mm_series"] = "countryid";
$joinkeys[8]["status_mm_series"] = "seriesid";
$joinkeys[8]["episodes"] = "seriesid";
$joinkeys[9]["status"] = "statusid";
$joinkeys[9]["status_mm_series"] = "statusid";
$joinkeys[10]["series"] = "seriesid";
$joinkeys[10]["episodes"] = "seriesid";

//we want our maintable to be the one with the series episodes
$maintable = "episodes";
/*	this is the end of the main part of the table params	*/

/*	this is for genres listing */
$genrekeys = array();
$genrekeys[0]["genres_mm_series"] = "seriesid";
$genrekeys[0]["episodes"] = "seriesid";
$genrekeys[1]["genres"] = "genreid";
$genrekeys[1]["genres_mm_series"] = "genreid";
$genrefields = array("genres.genrename");
$genremain = "series";
$genrewhere = array("episodes.seriesid" => " = 1");
$genregroup = array("genres.genreid");
$genrepmain = "series";
$genrespkeys = array();
$genrespkeys[0]["genres_mm_series"] = "seriesid";
$genrespkeys[0]["series"] = "seriesid";
$genrespkeys[1]["genres"] = "genreid";
$genrespkeys[1]["genres_mm_series"] = "genreid";
$gfields = array("*","genres.genrename AS name","series.serieslink AS link","series.seriesname AS title");
$wherep = array("genrename" => isset($_REQUEST["genre"]) ? " LIKE '%".$_REQUEST["genre"]."%'" : "");
$gby["values"] = "seriesname";
/*	end of genres listing */

/*	search request table params start	*/
$fieldset = array("episodes.seriesid","episodes.episodeid");
if(isset($_REQUEST["q"])){$searchterm = " '%".urlencode($_REQUEST["q"])."%'";} else {$searchterm = "";}
$sqlOrs_search = array(
	"link" => $searchterm,
	"channame" => $searchterm,
	"classname" => $searchterm,
	"countryname" => $searchterm,
	"statusname" => $searchterm,
	"seriesname" => $searchterm,
	"serieslink" => $searchterm
);
$where_search = array("title" => " LIKE ".$searchterm);
/*	search request table params end		*/
?>