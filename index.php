<?php

include 'dbapi/db_utils.php';

$host = "localhost"; $user = "root"; $password = ""; $database ="dbapi";
$dbutils = new db_utils($host, $user, $password, $database);



//Insert Data
$table = "test";
$columns = array("name");
$records = array("Victor");
$dbutils->insert_records($table, $columns, $records,true);

//BULK INSERT
$columns = array("name");
$records = array("Mwenda","Marvik","Victor");
$dbutils->bulk_insert_records($table, $columns, $records,true);


//UPDATE DATA
$where_columns = array("name");
$where_records = array("Mwenda");
$columns = array("name");
$records = array("Vicky");
$dbutils->update_record($table, $columns, $records, $where_columns, $where_records,true);


//DELETE_DATA
$columns = array("name");
$records = array("Marvik");
$dbutils->delete_record($table, $columns, $records,true);


//COMPLEX METHODS

//QUERY && FETCH ASSOC
$columns = array("name");
$records = array("Vic");
$results = $dbutils->fetch_assoc($table, $columns, $records,true);
for($i = 0;$i<count($results);$i++){
	echo $results[$i]['name'];
}


//Search
$columns = array("name");
$records = array("Mw");
$results = $dbutils->search($table, $columns, $records,true);
for($i = 0;$i<count($results);$i++){
	//echo $results[$i]['name'];
}

?>