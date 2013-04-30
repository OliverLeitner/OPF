<?php
/**
 * These are some functions needed to grab from tvrage
 * 
 * @author	Oliver Leitner <Shadow333[AT]gmail.com>
 */
//some params for tvrage source...
define('TVRAGE_FEED','http://services.tvrage.com/tools/quickschedule.php');
define('TVRAGE_DETAILS','http://services.tvrage.com/feeds/episodeinfo.php');
define('TVRAGE_COUNTRY', 'USA');

class RAGE extends \OPF\Parsing\CON {

	/**
	 *
	 * Read from tvrage quickschedule output
	 * into an array
	 *
	 * @param  array $dataArray	holds the tvrage data to be parsed
	 *
	 * @return array $data_arr	Array of rage data for database insertion
	 */
	public function parseQuickData($dataArray){
		$data_arr = array();
		$iter = 0;
		
		if(is_array($dataArray)){
			foreach($dataArray AS $date){
				preg_match_all("/\[TIME\](.*?)\[\/TIME\]/si",$date,$out_time);
				preg_match("/\[DAY\](.*?)\[\/DAY\]/si",$date,$out_date);
				preg_match_all("/\[SHOW\](.*?)\[\/SHOW\]/si",$date,$out_show);

				if(is_array($out_time[1])){
					foreach($out_time[1] AS $timer){
						$time = $timer;
						foreach($out_show[1] AS $show){
							$data_arr[$iter]["show"][] = strtotime($out_date[1])."^".strtotime($time)."^".$show;
						}
					}
				} else {
					die(\OPF\Core\Errors::returnError("invalid_data",""));
				}
				$iter++;
			}
		} else {
			die(\OPF\Core\Errors::returnError("no_data",""));
		}
		
		return $data_arr;
	}

	/**
	 *
	 * Get genredata for a series from tvrage
	 *
	 * @param  string $url			series page url
	 * @param  object $con			data connection
	 *
	 * @return string $out			The Genre
	 */
	public function getGenre($url){
		if(!$data = $this->feedCon($url)){
			die(\OPF\Core\Errors::returnError("tvrage_details_error",""));
		}
		return $data;
	}

	/**
	 *
	 * Get every information about a single episode
	 *
	 * @param string $url				URL of xml detail page from tvrage
	 * @param array  $paramsArr			The episode we want to get details for
	 * @param object $con				The data connection
	 *
	 * @return object $xml				XML Dump of tvrage details
	 */
	public function getDetails($url,$paramsArr){
		$params = "";
		
		if(is_array($paramsArr)){
			foreach($paramsArr AS $key => $value){
				$params .= urlencode(stripslashes($key))."=".urlencode(stripslashes($value))."&";
			}
			$params = rtrim($params,"&");
		} else {
			print(\OPF\Core\Errors::returnError("no_data",""));
		}
		
		if(!$xml = $this->feedCon($url."?exact=1&".$params)){
			print(\OPF\Core\Errors::returnError("tvrage_details_error",""));
		}
		return $xml;
	}
}

//we just initialize it here, so we can dynamically
//load it from our scripts...
$source_con = new RAGE();
?>
