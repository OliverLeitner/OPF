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
	function setQuery($table,$dataarray,$wherearray,$dbcon,$type){
		
		if(!$table)
			die(Errors::returnError("db_table_missing",""));
			
		if(!$dbcon)
			die(Errors::returnError("db_invalid_conObj",""));
		
		if(is_array($dataarray)){
			if(isset($dataarray)){
				$data = array();
				foreach($dataarray AS $key => $value){
					$data[$key] = stripslashes($value);
				}
			}
		} else {
			die(Errors::returnError("db_missing_data",$table));
		}
		
		if(is_array($wherearray)){
			if(isset($wherearray)){
				$where = array();
				foreach($wherearray AS $key => $value){
					$where[$key] = stripslashes($value);
				}
			}
		} else {
			die(Errors::returnError("db_malformed_where",$table));
		}

		if($type == "INSERT"){
			$ret_id = $dbcon->queryInsert($table,$data);
		} else if($type == "UPDATE"){
			$dbcon->queryUpdate($table,$data,$where);
		} else {
			die(Errors::returnError("db_wrong_setOPchoice",$type));
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
	function getQuery($table,$fields,$keys,$where,$sqlAnds,$sqlOrs,$sortparms,$sortorder,$group,$limit,$dbcon,$type="SINGLE"){
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
			$res = $dbcon->querySingleRow($table,$fieldset,$where,$sqlAnds,$sqlOrs);
		} else if($type == "FULL"){
			$res = $dbcon->queryFullData($table,$fieldset,$where,$sqlAnds,$sqlOrs,$sortvalue,$sortorder);
		} else if($type == "MM"){
			$res = $dbcon->queryMMData($table,$fields,$keys,$where,$sqlAnds,$sqlOrs,$sortvalue,$sortorder,$group,$limit);
		} else {
			die(Errors::returnError("db_wrong_getOPchoice",$type));
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
	public function getCountRows($field,$table,$dbcon){
		if(!$res = $dbcon->queryCountRows($field,$table)){
			die(Errors::returnError("db_misc_error",$table));
		}
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
	public function escape_string($string,$dbcon){
		if($string != ""){
			$res = $dbcon->escape_string($string);
			return $res;
		}
	}
}
?>
