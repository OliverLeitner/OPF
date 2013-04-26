# you can add this script to your daily cron
#!/bin/bash

#change these to your likings...
SCRIPT_DIR="./"
RAGE_CACHE="../cache/tvrage_data.xml"
PHP_PATH=`which php`
#the following you may want to change to your needs...
PHP_INI="/etc/php5/cli/php.ini"

#first we cleanup leftovers
rm -f $RAGE_CACHE

#grab series data
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_series.php

#grab channels
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_categories.php

#grab showschedules
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_schedules.php

#grab episode data
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_episodes.php

#grab genres
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_genres.php

#grab extra episode data
$PHP_PATH -c $PHP_INI $SCRIPT_DIR""cron_parsetitle.php

exit 0
