<!DOCTYPE html>
<html lang="en">
<head>
<link href="./bootstrap/css/bootstrap.css" rel="stylesheet"
	media="screen">

<title>Leave Request Form</title>

<style type="text/css">

html {
  height: 5000px;
}



body {
	
	max-height: 10000px;
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

<title>Leave History</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'>Home</font></a> <font color='#FFFFFF' face='courier' size='3'>|</font> <a href='leave_reim.php'><font color='#FFFFFF' face='courier' size='3'>  </font><font color='#FFFFFF' face='courier' size='3'>Request Leave</font></a><font color='#FFFFFF' face='courier' size='3'> | Leave History</font></p>
 </div>
 
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Leave History</strong></font></p>
<br/><br/>


</head>

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
//session_start();
$empId=$_SESSION['employee_id'];
echo "<table width='100%' border='20' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'><tr><th><font color='#1e5799' face='courier' size='5'><strong>LEAVE ID</strong></font></th><th><font color='#1e5799' face='courier' size='5'><strong>START DATE</strong></font></th><th><font color='#1e5799' face='courier' size='5'><strong>END DATE</strong></font></th><th><font color='#1e5799' face='courier' size='5'><strong>APPROVAL STATUS</strong></font></th></tr>";
//$empId = $_SESSION['empId'];
$query = "SELECT * FROM `leave_emp` WHERE employee_id = '$empId' ";
$results = mysql_query($query);
//print_r($results);
while($row = mysql_fetch_array($results, MYSQL_ASSOC)){
	echo "<tr>";
	echo"<td><font color='#1e5799' face='courier' size='4'><center>".$row['leave_id']."</center></font></td>";
	echo"<td><font color='#1e5799' face='courier' size='4'><center>".$row['start_date']."</center></font></td>";
	echo"<td><font color='#1e5799' face='courier' size='4'><center>".$row['end_date']."<center></font></td>";
$approved=$row['approved'];
$query1 = "select re_desc from reimbursed_status where re_id=".$approved."";
$results1 = mysql_query($query1);
$row1 = mysql_fetch_array($results1, MYSQL_ASSOC);
echo "<td><font color='#1e5799' face='courier' size='4'><center>".$row1['re_desc']."</center></font></td></tr>";
}

echo "</table>";
?>
</body>
</html>