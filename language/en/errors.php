<?php
/**
 * this file holds the structure for error messages
 */
$errorsArray = array (
	"db_no_connect" => "Could not connect to Database ###VALUE###, wrong credentials?",
	"db_wrong_getQuery_statement" => "You have a Syntax Error within your getQuery statement: <b>###VALUE###</b>.",
	"db_wrong_setQuery_statement" => "You have a Syntax Error within your setQuery statement: <b>###VALUE###</b>.",
	"db_wrong_countRows_statement" => "You have a Syntax Error within your countRows statement: <b>###VALUE###</b>.",
	"file_missing" => "Could not find the Filename you have specificied: ###VALUE.",
	"include_missing" => "The file ###VALUE### you tried to include does not exist.",
	"no_such_class"	=> "Unable to find a Class with the Name ###VALUE###.",
	"no_such_function"	=> "Unable to find a function with the Name ###VALUE###.",
	"page_missing" => "The Page ###VALUE### you tried to load is unavailable at the moment, please try again later.",
	"url_no_connect" => "Unable to connect to ###VALUE###, please try again later.",
	"tidy_error" => "Unable to clean (tidy) your string.",
	"template_error" => "There is an error in your Template data, please check your data (var_dump, print_r...).",
	"unescapeable_string" => "I was not able to escape the string '###VALUE###' you provided.",
	"db_wrong_setOPchoice" => "I was unable to compute your database request of type ###VALUE###, possible types are INSERT or UPDATE.",
	"db_wrong_getOPchoice" => "I was unable to compute your database request of type ###VALUE###, possible types are SINGLE/FULL/MM.",
	"db_missing_data" => "You have an error on the Data you provided for the SQL INSERT/UPDATE statement on the TABLE ###VALUE###.",
	"db_malformed_where" => "You had an Error in your WHERE clause while trying to INSERT/UPDATE on table ###VALUE###.",
	"db_table_missing" => "Your Request is missing the Tablename.",
	"db_invalid_conObj" => "You did not provide a Database Connection Object for your Statement.",
	"db_misc_error" => "Your SQL statement on the table ###VALUE### did not return any data.",
	"plugin_missing" => "A plugin with the name ###VALUE### does not exist.",
	"no_connection_object" => "The connection you provided does not exist.",
	"no_data" => "You did not provide any data for your Request.",
	"invalid_data" => "We can not parse the data that you provided.",
	"no_such_directory" => "There is no Directory with the name: ###VALUE### within the plugin.",
	"no_files_found" => "There are no Templates provided in the Directory ###VALUE### given.",
	"nothing_here_yet" => "This page is under construction, please visit later."
);
?>