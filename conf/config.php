<?php
/*
 main config data
 you can overwrite all these defines in your plugins
 */
//base parms
define('ADMIN_EMAIL','your@email.com');
define('ADMIN_URL','/index.php?page=feed');
define('ADMIN_LOGO','/tvguide/images/icon-35468_150.png');

//database configuration values
define('DB_TYPE', 'mysqli');
define('DB_HOST', 'localhost');
define('DB_USER', 'database_user');
define('DB_PASSWORD', 'database_password');
define('DB_PORT', 3306);
define('DB_NAME', 'database_name');

//other settings
define('DEFAULT_STARTPAGE', 'home'); //page?=home is our starting page
define('DEFAULT_CACHE', '../cache/'); //where to store received raw data from sources
define('DEFAULT_TIMEOUT', 600); //connection timeout for source grabbing
define('DEFAULT_DATE', strtotime("now -1 Day")); //current day and time and yaddah...
define('DEFAULT_SOURCES', 'tvrage'); //currently implemented tvrage, others to follow...
define('DEFAULT_LANG', 'en'); //for error handling etc...

//and here goes the actual output
define('ACTIVATE_PLUGINS', 'help,tvrage'); //loading our plugins...
?>
