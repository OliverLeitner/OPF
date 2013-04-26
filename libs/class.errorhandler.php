<?php
/**
 * This is the base error handling
 * functionality.
 * 
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 */
namespace OPF\Core;
class Errors {
	public static $errors = array();
	
	function __construct($errorsArray){
		Errors::$errors = $errorsArray;
	}
	
	/**
	 * 
	 * Reads an error definition from Error Array, replaces ###VALUE###
	 * against provided variable and returns the string.
	 * 
	 * @param string  $definition	Entry from errorsArray
	 * @param string  $variable		Any Variable that describes the error source
	 * 
	 * @return string $error		The Error information for output
	 */
	public function returnError($definition,$variable){
		$error = str_replace("###VALUE###",trim($variable),Errors::$errors[$definition]);
		return $error;
	}
}
?>