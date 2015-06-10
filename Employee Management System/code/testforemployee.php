<?php
	$emapid = $_GET['employeeid'];

	include 'db_conn.php';
	//$host="localhost"; // Host name 
	//$username="root"; // Mysql username 
	//$password="password"; // Mysql password 
	//$db_name="employeemanagement"; // Database name                            
	mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");


	$query="select count(*) from employee where employee_id=$emapid";
	$sql=mysql_query($query);
	$row = mysql_fetch_array($sql);
	
	if($row[0]==1)
	{
		$query2="select designation from employee where employee_id=$emapid";
		$sql2=mysql_query($query2);
		$row2 = mysql_fetch_array($sql2);
		$output =  array('user'=>'exists',
                 'empid'=>$emapid,
				 'designation'=>$row2[0]);
		
	}
	else
	{
		$output =  array('user'=>'notexists',
                 'empid'=>$emapid,
				 'designation'=>'None');
	}
	
	echo json_encode($output,JSON_FORCE_OBJECT);
 
	mysql_close();
?>