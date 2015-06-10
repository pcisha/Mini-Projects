<?php
	$search = $_GET['search'];
	session_start();
	include 'db_conn.php';
	//$host="localhost"; // Host name 
	//$username="root"; // Mysql username 
	//$password=""; // Mysql password 
	//$db_name="employeemanagement"; // Database name
	//$db_name="employeemanagement"; // Database name                            

	mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");

	$query = "SELECT skill_id FROM skills_mapping WHERE employee_id=$search";
	$sql = mysql_query($query);

	$skillRow=array();
	//echo "$sql";
	$i=0;
	while($row = mysql_fetch_array($sql))
	{	
  		//echo"inside while"."</br>";
		$skill=$row[0];
		//echo $skill;	
		$skillRow[$i]=$skill;
		$i=$i+1;
  
	}
	echo json_encode($skillRow,JSON_FORCE_OBJECT);
	mysql_close();
	
?>