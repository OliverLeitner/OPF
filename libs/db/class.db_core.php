<?php
/**
 *
 * Database Core Functionality
 * independent from database engine...
 * 
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 *
 */
namespace OPF\Database;
class DB_CORE {
	
	//creates the data connection
	private static $dbcon = null;
	
	function __construct($config){
		DB_CORE::$dbcon = new DB($config); 
	}

	/**
	 *
	 * Run INSERT or UPDATE queries on Database tables
	 *
	 * @param string $table				the table to operate on
	 * @param array  $dataarray			the fieldnames and values to write
	 * @param array  $wherearray		where to replace/insert the data
	 * @param object $dbcon				the database connection
	 * @param string $type				the operation type (INSERT/UPDATE)
	 *
	 * @return int	 $ret_id			returns the id of the successful INSERT
	 */
	function setQuery($table,$dataarray,$wherearray,$type){
		
		if(!$table)
			die(\OPF\Core\Errors::returnError("db_table_missing",""));
		
		if(is_array($dataarray)){
			if(isset($dataarray)){
				$data = array();
				foreach($dataarray AS $key => $value){
					$data[$key] = stripslashes($value);
				}
			}
		} else {
			die(\OPF\Core\Errors::returnError("db_missing_data",$table));
		}
		
		if(is_array($wherearray)){
			if(isset($wherearray)){
				$where = array();
				foreach($wherearray AS $key => $value){
					$where[$key] = stripslashes($value);
				}
			}
		} else {
			die(\OPF\Core\Errors::returnError("db_malformed_where",$table));
		}

		if($type == "INSERT"){
			$ret_id = DB_CORE::$dbcon->queryInsert($table,$data);
		} else if($type == "UPDATE"){
			DB_CORE::$dbcon->queryUpdate($table,$data,$where);
		} else {
			die(\OPF\Core\Errors::returnError("db_wrong_setOPchoice",$type));
		}

		//if INSERT we return insert ID
		if($type == "INSERT")
		return $ret_id;
	}

	/**
	 *
	 * SELECT data from one or many tables in a database
	 *
	 * @param string $table					name of the table to operate on
	 * @param array $fields					name of fields to return
	 * @param array $keys					tablenames and primary keys to join them on
	 * @param array $where					WHERE parameters
	 * @param array $sqlAnds				AND parameters
	 * @param array $sqlOrs					OR parameters
	 * @param array $sortparms				ORDER BY fields
	 * @param string $sortorder				ORDER BY type (ASC,DESC)
	 * @param array $group					GROUP BY fields
	 * @param array $limit					LIMIT (array["offset"] => 0,array["limit"] => 20)
	 * @param object $dbcon					The Database connection
	 * @param string $type					Type of Query (SINGLE,FULL,MM (LEFT JOIN...))
	 *
	 * @return array $res					Database rows
	 */
	function getQuery($table,$fields,$keys,$where,$sqlAnds,$sqlOrs,$sortparms,$sortorder,$group,$limit,$type="SINGLE"){
		if(isset($sortparms)){
			$sortvalue = $sortparms["values"];
		} else {
			$sortvalue = "";
		}

		$fieldset = "*";
		if(isset($fields)){
			$fieldset = "";
			foreach($fields AS $value){
				$fieldset .= stripslashes($value).", ";
			}
			$fieldset = rtrim($fieldset,", ");
		}

		if($type == "SINGLE"){
			$res = DB_CORE::$dbcon->querySingleRow($table,$fieldset,$where,$sqlAnds,$sqlOrs);
		} else if($type == "FULL"){
			$res = DB_CORE::$dbcon->queryFullData($table,$fieldset,$where,$sqlAnds,$sqlOrs,$sortvalue,$sortorder);
		} else if($type == "MM"){
			$res = DB_CORE::$dbcon->queryMMData($table,$fields,$keys,$where,$sqlAnds,$sqlOrs,$sortvalue,$sortorder,$group,$limit);
		} else {
			die(\OPF\Core\Errors::returnError("db_wrong_getOPchoice",$type));
		}
		return $res;
	}

	/**
	 *
	 * Table Row Counter
	 *
	 * @param string $field				Field to count on
	 * @param string $table				Table to count rows from
	 * @param object $dbcon				Database Connection
	 *
	 * @return array $res				array[0] -> num rows in table
	 */
	public function getCountRows($field,$table){
		$res = DB_CORE::$dbcon->queryCountRows($field,$table);
		return $res;
	}

	/**
	 *
	 * Cleanup String for Database insertion
	 *
	 * @param string $string			the name of the string to cleanup
	 * @param object $dbcon				The database connection
	 *
	 * @return string					The cleaned string ready for insert/update
	 */
	public function escape_string($string){
		if($string != ""){
			$res = DB_CORE::$dbcon->escape_string($string);
			return $res;
		}
	}
}
?>
