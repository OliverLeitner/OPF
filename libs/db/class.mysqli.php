<?php
/**
 *
 * MySQLi Database Operations
 *
 * @author Oliver Leitner <Shadow333[AT]gmail.com>
 *
 */
namespace OPF\Database;
class DB {
	private static $conn = null;

	/**
	 *
	 * Building the Connection to our MySQL Database
	 */
	function __construct($config){
			DB::$conn = mysqli_init();
			DB::$conn->options(MYSQLI_INIT_COMMAND, 'SET NAMES \'utf8\'');
			DB::$conn->real_connect($config["db_host"], $config["db_user"], $config["db_password"], $config["db_name"], $config["db_port"], NULL, MYSQLI_CLIENT_FOUND_ROWS|MYSQLI_CLIENT_COMPRESS)
			or die(Errors::returnError("db_no_connect",$config["db_name"]));
			DB::$conn->set_charset("utf8");
	}

	/**
	 *
	 * Join Multiple Tables and SELECT on the Data
	 *
	 * @param string $maintable				The primary Table
	 * @param array  $fields				Fields to SELECT from
	 * @param array  $joinkeys				Tables and their primary keys to LEFT JOIN on
	 * @param array  $where					WHERE clause field, operator and param
	 * @param array  $sqlAnds				AND fields and params
	 * @param array  $sqlOrs				OR fields and params
	 * @param array  $sortvalue				ORDER BY fieldnames
	 * @param string $sortorder				ORDER BY type (ASC/DESC)
	 * @param array  $group					GROUP BY fieldname(s)
	 * @param array  $limit					Array containing LIMIT and OFFSET values
	 *
	 * @return array $returns				Array of selected Data
	 */
	function queryMMData($maintable,$fields,$joinkeys,$where,$sqlAnds,$sqlOrs,$sortvalue,$sortorder="DESC",$group,$limit){

		//field builder
		$sql_fields = "";
		if(isset($fields)){
			foreach($fields AS $field){
				$sql_fields .= $field.",";
			}
			$sql_fields = rtrim($sql_fields,",");
		}

		//everything WHERE
		$sql_where = "";
		if(is_array($where)){
			$sql_where = " WHERE ";
			foreach($where AS $key => $value){
				$sql_where .= $key.$value;
			}
		}

		//everything AND
		$sql_and = "";
		if(isset($sqlAnds)){
			$sql_and = " AND ";
			foreach($sqlAnds as $key => $value){
				if(is_numeric($value) && $value != ""){
					$comp = " = ";
					$append = " AND ";
					$colon = "'";
				} else if(!is_numeric($value) && $value != ""){
					$comp = " LIKE ";
					$append = " AND ";
					$colon = "'";
				} else {
					$comp = "";
					$key = "";
					$append = "";
					$colon = "";
				}
				$sql_and .= $key.$comp.$colon.$value.$colon.$append;
			}
			$sql_and = rtrim($sql_and," AND ");
		}

		//everything OR
		$sql_or = "";
		if(isset($sqlOrs)){
			$sql_and = " OR ";
			foreach($sqlOrs as $key => $value){
				if(is_numeric($value) && $value != ""){
					$comp = " = ";
					$append = " OR ";
					$colon = "'";
				} else if(!is_numeric($value) && $value != "") {
					$comp = " LIKE ";
					$append = " OR ";
					$colon = "'";
				} else {
					$comp = "";
					$key = "";
					$append = "";
					$colon = "";
				}
				$sql_or .= $key.$comp.$value.$append;
			}
			$sql_or = rtrim($sql_or," OR ");
		}

		//everything ORDER BY
		$sql_order = "";
		if($sortvalue != ""){
			$sql_order = " ORDER BY ".$this->escape_string($sortvalue)." ".$sortorder." ";
		}

		$sql_group = "";
		if(isset($group)){
			$sql_group = " GROUP BY ".$this->escape_string($group[0]);
		}

		//set limit and offset
		$sql_limit = "";
		if(isset($limit)){
			$sql_limit = " LIMIT ".$limit["offset"].",".$limit["limit"];
		}

		$joins = "";
		if(isset($joinkeys)){
			foreach($joinkeys AS $tables => $value){
				$iter = 0;
				$table = array();
				$id = "";
				foreach($value AS $key => $subval){
					$table[$iter] = $key;
					$id = $subval;
					$iter++;
				}

				$joins .= " LEFT JOIN ".$table[0]." ON ".$table[0].".".$id." = ".$table[1].".".$id;
				unset($table);
			}
		}

		$data_sql = "SELECT SQL_NO_CACHE SQL_BIG_RESULT ".$sql_fields." FROM ".$maintable.$joins.$sql_where.$sql_and.$sql_or.$sql_order.$sql_group.$sql_limit;

		if(!$res_browse = DB::$conn->query($data_sql)){
			die(\OPF\Core\Errors::returnError("db_wrong_getQuery_statement",$data_sql));
		}

		$returns = array();
		while($data = $res_browse->fetch_assoc()){
			$returns[] = $data;
		}
		return $returns;
	}

	/**
	 *
	 * INSERT data into a Table
	 *
	 * @param string $table		The name of the Table to insert into
	 * @param array  $dataArray	Array of fieldnames and values to INSERT
	 *
	 * @return int	 $ret_id	The ID of the inserted row
	 */
	function queryInsert($table,$dataArray){
		$sql_start = "INSERT IGNORE INTO ".$table;
		$values = "";
		$fields = "";

		foreach($dataArray AS $key => $value){
			$fields .= $this->escape_string($key).",";
			$values .= "'".$this->escape_string($value)."',";
		}

		$values = rtrim($values,",");
		$fields = rtrim($fields,",");

		$sql = $sql_start." (".$fields.") VALUES(".$values.")";

		if(!DB::$conn->query($sql)){
			die(\OPF\Core\Errors::returnError("db_wrong_setQuery_statement",$sql));
		}
		
		$last_id = mysqli_insert_id(DB::$conn);
		return $last_id;
	}

	/**
	 *
	 * UPDATE Data into Table
	 *
	 * @param  string $table				Name of the table we're working on
	 * @param  array  $dataArray			fieldnames and values to write
	 * @param  array  $addWhereArray		Declaration of where we want to write the data to
	 *
	 * @return int	  $last_id				The ID of the last primary Key we wrote
	 */
	function queryUpdate($table,$dataArray,$addWhereArray){
		$sql_start = "UPDATE ".$table." SET ";
		$updater = "";

		foreach($dataArray AS $key => $value){
			$updater .= $this->escape_string($key)." = '".$this->escape_string($value)."',";
		}

		$updater = rtrim($updater,",");

		$addWhere = " WHERE ";
		foreach($addWhereArray AS $key => $value){

			if(is_numeric($value)){
				$operator = " = ";
			} else {
				$operator = " LIKE ";
			}

			$addWhere .= $key.$operator."'".$this->escape_string($value)."' AND ";
		}

		$addWhere = rtrim($addWhere," AND ");

		$sql = $sql_start.$updater.$addWhere;

		if(!DB::$conn->query($sql)){
			die(\OPF\Core\Errors::returnError("db_wrong_setQuery_statement",$sql));
		}
		
		$last_id = mysqli_insert_id(DB::$conn);
		return $last_id;
	}

	/**
	 *
	 * SELECT a single row from a Table
	 *
	 * @param string $table		Name of the Table were going to query on
	 * @param array	 $fieldlist	List of fields we are going to operate on
	 * @param array	 $where		WHERE fieldnames and values
	 * @param array	 $sqlAnds	AND fieldnames and values
	 * @param array	 $sqlOrs	OR fieldnames and values
	 *
	 * @return	array $row		The Table row we selected
	 */
	function querySingleRow($table,$fieldlist,$where,$sqlAnds,$sqlOrs){

		//where param
		$addWhere = "";
		if(is_array($where)){
			$addWhere = " WHERE ";
			foreach($where AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$addWhere .= $key.$operator."'".$this->escape_string($value)."'";
			}
		}

		//every extra param that comes with an AND
		$addAnds = "";
		if(is_array($sqlAnds)){
			$addAnds = " AND ";
			foreach($sqlAnds AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$addAnds .= $key.$operator."'".$this->escape_string($value)."' AND ";
			}
			$addAnds = rtrim($addAnds," AND ");
		}

		//sames for OR
		$addOrs = "";
		if(is_array($sqlOrs)){
			$addOrs = " OR ";
			foreach($sqlOrs AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$addOrs .= $key.$operator."'".$this->escape_string($value)."' OR ";
			}
			$addOrs = rtrim($addOrs," OR ");
		}

		//and here we build a query...
		$sql = "SELECT ".$fieldlist." FROM ".$table.$addWhere.$addAnds.$addOrs;
		
		if(!$res = DB::$conn->query($sql)){
			die(\OPF\Core\Errors::returnError("db_wrong_getQuery_statement",$sql));
		}
		
		$row = $res->fetch_row();
		return $row;
	}

	/**
	 *
	 * SELECT all rows of a table
	 *
	 * @param string $table		name of the table to query from
	 * @param array  $fieldlist	List of fieldnames to fetch
	 * @param array  $where		The WHERE statement
	 * @param array  $sqlAnds	The AND statement
	 * @param array  $sqlOrs	The OR statement
	 * @param array  $sortvalue	ORDER BY fieldnames
	 * @param array  $sortorder	ORDER BY type (ASC/DESC)
	 *
	 * @return array $results	The Resulting rows as array
	 */
	function queryFullData($table,$fieldlist="*",$where,$sqlAnds,$sqlOrs,$sortvalue="",$sortorder="DESC"){
		$sort_sql = "";
		if($sortvalue != ""){
			$sort_sql = " ORDER BY ".$sortvalue." ".$sortorder;
		}

		$where_sql = "";
		if(is_array($where)){
			$where_sql = " WHERE ";
			foreach($where AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$where_sql .= $key.$operator."'".$this->escape_string($value)."'";
			}
		}

		$addAnds = "";
		if(is_array($sqlAnds)){
			$addAnds = " AND ";
			foreach($sqlAnds AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$addAnds .= $key.$operator."'".$this->escape_string($value)."' AND ";
			}
			$addAnds = rtrim($addAnds," AND ");
		}

		$addOrs = "";
		if(is_array($sqlOrs)){
			$addOrs = " OR ";
			foreach($sqlAnds AS $key => $value){
				if(is_numeric($value)){
					$operator = " = ";
				} else {
					$operator = " LIKE ";
				}
				$addOrs .= $key.$operator."'".$this->escape_string($value)."' OR ";
			}
			$addOrs = rtrim($addOrs," OR ");
		}

		$space = " ";

		$sql = "SELECT ".$fieldlist." FROM ".$table.$where_sql.$space.$addAnds.$space.$addOrs.$sort_sql;
		
		if(!$res = DB::$conn->query($sql)){
			die(\OPF\Core\Errors::returnError("db_wrong_getQuery_statement",$sql));
		}
		
		$results = array();
		while($row = $res->fetch_row()){
			$results[] = $row;
		}
		return $results;
	}

	/**
	 *
	 * Count rows in a table
	 *
	 * @param string $field		the field to count on
	 * @param string $table		the table to count on
	 *
	 * @return array $row		$row[0] -> total number of rows in table
	 */
	function queryCountRows($field,$table){
		$sql = "SELECT COUNT(".$field.") FROM ".$table;
		$res = DB::$conn->query($sql);

		if($res){
			$row = $res->fetch_row();
			return $row;
		}
	}

	/**
	 *
	 * Parameter cleanup for writing into a database
	 *
	 * @param   string $string			the data to cleanup
	 *
	 * @return	string $resultstring	the cleaned string
	 */
	function escape($string){
		$resultstring = DB::$conn->real_escape_string($string);
		return $resultstring;
	}
}
?>
