<?php
	$emapid = $_GET['username'];

	include'db_conn.php';
	//$host="localhost"; // Host name 
	//$username="root"; // Mysql username 
	//$password="password"; // Mysql password 
	//$db_name="employeemanagement"; // Database name                            
	mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");
//echo $emapid;

	$result = mysql_query("SELECT * FROM authenticate WHERE user_id='$emapid'");
$num_rows = mysql_num_rows($result);

if ($num_rows > 0) {
	$output =  array('query'=>'success');
  // do something
}
else {
  // do something else
  $output =  array('query'=>'nosuccess');
}
	
	echo json_encode($output,JSON_FORCE_OBJECT);
 
	mysql_close();
?>