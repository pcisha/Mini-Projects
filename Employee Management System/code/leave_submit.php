<!DOCTYPE html>
<html lang="en">
<head>
<link href="./bootstrap/css/bootstrap.css" rel="stylesheet"
	media="screen">

<title>Leave Request Form</title>

<style type="text/css">

html {
  height: 100%;
}



body {
	
	max-height: 750px;
	overflow:auto;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
}



table {
padding:10px 30px;
background:#FFFFFF;
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>



</head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resource Allocation</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='leave_reim.php'> <u><font color='#FFFFFF' face='courier'>Leave Request</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Leave Request Submission</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Leave Request Submission Results</strong></font></p>
<br/><br/>

<body>
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
	
include 'db_conn.php';
$retype = '';
//$empId = $_SESSION['empId'];
$empId = $_SESSION['employee_id'];
$approved=2;

if($_POST)
{
	
	$retype = $_POST['retype'];
	$startDate = date("Y-m-d",strtotime( $_POST['startdate']) );
	$endDate = date("Y-m-d",strtotime($_POST['enddate']));
	$reason = '';
	if(isset($_POST['reason']))
	{
	$reason = $_POST['reason'];
	}
	
	$query = "INSERT INTO `leave_emp` ( start_date, end_date, type_id, reason, approved, employee_id) VALUES ('$startDate','$endDate','".$retype."','".$reason."',$approved, $empId ) ";
	
	if(mysql_query($query)){
		echo "<font color='#FFFFFF' face='courier' size='3'>Successfully applied for leave.</font><br/><br/>";
	}
	else
	{
		echo "<font color='#FFFFFF' face='courier' size='3'>Unable to apply for leave.Try again </font><br/><br/>";
		//print_r(mysql_error());
		
	}	
}
?>
 <table width='1000' border='10' cellpadding='5' cellspacing='0' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
 
  <tr>
  
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Start Date</font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='3'>End Date</font></th>
 	<th scope='col'><font color='#1e5799' face='courier' size='3'>Status</font></th>
    
          </tr>
         
<?php 	
if($retype != ""){
	echo "<tr><td><font color='#1e5799' face='courier' size='3'><center>".$startDate."</center></td>";
	echo "<td><font color='#1e5799' face='courier' size='3'><center>".$endDate."</center></td>";
}
else
{
	print_r($_POST);
	$query = "SELECT * from `leave_emp` where employee_id =".$empId." order by leave_id desc";
	$results = mysql_query($query);
	print_r($results);
	while ($row = mysql_fetch_array($results, MYSQL_ASSOC))
	{
		echo "<tr><td><font color='#1e5799' face='courier' size='3'><center>".$row['start_date']."</center></font></td>";
		echo "<td><font color='#1e5799' face='courier' size='3'><center>".$row['end_date']."</center></font></td>";
		$approved = $row['approved'];
		break;
	}	
	
}	
$query1="select re_desc from reimbursed_status where re_id=".$approved."";
$results1 = mysql_query($query1);
$row1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	echo "<td><font color='#1e5799' face='courier' size='3'><center>".$row1['re_desc']."</center></font></td></tr>";
echo "</table>";

?>



</body>
</html>
