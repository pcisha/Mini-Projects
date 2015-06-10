<!DOCTYPE html>
<html lang="en">
<head>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<title>Pending Leave Requests</title>


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
	$approved = $_POST['approved'];
	$leaveId = $_POST['leaveId'];
	//echo "Updating";
	$query = mysql_query("UPDATE `leave_emp` SET approved = ".$approved." WHERE leave_id = '".$leaveId."'");
	$days=$_POST['grantDays'];
	$leaveblnc = $_POST['leaveblnc'];
	//echo $leaveblnc;
	$leaveblnc=$leaveblnc-$days;
	//echo "Leave granted for ".$days." days. leave bal = ".$leaveblnc." days.";
	$empId = $_POST['empId'];
	echo "<font color='FFFFFF'>"."emp id is".$empId;
	$query2 = mysql_query("UPDATE `employee` SET leave_bal = ".$leaveblnc." WHERE employee_Id = ".$empId);
}
?>
<script type="text/javascript">
function doSubmit(leaveId, status, empid, leavebal, days)
{
	//alert('Submitting');
	var leaveIdField = document.approveForm.leaveId ;
	var approved = document.approveForm.approved ;
	document.approveForm.leaveId.value = leaveId;
	document.approveForm.approved.value = status;
	document.approveForm.empId.value = empid;
	document.approveForm.leaveblnc.value = leavebal;
	document.approveForm.grantDays.value = days;
	document.approveForm.submit();		
}
</script>
<form action="superviser.php" name="approveForm" method="post">

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Pending Leave Approvals</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Pending Leave Approvals</strong></font></p>
<br/><br/>






<table table width='100%' border='20' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
 
  <tr>
  <th scope='col'><font color='#1e5799' face='courier' size='5'><strong>Employee Name</strong></font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='5'><strong>Start Date</strong></font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='5'><strong>End Date</strong></font></th>
	<th scope="col"><font color='#1e5799' face='courier' size='5'><strong>Leave Balance</strong></font></th>
	<th scope="col"><font color='#1e5799' face='courier' size='5'><strong>Days Applied</strong></font></th>
 	<th scope='col' colspan="2"><font color='#1e5799' face='courier' size='5'><strong>APPROVE/REJECT</strong></font></th>
    
          </tr>
<input type="hidden" name="leaveId"/>
<input type="hidden" name="approved"/>
<input type="hidden" name="empId"/>
<input type="hidden" name="leaveblnc"/>
<input type="hidden" name="grantDays"/>        
<?php 
$supervisorId=$_SESSION['employee_id'];
$query = "select leave_emp.leave_id,leave_emp.start_date as StartDate,leave_emp.end_date as EndDate,employee.name as EmpName, employee.employee_id as Id, employee.leave_bal, leave_emp.end_date - leave_emp.start_date as days from `leave_emp`,`employee` 
where leave_emp.approved=2 and employee.employee_Id=leave_emp.employee_Id 
and employee.employee_Id in( select employee_id from employee where sup_id=".$supervisorId.")";
$results=mysql_query($query);
while ($row = mysql_fetch_array($results, MYSQL_ASSOC))
{
	echo "<tr><td><font color='#1e5799' face='courier' size='4'><center>".$row['EmpName']."</center></font>";
	echo"</td><td><font color='#1e5799' face='courier' size='4'><center>".$row['StartDate']."</center></font>";
	echo"</td><td><font color='#1e5799' face='courier' size='4'><center>".$row['EndDate']."</center></font></td>";
	
	echo "<td><font color='#1e5799' face='courier' size='4'><center>".$row['leave_bal']."</td>";
	echo "<td><font color='#1e5799' face='courier' size='4'><center>".$row['days']."</td>";
	echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['leave_id'].",0,".$row['Id'].",".$row['leave_bal']." ,".$row['days'].")'  style='height: 30px; width: 150px;margin-left:35px; color: #1e5799'>Approve</button>";
	echo "<td><button class='btn btn-primary' type='button' onclick='doSubmit(".$row['leave_id'].",1,".$row['Id'].",".$row['leave_bal'].", 0)' style='height: 30px; width: 150px;margin-left:35px; color: #1e5799'>Reject</button></tr>";	

}
?>

</table>
</form>
</body>

</html>