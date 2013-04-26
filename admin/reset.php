<?php
//die("remove this line to activate the script.");
/*
 *  Connect to Mysql Database
 */
//$con=mysql_connect('localhost','username','password');
mysql_select_db('tvguide');
/*
 *  Get all the tables in the database
 */
$database_tables = mysql_query('show tables');
/*
 *  Define the tables to preserve data
 */
$preserve_tables = array();
/*
 *  Check the tables to clean up and clear all the data
 */
while($row=mysql_fetch_array($database_tables)){
	$table = trim($row[0]);
	if(!(in_array($table,$preserve_tables))){
		mysql_query("truncate ".$table);
	}
}
?>
