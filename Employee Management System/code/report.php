<html>
<title>
Reports
</title>
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">
body {
	height: 200%;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
margin: 2%;
}



table {

padding:20px 200px;
background:#FFFFFF;
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>

</head>
<body>
<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>

   <div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

 <div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Reports</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Reports </strong></font></p>
<div class="container">

<?php

/*$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password="password"; // Mysql password 
$db_name="employeemanagement"; // Database name                            
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");*/
///////////////////////////////////////////////////

/*$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password="password"; // Mysql password 
$db_name="employeemanagement"; // Database name                            
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");*/

session_start();
if(isset($_SESSION['logged_in']))
{
}
else
{
	header("Location: login.html");
}


include 'db_conn.php';

$first_filled=null;
$emp_name=$_POST['emp_name'];
$emp_id=$_POST['emp_id'];
$email_id=$_POST['email_id'];
if($_POST['emp_id']!="")
{
	$query="Select * from employee where employee_id='$emp_id'";
}
else
{	
	$query="Select * from employee where";
	
	if($_POST['emp_name']!="")
	{	
		if($first_filled==null)
		{
		$first_filled="filled";
		}
		//echo "name is not blank"."<br/>";
		$query=$query." name = '$emp_name'";
	}
	
	if($_POST['emp_id']!="")
	{
		if($first_filled==null)
		{
		$first_filled="filled";
		$query=$query." employee_id='$emp_id'";
		}
		else
		{
		$query=$query." and employee_id='$emp_id'";
		}
	//echo "emp_id is blank"."<br/>";
	
	}
	if($email_id!="")
	{	
	//echo "email_id is blank"."<br/>";
	if($_POST['email_id']==null)
	{
		$first_filled="filled";
		$query=$query." email_id='$email_id'";
	}
	else
	{
		$query=$query." and email_id='$email_id'";
	}
	
}
}
$fromDate=$_POST['fromDatepicker'];
$toDate=$_POST['toDatepicker'];
//echo date("Y-m-d", strtotime($fromDate));
//echo date("Y-m-d", strtotime($toDate));
$frmDate=date("Y-m-d", strtotime($fromDate));
$tDate=date("Y-m-d", strtotime($toDate));
//echo "from date is".$frmDate;
//echo "to date is".$tDate;
//echo "$query";
$sql=mysql_query($query);
//$empRow=array();
//echo "$sql";
//$i=0;
while($row = mysql_fetch_array($sql))
{	
	$emp_id=$row['employee_id'];
}
//echo "employee id id".$emp_id;



//////////////////////////////////////////
$name="";
$address="";
$phone="";
$email="";
$location="";
$supervisor="";
$query="SELECT * FROM employee WHERE EMPLOYEE_ID='$emp_id'";
$sql=mysql_query($query);
while($row = mysql_fetch_array($sql))
  {
	  $name=$row['name'];
	  $address=$row['address'];
	  $phone=$row['phone_no'];
	  $email=$row['email_id'];
	  $location=$row['location'];
	  $supervisor=$row['sup_id'];
  }
 
 $sup_name="SELECT name FROM employee WHERE EMPLOYEE_ID='$supervisor'";
 $sql1=mysql_query($sup_name);
 $row1=mysql_fetch_array($sql1);
 $sup_name=$row1['name'];
 
 //for expenses
 $exp_query="select * from expenses where claim_date BETWEEN '$frmDate' and '$tDate' and employee_id='$emp_id'";
 $expRow=array();
 $i=0;
 $expSum=0;
 $sqlExp=mysql_query($exp_query);
 echo "";
 while($rowExp = mysql_fetch_array($sqlExp))
  {
	  //echo "inside while";
	  $claim_date=$rowExp['claim_date'];
	  //echo "claim_date is".$claim_date."</br>";
	  $claim_amount=$rowExp['claim_amount'];
	  //echo "claim amount is".$claim_amount."</br>";;
	  $claim_type=$rowExp['claim_type'];
	  //echo "claim type is".$claim_type."</br>";;
	  
	  $expRow[$i]=array('claim_date'=>$claim_date,
						'claim_amount'=>$claim_amount,
						'claim_type'=>$claim_type);
						$expSum+=$claim_amount;
			
			
			$i=$i+1;
  }
  
  //for leave
 $leave_query="SELECT * FROM leave_emp WHERE employee_id= '$emp_id' and approved='0'";
 $leaveRow=array();
 $lcnt=0;
 $leaveSum=0;
 $sqlLeave=mysql_query($leave_query);
 while($rowLeave = mysql_fetch_array($sqlLeave))
  {
	  $start_date=$rowLeave['start_date'];
	  $end_date=$rowLeave['end_date'];
	  $type_id=$rowLeave['type_id'];
	  //find d date difference
	  $diff = (strtotime($end_date)- strtotime($start_date))/24/3600; 
	  
	  //echo "start date is".$start_date."<br>";
	  //echo "end date is".$end_date."<br>";
	  //echo "type id is".$type_id."<br>";
	  //echo "Days difference is ".$diff."<br>";
	  $leaveRow[$lcnt]=array('start_date'=>$start_date,
						'days'=>$diff,
						'leaveType'=>$type_id);
						
						$leaveSum+=$diff;
			
			
			$lcnt=$lcnt+1;
  }
  
  //for time
 $timeRow=array();
 $tcnt=0;
 $dist_date="SELECT distinct clock_in_date from time_clocking where employee_id='$emp_id'";
 $sqlDate=mysql_query($dist_date);
 $totalTimeWorked=0;
 while($rowTime = mysql_fetch_array($sqlDate))
 {
	//l echo $rowTime[0]."</br>";
	 $dt=$rowTime[0];
	 $time_query="SELECT * FROM time_clocking WHERE clock_in_date ='$dt'";
 	 $sqlTime=mysql_query($time_query);
	 $init_time=0;
	while($rowTime1 = mysql_fetch_array($sqlTime))
 	 {
	  	$time1 = strtotime($rowTime1['clock_in_time']);
	    $time2 = strtotime($rowTime1['clock_out_time']);
		$diff = $time2 - $time1;
		//echo "time1 is".$time1;
		$init_time=$init_time+$diff;
  	}
	$timeRow[$tcnt]=array('date'=>$dt,
						'time'=>$init_time,
						);
						$tcnt+=1;
	//echo "total time worked is ".date('H:i:s', $init_time)."</br>";
	$totalTimeWorked+=$init_time;
	
	//echo "tot time worked is".date('H:i:s', $totalTimeWorked)."</br>";
 }
 
//employee information
echo "<div class='row'>";  
echo"<div class='col-md-6'>";
echo "<h3><font size='5' face='courier' color='#FFFFFF'><strong>Employee Information</strong></font></h3>";
echo"<table  width='434' border='0' cellpadding='15' cellspacing='25' bgcolor='#FFFFFF' class='w900'>";
echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Name:".$name;
echo"</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Phone:".$phone;
echo "</strong></font>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Address:".$address;
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Email:".$email;
echo "</strong></font>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Location:".$location;
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo "Supervisor:".$sup_name;
echo "</strong></font>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<br>";
echo"</div>";


//------------------------------------------------------------------------------------------------------------
//expenses claim information
echo"<div class='col-md-6'>";
echo "<h3><font size='5' face='courier' color='#FFFFFF'><strong>Expenses Information</strong></font></h3>";
echo"<table  width='434' border='0' cellpadding='5' cellspacing='25' bgcolor='#FFFFFF' class='w900'>";
echo "<tr>";
echo "<th scope='col'><font size='4' face='courier' color='#1e5799'><strong>Claim Date</strong></font></th>";
echo " <th scope='col'><font size='4' face='courier' color='#1e5799'><strong>Amount</strong></font></th>";  
echo "<th scope='col'><font size='4' face='courier' color='#1e5799'><strong>Claim Type</strong></font></th>";  
echo "</tr>";
for($j=0;$j<count($expRow);$j++)
{	
//echo "claim date is".$empRow[$j]['claim_date'];

echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo $expRow[$j]['claim_date'];
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo " $".$expRow[$j]['claim_amount'];
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
$type_query="SELECT type_name FROM claim_tp where type_id='$claim_type'";
$sqlExp=mysql_query($type_query);
$rowtype = mysql_fetch_array($sqlExp);
//$claim_type=$rowtype['re_desc'];
	  
echo $rowtype['type_name'];
echo "</strong></font>";
echo "</td>";
echo "</tr>";	
 }
echo"<tr>";

echo "<b><font size='4' face='courier' color='#FFFFFF'><strong>Total amount claimed";
echo " $".$expSum;
echo "</strong></font></b>";

echo"</tr>";
echo "</table>";
echo"</div>";
echo"</div>";   //row for emp info & exp ends here


//----------------------------------------------------------------------------------------------------
//for leave
echo "<div class='row'>";  
echo"<div class='col-md-6'>";
echo "</br>";
echo "<h3><font size='5' face='courier' color='#0B0B3B'><strong>Leave Information</strong></font></h3>";
echo"<table  width='434' border='0' cellpadding='5' cellspacing='25' bgcolor='#FFFFFF' class='w900'>";
echo "<tr>";
echo "<th scope='col'><font size='4' face='courier' color='#1e5799'>Start Date</font></th>";
echo " <th scope='col'><font size='4' face='courier' color='#1e5799'>Number Of days</font></th>";  
echo "<th scope='col'><font size='4' face='courier' color='#1e5799'>Leave Type</font></th>";    
echo "</tr>";
for($j=0;$j<count($leaveRow);$j++)
{	
//echo "start date ".$leaveRow[$j]['start_date'];
echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo $leaveRow[$j]['start_date'];
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo $leaveRow[$j]['days'];
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
$tid=$leaveRow[$j]['leaveType'];
//echo $tid."</br>";
$type_query="SELECT type_name FROM leave_type where type_id='$tid'";
$sqlLeave=mysql_query($type_query);
$rowtypeLeave = mysql_fetch_array($sqlLeave);
$leave_type=$rowtypeLeave['type_name'];
	  
echo $leave_type;
echo "</strong></font>";
echo "</td>";
echo "</tr>";	
 }
 echo"<tr>";

 echo "<b><font size='4' face='courier' color='#1e5799'><strong>Total Leaves Availed";
echo " ".$leaveSum;
echo "</strong></font></b>";
 echo"</tr>";

echo "</table>";

echo"</div>";

//-------------------------------------------------------------------------------------------
//for time
echo "<div class='row'>";  
echo"<div class='col-md-6'>";
echo "</br>";
echo "<h3><font size='5' face='courier' color='#0B0B3B'><strong>Time Information</strong></font></h3>";
echo"<table  width='434' border='0' cellpadding='5' cellspacing='25' bgcolor='#FFFFFF' class='w900'>";
echo "<tr>";
echo "<th scope='col'><font size='4' face='courier' color='#1e5799'>Date</font></th>";
echo " <th scope='col'><font size='4' face='courier' color='#1e5799'>Total Hours Worked</font></th>";     
echo "</tr>";
for($j=0;$j<count($timeRow);$j++)
{	
//echo "start date ".$leaveRow[$j]['start_date'];
echo "<tr>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo $timeRow[$j]['date'];
echo "</strong></font>";
echo "</td>";
echo "<td>";
echo "<font size='4' face='courier' color='#1e5799'><strong>";
echo date('H:i:s', $timeRow[$j]['time']);
echo "</strong></font>";
echo "</td>";
echo "</tr>";	
 }
 echo"<tr>";

/*echo "<b><font size='4' face='courier' color='#FFFFFF'><strong>Total time worked";
echo "   ".date('H:i:s', $totalTimeWorked);
echo "</strong></font></b>";
*/
echo"</tr>";

echo "</table>";
?>
</div>
</div>
</body>
</html>
