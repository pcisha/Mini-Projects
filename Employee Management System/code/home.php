<html lang="en">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">

body {
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
	background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
	background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
}
body,td,th {
	color: #FFFFFF;
}
a:link {
	color: #FFFBF0;
}
a:hover 
{font-size:9px;}

</style>

<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
<script>
$(function() {
$( "#accordion" ).accordion();
});
</script>

<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="row">
  <div class="col-md-12 ">
<h1 align="left" class="col-xs-offset-0"><strong><font face="courier">EMPLOYEE MANAGEMENT PORTAL</font></strong></h1>
</div></div>
  
<div class="row">
  <div class="col-md-12 ">
  
<?php
include 'db_conn.php';
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


if(isset($_SESSION['usr_role']))
{
	//echo "the role is".$_SESSION['usr_role'];
	echo "</br>";
	if($_SESSION['usr_role']==3)
	{
	echo"<a href='employeeinformation.php'><font color='#FFFFFF' face='courier' size='3'>Insert Employee Information</font></a>";
	echo " | ";
	echo"<a href='employeeupdation.php'><font color='#FFFFFF' face='courier' size='3'>Update Other Employee Information</font></a>";
	echo " | ";
		echo"<a href='employeeupdationEmp.php'><font color='#FFFFFF' face='courier' size='3'>Update Personal Information</font></a>";
	echo " | ";
	echo"<a href='rea.php'><font color='#FFFFFF' face='courier' size='3'>Resource Allocation</font></a>";
	echo " | ";
	echo"<a href='resourceDeallocation.php'><font color='#FFFFFF' face='courier' size='3'>Resource De-allocation</font></a>";
	echo " | ";
	echo"<a href='add project.php'><font color='#FFFFFF' face='courier' size='3'>Add New Project</font></a>";
	echo " | ";
	echo"<a href='time_c.php'><font color='#FFFFFF' face='courier' size='3'>Time Clocking</font></a>";
	echo " | ";
	echo"<a href='superviser.php'><font color='#FFFFFF' face='courier' size='3'>Approve Leaves</font></a>";
	echo " | ";
	echo"<a href='supervisorexpenses.php'><font color='#FFFFFF' face='courier' size='3'>Approve Expenses</font></a>";
	echo " | ";
	echo"<a href='repSearch.php'><font color='#FFFFFF' face='courier' size='3'>Reports</font></a>";
	echo " | ";
	echo"<a href='logout.php'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a>";
	}
	else if($_SESSION['usr_role']==4)
	{
	echo"<a href='employeeupdationEmp.php'><font color='#FFFFFF' face='courier' size='3'>Employee Information</font></a>";
	echo " | ";
	echo"<a href='time_c.html'><font color='#FFFFFF' face='courier' size='3'>Time Clocking</font></a>";
	echo " | ";
	echo"<a href='superviser.php'><font color='#FFFFFF' face='courier' size='3'>Approve Leaves</font></a>";
	echo " |";
	echo"<a href='leave_reim.php'><font color='#FFFFFF' face='courier' size='3'>Apply Leaves</font></a>";
	echo " |";
	if($_SESSION['deployed']==1)
	{
		echo"<a href='expenses_reim.php'><font color='#FFFFFF' face='courier' size='3'>Claim Expenses</font></a>";
	echo " | ";
	echo"<a href='supervisorexpenses.php'><font color='#FFFFFF' face='courier' size='3'>Approve Expenses</font></a>";
	echo " | ";
	}
	echo"<a href='repSearch.php'><font color='#FFFFFF' face='courier' size='3'>Reports</font></a>";
	echo " | ";
	echo"<a href='logout.php'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a>";
	}
	else if($_SESSION['usr_role']==2)
	{
	echo"<a href='employeeupdationEmp.php'><font color='#FFFFFF' face='courier' size='3'>Employee Information</font></a>";
	echo " | ";
	echo"<a href='time_c.php'><font color='#FFFFFF' face='courier' size='3'>Time Clocking</font></a>";
	echo " | ";
	echo"<a href='leave_reim.php'><font color='#FFFFFF' face='courier' size='3'>Apply Leaves</font></a>";
	echo " | ";
	if($_SESSION['deployed']==1)
	{
	echo"<a href='expenses_reim.php'><font color='#FFFFFF' face='courier' size='3'>Claim Expenses</font></a>";
	echo " | ";
	}
	echo"<a href='logout.php'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a>";
	}
}

?>

</div></div>
<div class="page-header">
  <h2><font color='#FFFFFF' face='courier'>Welcome <?php echo $_SESSION['name']; 
  ?>,</font>
  </h2>
  
</div>


<div id="accordion">
      <h3><strong>Employee Information</strong></h3>
     <div> <p>Name:
  <?php
    
if(isset($_SESSION['user_id']))
{
	
	
	$usr=$_SESSION['user_id'];

    $sql=mysql_query("SELECT name FROM employee WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id ='$usr')"); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['name'];
	}

}	
 	
    ?>
    
    
   <p> Address: 
      <?php
    
    

    $sql=mysql_query("SELECT address FROM employee WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id = '$usr')"); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['address'];
	}
	
    ?>
   </p>
  <p>Phone: 
      <?php
    
 
    $sql=mysql_query("SELECT phone_no FROM employee WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id = '$usr' )"); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['phone_no'];
	}
	
    ?>
    </p>
   
     <p>Email id: 
      <?php
    
  

    $sql=mysql_query("SELECT email_id FROM employee WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id = '$usr')"); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['email_id'];
	}
	
    ?>
    </p> </div>
    
  
    <h3><strong>Leave Information</strong></h3>
     <div> <p>Leave Balance:
  <?php
    
  
    $sql=mysql_query("SELECT leave_bal FROM employee WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id = '$usr' )"); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['leave_bal'];
	}
	
    ?>
     </p></div> 
    
   
    <h3><strong>Expenses Information</strong></h3>
     <div> <p>Expenses Claimed: $
  <?php
    
 
    $sql=mysql_query("SELECT sum(claim_amount) tcm FROM `expenses` WHERE employee_id = (SELECT employee_id FROM authenticate WHERE user_id = '$usr' ) "); 
    while($row=mysql_fetch_array($sql))
	{
	echo $row['tcm'];
	}
	
    ?>
      </p></div>
   
     <h3><strong>Time Information</strong></h3>
     <div> <p>Clocked in since: 
  <?php
    
  
    $sql=mysql_query("select max(clock_in_time) cit from time_clocking where clock_out_time is NULL and employee_id=(SELECT employee_id FROM authenticate WHERE user_id = '$usr' )"); 
	
    while($row=mysql_fetch_array($sql))
	{
	echo $row['cit'];
	}
	
    ?>
</p></div>

</body>
</html>
