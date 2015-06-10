<?php
  $username = $_GET['username'];
  $password = $_GET['password'];
  $secques = $_GET['secques'];
  $secans = $_GET['secans'];
  $designation = $_GET['designation'];
  $empid = $_GET['employeeid'];
  
 $dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
   or die('Error connecting to MySQL server.');

	$query = "INSERT INTO authenticate (user_id,password,sec_qstn,sec_ans,designation_id,employee_id)
    VALUES ('$username', '$password', '$secques', '$secans', '$designation','$empid')";
	
	$result = mysqli_query($dbc, $query)
    or die('Error querying database.');

	if($result){
		$output =  array('query'=>'success');
    }
	
	else
	{
		$output =  array('query'=>'failure');
	}
	
	
	echo json_encode($output,JSON_FORCE_OBJECT);
	
  //echo "mooooooooooooo";

  mysqli_close($dbc);

  ?>