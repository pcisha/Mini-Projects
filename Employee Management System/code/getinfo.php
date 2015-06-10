<?php
	$search = $_GET['search'];
	session_start();
	$dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
    or die('Error connecting to MySQL server.');

  $query = "SELECT * FROM employee WHERE employee_id=$search";

  $result = mysqli_query($dbc, $query)
    or die('Error querying database.');
	
	$row = $result->fetch_array();
	
	
	$output =  array('employeeid'=>$row['employee_id'],
                 'name'=>$row['name'],
				 'address'=>$row['address'],
				 'phonenumber'=>$row['phone_no'],
				 'email'=>$row['email_id'],
				 'designation'=>$row['designation'],
				 'place'=>$row['location']);
	
	echo json_encode($output,JSON_FORCE_OBJECT);
	mysqli_close($dbc);
?>