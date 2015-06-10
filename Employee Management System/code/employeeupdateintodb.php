<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">
body {
	height: 778px;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
}



table {
padding:10px 200px;
background:#FFFFFF;
/*border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;*/
}



</style>
  <title>Employee Information</title>
  <h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Update Results</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Employee Information </strong></font></p>
</head>

<body>
  

<?php
session_start();
$present=false;
  $name = $_POST['firstname'] . ' ' . $_POST['lastname'];
  $address = $_POST['address'];
  $phoneno = $_POST['phoneno'];
  if(isset($_POST['email']) && isset($_POST['designation']))
  {
	  $email = $_POST['email'];
  	  $designation = $_POST['de'];
	  $present=true;
  }
  
  
  
  //echo $designation;
  $location = $_POST['place'];
  
$empid=$_SESSION['employee_id'];
//echo "emp is ".$empid;

$dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
    or die('Error connecting to MySQL server.');
	

if($present==true)
{
	//echo "email is".$email."</br>";
  //echo "designation is".$designation."</br>";

	
	//echo "data present";
	
	$query = "UPDATE employee set name='$name',address='$address',phone_no='$phoneno',location='$location',email_id='$email',designation='$designation' where employee_id='$empid'";
	
	
  $result = mysqli_query($dbc, $query)
    or die('Error querying database.');
	
}
else
{
		//echo "data not present";
	
	$query = "UPDATE employee set name='$name',address='$address',phone_no='$phoneno',location='$location' where employee_id='$empid'";
	
	
 $result = mysqli_query($dbc, $query)
  or die('Error querying database.');
	
}
 
	
  
	//echo "last insert id in first multi_query is ", $dbc->insert_id, "\n";
	//echo $employee_id;

  if(!empty($_POST['skills'])) {

	  $query1 = "delete from skills_mapping where EMPLOYEE_ID='$empid'";
	  $result = mysqli_query($dbc, $query1)
	or die('Error querying database.');
 
	foreach($_POST['skills'] as $i){	
	
	$query2 = "INSERT INTO skills_mapping (skill_id, employee_id) " .
    "VALUES ('$i', '$empid')";

  $result = mysqli_query($dbc, $query2)
    or die('Error querying database.');
	}
 }
  mysqli_close($dbc);

  //echo 'Record for the Employee ID  ' . $empid . ' has been successfully updated';
  echo "<p><font color='#FFFFFF' face='courier' size='5'><strong>Record has been successfully updated</strong></font></p>";

  //echo ' THe value returned from DB is : ' . $result . '<br />';

?>

</body>
</html>

