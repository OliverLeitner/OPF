<?php
/*
 * Loading our core stuff...
 * this is what we need everywhere...
 */
//loading our libraries
require_once("conf/config.php");
require_once("language/".DEFAULT_LANG."/errors.php");
require_once("libs/class.errorhandler.php");
require_once("libs/db/class.".$config["db_type"].".php");
require_once("libs/db/class.db_core.php");
require_once("libs/class.datacon.php");
require_once("libs/class.parser.php");
require_once("libs/class.dynloader.php");

//and we initialize everything...
$errors = new OPF\Core\Errors($errorsArray);
$parser = new OPF\Parsing\Parser();
$dynamic = new OPF\Core\dynamicLoader();
$db_core = new OPF\Database\DB_CORE($config);

//include all sources as requested...
$out_sources = $dynamic->loadSources();
foreach($out_sources AS $key => $value){ require_once($value); }

//and get the plugin array ready...
$plugin_out = array();
?>
