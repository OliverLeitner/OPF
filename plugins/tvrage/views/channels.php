<?php
/*
 a sample listing of everything available
 so, we can do html too, if we want to...
 */
$channels = $db_core->getQuery(
	"channels", //table
NULL, //fields
NULL, //keys
NULL, //where
NULL, //sqlands
NULL, //sqlors
array("values" => "channame"), //sortparms
	"ASC", //sortorder
NULL, //group
NULL, //limit
$db_con, //dbcon
	"FULL" //functiontype
);

$countries = $db_core->getQuery(
	"country",
NULL,
NULL,
NULL,
NULL,
NULL,
array("values" => "countryname"),
	"ASC",
NULL,NULL,$db_con,"FULL"
);

$series = $db_core->getQuery(
	"series",
NULL,
NULL,
NULL,
NULL,
NULL,
array("values" => "seriesname"),
	"ASC",
NULL,NULL,$db_con,"FULL"
);

$classes = $db_core->getQuery(
	"classes",
NULL,
NULL,
NULL,
NULL,
NULL,
array("values" => "classname"),
	"ASC",
NULL,NULL,$db_con,"FULL"
);

$statuses = $db_core->getQuery(
	"status",
NULL,
NULL,
NULL,
NULL,
NULL,
array("values" => "statusname"),
	"ASC",
NULL,NULL,$db_con,"FULL"
);

$out = '
<html>
	<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Channel listing</title>
		<style>
			body{font-family:verdana;font-size:10pt;}
			div{float:left;}
		</style>
	</head>
	<body>
<h1>listings</h1>
<div class="content">
<h2>channels</h2>
<ul>
';

foreach($channels AS $key => $channel){
	$out .= '<li><a href="index.php?page=feed&channel='.$db_con->escape_string($channel[1]).'" >'.$channel[1].'</a></li>';
}

$out .= '
		</ul>
		</div>
<div class="content">
<h2>countries</h2>
<ul>';

foreach($countries AS $key => $country){
	$out .= '<li><a href="index.php?page=feed&country='.$db_con->escape_string($country[1]).'" >'.$country[1].'</a></li>';
}

$out .= '</ul>
</div>
<div class="content">
<h2>series</h2>
<ul>';

foreach($series AS $key => $serie){
	if($serie[1] != ""){
		$out .= '<li><a href="index.php?page=feed&series='.urlencode($db_con->escape_string($serie[1])).'" >'.utf8_decode(trim($serie[1])).'</a></li>';
	} else {
		$out .= '<li>'.$serie[1].'</li>';
	}
}

$out .= '</ul>
</div>
<div class="content">
<h2>classifications</h2>
<ul>';

foreach($classes AS $key => $classification){
	if($classification[1] != ""){
		$out .= '<li><a href="index.php?page=feed&class='.urlencode($db_con->escape_string($classification[1])).'" >'.utf8_decode(trim($classification[1])).'</a></li>';
	} else {
		$out .= '<li>'.$classification[1].'</li>';
	}
}

$out .= '</ul>
</div>
<div class="content">
<h2>status</h2>
<ul>';

foreach($statuses AS $key => $status){
	if($status[1] != ""){
		$out .= '<li><a href="index.php?page=feed&status='.urlencode($db_con->escape_string($status[1])).'" >'.utf8_decode(trim($status[1])).'</a></li>';
	} else {
		$out .= '<li>'.$status[1].'</li>';
	}
}

$out .= '</ul>
</div>
	</body>
</html>
';

$page = array();
$page["channels"] = $out;
?>
