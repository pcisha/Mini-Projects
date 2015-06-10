<!DOCTYPE html>
<html lang="en">
<head>
<link href="./bootstrap/css/bootstrap.css" rel="stylesheet"
	media="screen">

<title>Expense History</title>

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
</head>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Expenses History</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Expenses History</strong></font></p>
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
/*$host="localhost"; // Host name
$username="root"; // Mysql username
$password=""; // Mysql password
$db_name="employeemanagement"; // Database name
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");
session_start();*/
include 'db_conn.php';
$empId=$_SESSION['employee_id'];
echo "<table table width='60%' border='20' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'><tr><th>EXPENSE ID</th><th>CLAIM DATE</th><th>CLAIM AMOUNT</th><th>REIMBURSED</th></tr> ";
//$empId = $_SESSION['empId'];
$query = "SELECT * FROM `expenses` WHERE employee_id = $empId ";
$results = mysql_query($query);
//print_r($results);
while($row = mysql_fetch_array($results, MYSQL_ASSOC)){
	echo "<tr><td><center>".$row['expense_id']."</center></td><td><center>".$row['claim_date']."</center></td><td><center>".$row['claim_amount']."</center></td>";
	$query1 = "select re_desc from reimbursed_status where re_id=".$row['re_id']."";
$results1 = mysql_query($query1);
$row1 = mysql_fetch_array($results1, MYSQL_ASSOC);
echo "<td><center>".$row1['re_desc']."</center></td></tr>";
}
echo "</table>";
?>
</body>
</html>