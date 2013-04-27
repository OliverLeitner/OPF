<?php
/**
 *
 * Dynamic Loader
 *
 * Enables plugin functionality
 *
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 *
 */
namespace OPF\Core;
class dynamicLoader {

	/**
	 *
	 * Load our templates for usage in plugins
	 * @param string $plugin	the plugin name
	 * @param object $data_con	data connection
	 *
	 * @return array $templates	an array with our templates
	 */
	public function loadTemplates($plugin,$data_con){

		if(!is_dir("plugins/".$plugin."/templates"))
			die(Errors::returnError("no_such_directory",$plugin."/templates"));
		
		if(!$plugin)
			die(Errors::returnError("plugin_missing",$plugin));
			
		if(!$data_con)
			die(Errors::returnError("url_no_connect",""));

		$dir = "plugins/".$plugin."/templates";
		$files = array_diff(scandir($dir), array("..","."));
		
		//and create a base template array
		if(is_array($files)){
			$templates = array();
			foreach($files AS $key => $file){
				$naming_array = explode(".",$file);
				$tname = $naming_array[0];
				$templates[$tname] = $data_con->feedCon("plugins/".$plugin."/templates/".$file);
			}
			return $templates;
		} else {
			die(Errors::returnError("no_files_found",$plugin."/templates"));
		}
	}

	/**
	 *
	 * Adds user defined Hooks
	 * for plugins to use.
	 *
	 * @param string $plugin	the plugin name
	 *
	 * @return string $db_opts	the full path of the hook file.
	 */
	public function loadHooks($plugin="help"){
		if(!is_dir("plugins/".$plugin."/hooks"))
			die(Errors::returnError("no_such_directory",$plugin."/hooks"));
		
		if(!$plugin)
			die(Errors::returnError("plugin_missing",$plugin));
		
		$dir = "plugins/".$plugin."/hooks";
		$files = array_diff(scandir($dir), array("..","."));

		$out_includes = array();
		//and create a base template array
		if(is_array($files)){
			foreach($files AS $key => $file){
				$out_includes[] = "plugins/".$plugin."/hooks/".$file;
			}
		} else {
			die(Errors::returnError("no_files_found",$plugin."/hooks"));
		}
		
		return $out_includes;
	}

	/**
	 * 
	 * Load every file from sources directory into an array
	 * usually being used for require_once... task.
	 * 
	 * @return	array	$ret_src	Array of Sources
	 */
	public function loadSources($shell=FALSE){
		$sources = explode(",",DEFAULT_SOURCES);
		
		$ret_src = array();
		foreach($sources AS $key => $value){
			if($shell === FALSE){
				$ret_src[] = "libs/sources/class.".$value.".php";
			} else {
                                $ret_src[] = "../../libs/sources/class.".$value.".php";
			}
		}
		return $ret_src;
	}
}
?>
