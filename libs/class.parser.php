<?php
/**
 * Basic document parsing functionality
 * 
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 *
 */
namespace OPF\Parsing;
class Parser {

	/**
	 * 
	 * General content indention and cleanup
	 * 
	 * @param string $string	input string
	 * @param string $type		xml or html
	 * 
	 * @return string $result	cleaned string
	 */	
	public function cleanup($string,$type="html"){
		$doc = new \DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		if($type == "html"){
        	$string = preg_replace('/[ ]+/', ' ', $string);
        	$result = preg_replace('/<!--[^-]*-->/', '', $string);
		} else {
			$string = preg_replace("/[\r\n]/", '', trim($string));
			$doc->loadXML($string);
			$result = $doc->saveXML();
		}
		return $result;
	}

	/**
	 *
	 * Grabs a file from an url, stores it locally, and if it exists,
	 * grabs the local copy instead
	 *
	 * @param string	$feedurl	url of the website to grab
	 * @param string	$filename	filename of the cache file
	 * @param object	$data_con	the data connection to use
	 *
	 * @return array	$array		file data as array
	 */
	public function getFileArray($feedurl,$filename,$data_con){
		$filename = DEFAULT_CACHE.$filename;

		if(!file_exists($filename)){
			$data = $data_con->feedCon($feedurl);
			$fh = fopen($filename,"w");
			fwrite($fh,$data);
			fclose($fh);
		} else {
			$fh = fopen($filename,"r");
			$data = fread($fh,filesize($filename));
			fclose($fh);
		}
		if(!$array = explode("\n\n",$data)){
			die(Errors::returnError("file_missing",$filename));
		}
		return $array;
	}
	
	/**
	 * 
	 * Takes a string and makes it xml compatible
	 * @param string $string	input string
	 * 
	 * @return string @			xml compatible return string
	 */
	function xmlentities($string) {
   		return str_replace ( array ( '&', '"', "'", '<', '>', '�' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), trim($string) );
	} 

	/**
	 *
	 * Fill a template with an array of tags and values
	 *
	 * @param object $db_con	the connection for grabbing the template
	 * @param array $resArray	the array with the data to map to the template
	 * @param string $template_main	the empty template to use
	 * @param string $start_tag	start tag for the returned data
	 * @param string $end_tag	end tag for the returned data
	 * @param string $optData	add text but before the endtag
	 *
	 * @return string $returns	string containing the done template
	 */
	public function fillRepeatingTemplate($db_con,$resArray,$template_main,$start_tag="<item>",$end_tag="</item>",$optData){
		$returns = "";
		if(!is_array($resArray) || empty($resArray) || !isset($resArray)){
			die(Errors::returnError("template_error",""));
		}
		
		foreach($resArray AS $key => $value){
			$out_entries = "";
			foreach($value AS $itemkey => $itemname){
				$entry = preg_replace("/###ENTRY###/",$this->xmlentities($itemname),$template_main);
				$entry = preg_replace("/###TAG###/",$itemkey,$entry);
				$out_entries .= $entry;
			}
			$optionalData = "";
			if(isset($optData)){
				$optionalData = $optData;
			}
			$returns .= $start_tag.$out_entries.$optionalData.$end_tag;
		}
		return $returns;
	}

	/**
	 *
	 * just fill a template with tag -> value from infoArray
	 * @param array $infoArray	the data to fill the template with
	 * @param string $subData	add any precompiled text into the template
	 * @param string $template	the empty template
	 *
	 * @return string	$template	the done master template
	 */
	public function fillMainTemplate($infoArray,$subData,$template){
		
		if(!is_array($infoArray) || empty($infoArray) || !isset($infoArray)){
			die(Errors::returnError("template_error",""));
		}
		
		foreach($infoArray AS $key => $value){
			$template = str_replace("###".strtoupper($key)."###",trim($value),$template);
		}
		$template = str_replace("###ENTRIES###",$subData,$template);
		return $template;
	}
}
?>
