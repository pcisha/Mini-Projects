<!DOCTYPE html>
<html lang="en">
<head>
<link href="./bootstrap/css/bootstrap.css" rel="stylesheet"
	media="screen">

<title>Expenses Submission</title>

<!-----css------->
<style type="text/css">
body {
	height: 729px;
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

<body>

<!--header-->

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='expenses_reim.php'><font color='#FFFFFF' face='courier' size='3'>Expenses Reimbursements</font></a></font><font color='#FFFFFF' face='courier' size='3'> | Submission Results</font></p>
 </div>
 <br/>
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Expense Reimbursements Submission Results</strong></font></p>
<br/><br/>
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
//$host="localhost"; // Host name
//$username="root"; // Mysql username
//$password=""; // Mysql password
//$db_name="employeemanagement"; // Database name
//mysql_connect("$host", "$username", "$password")or die("cannot connect");
//mysql_select_db("$db_name")or die("cannot select DB");
//session_start();
include 'db_conn.php';
$retype = '';
//$empId = $_SESSION['empId'];
$empId = $_SESSION['employee_id'];
$reimbursed=2;

if($_POST)
{
	
	$retype = $_POST['retype'];
	$ClaimDate = date("Y-m-d",strtotime( $_POST['ClaimDate']) );
	$ClaimAmt = ($_POST['ClaimAmount']);
	$reason = '';
	if(isset($_POST['reason']))
	{
	$reason = $_POST['reason'];
	}
	
	$query = "INSERT INTO `expenses` ( claim_date, claim_amount, claim_type, comments, re_id, employee_id) VALUES ('$ClaimDate','$ClaimAmt','".$retype."','".$reason."',$reimbursed, $empId ) ";
	
	if(mysql_query($query)==1){
		echo "<font color='#FFFFFF' face='courier' size='4'>Successfully claimed expense.</font><br/>";
	}
	else
	{
		echo "<font color='#FFFFFF' face='courier' size='4'>Unable to claim.Try again.</font><br/>";
		print_r(mysql_error());
		
	}	
}
?>







 <table width='1000' border='10' cellpadding='2' cellspacing='2' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
 
  <tr>
  
    <th scope='col'><font color='#1e5799' face='courier' size='3'><strong>Claim Date</strong></font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='3'><strong>Claim Amount</strong></font></th>
 	<th scope='col'><font color='#1e5799' face='courier' size='3'><strong>Status</strong></font></th>
    
          </tr>
         
<?php 	
if($retype != ""){
	echo "<tr><td>".$ClaimDate."</td><td>".$ClaimAmt."</td>";
}
else
{
	print_r($_POST);
	$query = "SELECT * from `expenses` where employee_id =".$empId." order by expense_id desc";
	$results = mysql_query($query);
	//print_r($results);
	while ($row = mysql_fetch_array($results, MYSQL_ASSOC))
	{
		echo "<tr>";
		echo "<td><center>";
		echo $row['claim_date'];
		echo "</center></td>";
		echo "<td><center>";
		echo $row['claim_amount'];
		echo "</center></td>";
		echo $reimbursed = $row['reimbursed'];
		echo "</tr>";
		break;
	}	
	
}	
$query1="select re_desc from reimbursed_status where re_id=".$reimbursed."";
$results1 = mysql_query($query1);
$row1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	echo "<td><center>";
	echo $row1['re_desc'];
	echo "</center></td>";
echo "</table>";
?>



</body>
</html>

