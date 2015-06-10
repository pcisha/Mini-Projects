<!DOCTYPE html>
<html lang="en">
<head>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<title>Pending Claim Requests</title>

<script type="text/javascript">
function doSubmit(expenseId, status, empid, expenseblnc, amount)
{
	//alert(z);
	var expenseIdField = document.approveForm.expenseId ;
	var reimbursed = document.approveForm.reimbursed ;
	document.approveForm.expenseId.value = expenseId;
	document.approveForm.reimbursed.value = status;
	document.approveForm.empId.value = empid;
	document.approveForm.expenseblnc.value = expenseblnc;
	document.approveForm.grantAmount.value = amount;
	document.approveForm.submit();		
}
</script>

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
<body>
<?php
//$host="localhost"; // Host name
//$username="root"; // Mysql username
//$password=""; // Mysql password
//$db_name="employeemanagement"; // Database name
//mysql_connect("$host", "$username", "$password")or die("cannot connect");
//mysql_select_db("$db_name")or die("cannot select DB");
//session_start();
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
if($_POST)
{
	$reimbursed = $_POST['reimbursed'];
//echo "reimbursed".$reimbursed;
	$claimId = $_POST['expenseId'];
	//echo "Updating";
	$query = mysql_query("UPDATE `expenses` SET re_id = ".$reimbursed." WHERE expense_id = '".$claimId."'");
	$amount=$_POST['grantAmount'];
	$expenseblnc = $_POST['expenseblnc'];
	//echo $leaveblnc;
	$expenseblnc=$expenseblnc-$amount;
	//echo "$expenseblnc";
	//echo "Leave granted for ".$days." days. leave bal = ".$leaveblnc." days.";
	$empId = $_POST['empId'];
	//echo"empId".$empId;
	$q=mysql_query("select project_id from `works_on` where employee_id=".$empId);
	$inside=false;
	while ($row = mysql_fetch_array($q))
	{
		$inside=true;
		//echo $row['project_id'];
		//echo "inside works on part";
	$query2 = mysql_query("UPDATE `project` SET budget = ".$expenseblnc." WHERE project_id =(select project_id from `works_on` where employee_id= ".$empId.")");
	}
	
	
	//echo "abc".$q;
	/*if(mysql_query("select project_id from `works_on` where employee_id=".$empId) == 1)
	{
	echo "inside works on part";
	$query2 = mysql_query("UPDATE `project` SET budget = ".$expenseblnc." WHERE project_id =(select project_id from `works_on` where employee_id= ".$empId.")");
	}*/
	//else 
	if($inside==false)
	{
		$query2 = mysql_query("UPDATE `project` SET budget = ".$expenseblnc." WHERE project_id =(select project_id from `manages` where employee_id= ".$empId.")");
	}
else {}

}
?>
<!--<script type="text/javascript">
function doSubmit(expenseId, status, empid, expenseblnc, amount)
{
	//alert(z);
	var expenseIdField = document.approveForm.expenseId ;
	var reimbursed = document.approveForm.reimbursed ;
	document.approveForm.expenseId.value = expenseId;
	document.approveForm.reimbursed.value = status;
	document.approveForm.empId.value = empid;
	document.approveForm.expenseblnc.value = expenseblnc;
	document.approveForm.grantAmount.value = amount;
	document.approveForm.submit();		
}
</script>-->
<form action="supervisorexpenses.php" name="approveForm" method="post">

<!----header------->

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Pending Expense Reimbursement Approvals</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Pending Expense Reimbursement Approvals</strong></font></p>
<br/><br/>


<table width='90%' border='10' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
 
  <tr>
  <th scope='col'><font color='#1e5799' face='courier' size='5'><strong>Employee Name</strong></font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='5'><strong>Claim Date</strong></font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='5'><strong>Claim Amount</strong></font></th>
	<th scope="col"><font color='#1e5799' face='courier' size='5'><strong>Expenses Balance</strong></font></th>
	 
 	<th scope='col' colspan="2"><font color='#1e5799' face='courier' size='5'><strong>APPROVE/REJECT</strong></font></th>
              </tr>
<input type="hidden" name="expenseId"/>
<input type="hidden" name="reimbursed"/>
<input type="hidden" name="empId"/>
<input type="hidden" name="expenseblnc"/>
<input type="hidden" name="grantAmount"/>        
<?php 
$supervisorId=$_SESSION['employee_id'];;
$query = "select expenses.expense_id,expenses.claim_date as ClaimDate,expenses.claim_amount as claimAmount, employee.employee_id as Id,employee.name as name
from `expenses`,`employee`
where expenses.re_id=2 and employee.employee_id=expenses.employee_id
and employee.employee_id in( select employee_id from `employee` where sup_id=".$supervisorId.")";
$results=mysql_query($query);
while ($row = mysql_fetch_array($results, MYSQL_ASSOC))
{
	$present=0;
	echo "<tr><td>".$row['name']."</td><td>".$row['ClaimDate']."</td><td>".$row['claimAmount']."</td>";
	$q=mysql_query("select project_id from `works_on` where employee_id=".$row['Id']);
	while($row111 = mysql_fetch_array($q))
	{
	$present=1;
	$query1 =mysql_query( "select project.budget as expenseblnc
	from `project`,`works_on`
	where project.project_id=works_on.project_id
	and works_on.employee_id=".$row['Id']."");
	$row1 = mysql_fetch_array($query1, MYSQL_ASSOC);
	echo "<td>".$row1['expenseblnc']."</td>";
	//echo "<td>".$row['days']."</td>";
	echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['expense_id'].",0,".$row['Id'].",".$row1['expenseblnc']." ,".$row['claimAmount'].")'  >Approve</button>";
	echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['expense_id'].",1,".$row['Id'].",".$row1['expenseblnc'].", 0)' >Reject</button></tr>";	
	}
	
	if($present==0)
	{
		$query1 =mysql_query( "select project.budget as expenseblnc
				from `project`,`manages`
				where project.project_id=manages.project_id
				and manages.employee_id=".$row['Id']."");
				$row1 = mysql_fetch_array($query1, MYSQL_ASSOC);
				echo "<td>".$row1['expenseblnc']."</td>";
				//echo "<td>".$row['days']."</td>";
				echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['expense_id'].",0,".$row['Id'].",".$row1['expenseblnc']." ,".$row['claimAmount'].")'  >Approve</button>";
				echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['expense_id'].",1,".$row['Id'].",".$row1['expenseblnc'].", 0)' >Reject</button></tr>";
}
}
?>

</table>
</form>
</body>

</html>
