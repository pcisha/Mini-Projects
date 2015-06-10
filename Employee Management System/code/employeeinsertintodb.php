<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
</head>

<style type="text/css">
body {
	height: 750px;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
margin: 1%;
}



table {

padding:10px 200px;
background:#FFFFFF;
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>

<body>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>



   <div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>






 <div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Employee Information</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Employee Information </strong></font></p>
</div>
<?php
session_start();
if(isset($_SESSION['logged_in']))
{
	//echo "wjebgwrgbjrwgjb".$_SESSION['usr_role'];
	if($_SESSION['logged_in']!='1')
	{
		header("Location: login.html");
	}
}
else
{
	header("Location: login.html");
}

  $name = $_POST['firstname'] . ' ' . $_POST['lastname'];
  $address = $_POST['address'];
  $phoneno = $_POST['phoneno'];
  $email = $_POST['email'];
  $designation = $_POST['de'];
  $location = $_POST['place'];
  
  //echo "name is".$name."</br>";
  //echo "address is".$address."</br>";
  //echo "phoneno is".$phoneno."</br>";
  //echo "email is".$email."</br>";
  //echo "designation is".$designation."</br>";
  //echo "location is".$location."</br>";
  

 $dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
   or die('Error connecting to MySQL server.');

	if($designation=="4")
	{
		
		$hrid=$_SESSION['employee_id'];
	$query = "INSERT INTO employee (name, address, phone_no, email_id,
    designation, location,deployed,sup_id,leave_bal)
    VALUES ('$name', '$address', '$phoneno', '$email', '$designation','$location','0','$hrid','100')";
	
	$result = mysqli_query($dbc, $query)
    or die('Error querying database.');
	}
	else
	{	
	//echo "its here";
	//$query1 = "INSERT INTO employee (name, address, phone_no, email_id,
    //designation, location, deployed,leave_bal,sup_id)
    //VALUES ('$name', '$address', '$phoneno', '$email', '$designation', 
    //'$location','0','100','0')";
	
	$query1 = "INSERT INTO employee (name, address, phone_no, email_id,
    designation, location, deployed,leave_bal,sup_id)
    VALUES ('$name','$address', '$phoneno', '$email', '$designation', 
    '$location','0','100','0')";
	
	
	$result = mysqli_query($dbc, $query1)
    or die('Error querying database.');
	
	}
	
  //echo "mooooooooooooo";

  $employee_id = $dbc->insert_id ;
	//echo "last insert id in first multi_query is ", $dbc->insert_id, "\n";
	//echo $employee_id;

	foreach($_POST['skills'] as $i){	
	//echo $i;
	$query1 = "INSERT INTO skills_mapping(skill_id, employee_id) VALUES ('$i', '$employee_id')";

  $result = mysqli_query($dbc, $query1)
	  or die('Error querying database.');
  //echo "here";
  //echo $result;
	}
  mysqli_close($dbc);

  //echo 'Thanks for submitting the form. The Employee ID is ' . $employee_id . "\n";
	echo "<p align=center><font color='#FFFFFF' face='courier' size='5'><strong>Employee Inserted successfully</strong></font></p>";
  //echo ' THe value returned from DB is : ' . $result . '<br />';

?>

</body>
</html>

