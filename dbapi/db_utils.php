<?php

class DatabaseException extends Exception{}
class NullabilityException extends DatabaseException{}
class InvalidColumnValueMatchException extends DatabaseException{}
class InvalidColumnValuePairMatchException extends DatabaseException{}

class db_utils{
	
	private $hack = false;
	private $db;
	public function __destruct(){
		
	}
	public function __construct($host,$user,$password,$database){ 
		require_once 'db_connect.php';
		$link = new database($host,$user,$password,$database);	
		$this->db = $link->open_database_connection();
	}
	public function drop_table($table,$printSQL){
		if(empty($table)){
			throw new NullabilityException("The table name cannot be null");
		}
		
		$query = "drop table `".$table."`";
		
		if($printSQL){
			echo $query;
		}
		$this->db->query($query);
	}
	
	/**
	 * @desc Checks whether a certain record exitst in a relation
	 * @param string Table name
	 * @param array columns
	 * @param array records
	 * @return integer number of rows found
	 */
	public function is_exists($table,Array $columns,Array  $records,$printSQL){
		
	if(empty($table)||count($columns)==0||count($records)==0){
		if(empty($table)){
			throw new NullabilityException("The table name cannot be null");
		}
		if((count($columns)==0)){
			throw new NullabilityException("The table columns cannot be none");
		}
		if((count($records)==0)){
			throw new NullabilityException("The column records cannot be none");
		}
		
	}

	$num_rows = 0;
	
	$query = "SELECT * FROM `".$table."` WHERE ";
	
	if(count($columns)==count( $records)){
		for($x=0;$x<count($columns);$x++){
			$query .= " `".$columns[$x]."` = '". $records[$x]."' ";
			
			if($x<(count($columns)-1)){
				$query .=" AND ";
			}
		}
		
	 $query .=";"; if($printSQL){echo $query;}
		
	$rows = $this->db->query($query); //Perform Query
	$num_rows = $rows->num_rows; /*Count the Number of rows */
	return $num_rows; //Return number of rows
	}else{
		$message = "Columns are more or rows are more";
		if(count($columns)>count( $records)){
			$message = "Column count(".count($columns).") is greater than record count(".count( $records).") ";
		}else{
			$message = "Record count(".count( $records).") is greater than record column(".count($columns).") ";
		}
	   throw new InvalidColumnValueMatchException('Invalid query,'.$message);
	}
	
	return $num_rows;
	
	}
	
	/**
	 * @desc Deletes the specified record in a relation
	 * @param string Table name
	 * @param array columns
	 * @param array records
	 * @return integer 1 if success, 0 if failed
	 */
	public function delete_record($table,Array $columns,Array  $records,$printSQL){
		
		if(empty($table)||count($columns)==0||count($records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
			if((count($columns)==0)){
				throw new NullabilityException("The table columns cannot be none");
			}
			if((count($records)==0)){
				throw new NullabilityException("The column records cannot be none");
			}
		}
		
		$num_rows = 0;
		
		$query = "DELETE FROM `".$table."` WHERE ";
		
		if(count($columns)==count( $records)){
			for($x=0;$x<count($columns);$x++){
				$query .= " `".$columns[$x]."` = '". $records[$x]."' ";
				if($x<(count($columns)-1)){
					$query .=" AND ";
				}
			}
			
			 $query .=";"; if($printSQL){echo $query;}
			
			$delete =  $this->db->query($query); 
			return $delete;//Return number of deleted rows
		}else{
			$message = "";
			if(count($columns)>count( $records)){
				$message = "Column count(".count($columns).") is greater than record count(".count( $records).") ";
			}else{
				$message = "Record count(".count( $records).") is greater than record column(".count($columns).") ";
			}
			throw new InvalidColumnValueMatchException('Invalid query,'.$message);
		}
		
		return $num_rows;
	}
	
	/**
	 * @desc Deletes all records in a relation
	 * @return integer 1 if success, 0 if failed
	 */
	public function delete_all_records($table,$printSQL){
		if(empty($table)){
			throw new NullabilityException("The table name cannot be null");
		}
		
		//$query = "DELETE FROM `".$table."` WHERE 1;";
		$query = 'TRUNCATE table `'.$table.'`;';
		
		if($printSQL){ echo $query; }
		
		return $this->db->query($query);
	}
	/**
	 * @desc Inserts records in a relation
	 * @param string table name
	 * @param array columns
	 * @param array records
	 */
	public function insert_records($table,Array $columns,Array  $records,$printSQL){
		if(empty($table)||count($columns)==0||count($records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
			if((count($columns)==0)){
				throw new NullabilityException("The table columns cannot be none");
			}
			if((count($records)==0)){
				throw new NullabilityException("The column records cannot be none");
			}
		}
		
		/* $columns[count($columns)]=PARAM_TIME_ADDED;
		$records[count($records)]=time();
		
		$columns[count($columns)]=PARAM_COMMITER_ACCOUNT;
		$records[count($records)]="1"; */
		
		$query = " INSERT INTO `".$table."` (";
		
		for($x=0;$x<count($columns);$x++){
			$query .= " `".$columns[$x]."` ";
			if(($x<count($columns) - 1)){
				$query .=",";
			}
		}
		
		$query .=") VALUES (";
		
		for($x=0;$x<count($records);$x++){
			$query .= " '".$records[$x]."' ";
			if(($x<count($records) - 1)){
				$query .=",";
			}
		}
		
		 $query .=");"; if($printSQL){echo $query;}
		
		return $this->db->query($query);
	}
	/**
	 * @desc Inserts a large number of records in a relation
	 * @param string table name
	 * @param array columns
	 * @param array records
	 */
	public function bulk_insert_records($table,Array $columns, Array $records,$printSQL){
		if(empty($table)||count($columns)==0||count($records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
			if((count($columns)==0)){
				throw new NullabilityException("The table columns cannot be none");
			}
			if((count($records)==0)){
				throw new NullabilityException("The column records cannot be none");
			}
		}
		
		$query = " INSERT INTO `".$table."` (";
		
		for($x=0;$x<count($columns);$x++){
			$query .= " `".$columns[$x]."` ";
			if(($x<count($columns) - 1)){
				$query .=",";
			}
		}
		$record_count = count($records);
		$column_count = count($columns);
		
		$valueset = $record_count/$column_count;
		
		if($record_count%$column_count == 0){
			
		}else{ throw new InvalidColumnValuePairMatchException("The number of columns and record count does not match"); }
		$query .=") VALUES ";
		
		for($i=0;$i<$valueset;$i++){
			$query .="(";
			$record_set = (($i+1) * $column_count );
			for($x=($i * $column_count );$x<$record_set;$x++){
					
				$query .= " '".$records[$x]."' ";
				if(($x<($record_set-1))){
					$query .=",";
				}
			}
			if(($i<$valueset - 1)){
				$query .="),";
			}else{$query .=")";}
		}
		
		
		  $query .=";"; if($printSQL){echo $query;}
		return $this->db->query($query);
	}
	
	public function update_record($table,Array $columns, Array $records, Array $where_columns,Array $where_records,$printSQL){
		if(empty($table)||count($columns)==0||count($records)==0||count($where_columns)==0||count($where_records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
			if((count($columns)==0)){
				throw new NullabilityException("The table columns cannot be none");
			}
			if((count($records)==0)){
				throw new NullabilityException("The column records cannot be none");
			}
		}
		
		$query = " UPDATE `".$table."` SET ";
		
		for($x=0;$x<count($columns);$x++){
			$query .= " `".$columns[$x]."` = '". $records[$x]."' ";
			if($x<(count($columns)-1)){
				$query .=",";
			}
		}
		
		if(count($where_columns)>0&&count($where_records)>0){
			$query .=" WHERE ";
			
			for($x=0;$x<count($where_columns);$x++){
				$query .= " `".$where_columns[$x]."` = '". $where_records[$x]."' ";
				if($x<(count($where_columns)-1)){
					$query .=" AND ";
				}
			}
			
		} 
		
		$query .=";"; if($printSQL){echo $query;}
		
		return $this->db->query($query);
	}
	
	
	/**
	 * @desc Retrurns an associative array of the query
	 * @param string Table name
	 * @param array columns
	 * @param array records
	 * @return mysqli_object
	 */
	public function fetch_assoc($table,Array $columns,Array  $records,$printSQL){


		if(empty($table)||count($columns)==0||count($records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
		
		}
		
		
		$query ="";
		
		if((count($columns)==0) && (count($records)==0)){
			$query = "SELECT * FROM `".$table."`";
		}else {
			$query = "SELECT * FROM `".$table."` WHERE ";
		}
		
		
		
		
		if(count($columns)==count( $records)){
			for($x=0;$x<count($columns);$x++){
				$query .= " `".$columns[$x]."` = '". $records[$x]."' ";
					
				if($x<(count($columns)-1)){
					$query .=" AND ";
				}
			}
		
			 $query .=";"; if($printSQL){echo $query;}
		
			$results = array();
			$exec = $this->db->query($query); //Perform Query
			while($assoc = $exec->fetch_assoc()){
				$results[count($results)]= $assoc; //Return rows
			}
			
			return $results;
		}else{
			$message = "Columns are more or rows are more";
			if(count($columns)>count( $records)){
				$message = "Column count(".count($columns).") is greater than record count(".count( $records).") ";
			}else{
				$message = "Record count(".count( $records).") is greater than record column(".count($columns).") ";
			}
			throw new InvalidColumnValueMatchException('Invalid query,'.$message);
		}
		
		return null;
		
		
	}
	
	public function query($table,Array $columns,Array $records,$printSQL){
		return $this->fetch_assoc($table, $columns, $records,$printSQL);
	}
	
	public function search($table,Array $columns,Array $records,$printSQL){
		
		if(empty($table)||count($columns)==0||count($records)==0){
			if(empty($table)){
				throw new NullabilityException("The table name cannot be null");
			}
		
		}
		
		
		$query ="";
		
		if((count($columns)==0) && (count($records)==0)){
			$query = "SELECT * FROM `".$table."`";
		}else {
			$query = "SELECT * FROM `".$table."` WHERE ";
		}
		
		
		
		
		if(count($columns)==count( $records)){
			for($x=0;$x<count($columns);$x++){
				$query .= " `".$columns[$x]."` LIKE '%". $records[$x]."%' ";
					
				if($x<(count($columns)-1)){
					$query .=" OR ";
				}
			}
		
			 $query .=";"; if($printSQL){echo $query;}
		
			$results = array();
			$exec = $this->db->query($query); //Perform Query
			while($assoc = $exec->fetch_assoc()){
				$results[count($results)]= $assoc; //Return rows
			}
			
			return $results;
		}else{
			$message = "Columns are more or rows are more";
			if(count($columns)>count( $records)){
				$message = "Column count(".count($columns).") is greater than record count(".count( $records).") ";
			}else{
				$message = "Record count(".count( $records).") is greater than record column(".count($columns).") ";
			}
			throw new InvalidColumnValueMatchException('Invalid query,'.$message);
		}
		
		return null;
		
	}
	
	function resetAutoIncrement($table,$printSQL){
		if(empty($table)){
			throw new NullabilityException("The table name cannot be null");
		}
		$query = 'alter table `'.$table.'` set auto_increment = 1';
		if($printSQL){ echo $query;}
		$this->db->query($query);
	}
	
	function renameTable($oldname,$newname,$printSQL){
		if(empty($oldname) || empty($newname)){
			throw new NullabilityException("The table name cannot be null");
		}
		
		$query = 'RENAME TABLE `'.$oldname.'` TO `'.$newname.'`;';
		
		if($printSQL){
			echo $query;
		}
		
		return $this->db->query($query);
	}
}

?>