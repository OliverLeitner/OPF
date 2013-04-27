<?php
/*
 main config data
 you can overwrite all these defines in your plugins
 */
//database configuration values
//you can change those within your plugin
$config = array();
$config["db_host"] = "localhost";
$config["db_user"] = "database_user";
$config["db_password"] = "database_password";
$config["db_port"] = 3306;
$config["db_name"] = "database_name";
$config["db_type"] = "mysqli";

//other settings that are global constants
define('DEFAULT_STARTPAGE', 'home'); //page?=home is our starting page
define('DEFAULT_CACHE', '../../cache/'); //where to store received raw data from sources
define('DEFAULT_TIMEOUT', 600); //connection timeout for source grabbing
define('DEFAULT_DATE', strtotime("now -1 Day")); //current day and time and yaddah...
define('DEFAULT_SOURCES', 'tvrage'); //currently implemented tvrage, others to follow...
define('DEFAULT_LANG', 'en'); //for error handling etc...

//and here goes the actual output
define('ACTIVATE_PLUGINS', 'help,tvrage'); //loading our plugins...
?>
