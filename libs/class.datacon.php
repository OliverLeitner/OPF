<?php
/**
 * This is the base connection class
 * 
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 *
 */
namespace OPF\Parsing;
class CON {
	private static $_ctx = null;

	/**
	 *
	 * set some basic connection options
	 */
	function __construct(){
		CON::$_ctx = stream_context_create(array(
				'http' => array(
					'timeout' => DEFAULT_TIMEOUT
		)
		));
	}

	/**
	 *
	 * grabs data from a remote url
	 *
	 * @param string $url	the remote destination
	 *
	 * @return string $results	the grabbed data
	 */
	public function feedCon($url){
		if(!$results = file_get_contents($url,0,CON::$_ctx)){
			die(\OPF\Core\Errors::returnError("url_no_connect",$url));
		}
		return $results;
	}
}
?>