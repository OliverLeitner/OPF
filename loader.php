<?php
/*
 * Loading our core stuff...
 * this is what we need everywhere...
 */
//loading our libraries
require_once("conf/config.php");
require_once("language/".DEFAULT_LANG."/errors.php");
require_once("libs/class.errorhandler.php");
require_once("libs/db/class.".DB_TYPE.".php");
require_once("libs/db/class.db_core.php");
require_once("libs/class.parser.php");
require_once("libs/class.datacon.php");
require_once("libs/class.dynloader.php");

//and we initialize everything...
$errors = new OPF\Core\Errors($errorsArray);
$db_con = new OPF\Database\DB();
$db_core = new OPF\Database\DB_CORE();
$parser = new OPF\Parsing\Parser();
$data_con = new OPF\Core\CON();
$dynamic = new OPF\Core\dynamicLoader();

//include all sources as requested...
if (isset($_SERVER['argc'])) {
	$shell = TRUE;
} else {
	$shell = FALSE;
}
$out_sources = $dynamic->loadSources($shell);
foreach($out_sources AS $key => $value){ require_once($value); }

//and get the plugin array ready...
$plugin_out = array();
?>
