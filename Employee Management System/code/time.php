<html>

<head>

<style type="text/css">
body {
	height: 770px;
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,0)));
background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(125,185,232,0) 100%);
background: -o-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(125,185,232,0) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#007db9e8',GradientType=0 ); /* IE6-9 */
}



table {

padding:30px 100px;
background:#FFFFFF;
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>




</head>

<body>



<form action="detailedTime.php" method="post" name="time_clock" onSubmit="return validateForm()">

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='time_c.php'> <u><font color='#FFFFFF' face='courier'>Time Clocking</u></a>  <font color='#FFFFFF' face='courier' size='3'> | Edit</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Time Clocking Summary</strong></font></p>


<br/>

<table width='1000' border='1'>
 
  
  
<?php

date_default_timezone_set('America/Indiana/Indianapolis');
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
//ectract emp id from the seesion. Hardcoded to 1 as of now
$con=mysqli_connect("silo.cs.indiana.edu","b561f13_amlakhan","password","b561f13_amlakhan");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  $empId=$_SESSION['employee_id'];
  
if(isset($_POST['ClockIn']))
{
	
	//echo "Clock in was pressed"."</br>";
	$date=date("Y-m-d");
	//echo "todays date is".$date."</br>";
	$ts=date("Y-m-d H:i:s",time());
	//echo "current time stamp is
//".$ts."</br>";
	mysqli_query($con,"INSERT INTO time_clocking (clock_in_date, clock_in_time,employee_id)
				VALUES ('$date','$ts','$empId')");
				header("Location: time_c.php");
}
else if (isset($_POST['ClockOut']))
{
//echo "Clock out was pressed"."</br>";
$sql=mysqli_query($con,"SELECT MAX(time_id) FROM time_clocking where employee_id ='$empId'");
while($row = mysqli_fetch_array($sql))
 {
	 //echo "the last inserted id is ".$row[0];	
	 $id=$row[0];
	 $tso=date("Y-m-d H:i:s",time());
	 $upd=mysqli_query($con,"UPDATE time_clocking SET clock_out_time ='$tso' where time_id ='$id'");
 }
 header("Location: time_c.php");
}
else if (isset($_POST['edit']))
{
date_default_timezone_set('UTC');
//date
if (isset($_POST['datepicker']))
{
	//echo $_POST['datepicker'];
	$dt=$_POST['datepicker'];
	$weekday = date('N', strtotime($_POST['datepicker']));
	$diff=$weekday-1;
	$temp= "-".$diff." day";
	//echo "string is".$temp;
	$newdate = strtotime ( $temp , strtotime ( $dt ) ) ;
	$newdate = date ( 'Y-m-j' , $newdate );
	//echo "mon date is".$newdate;
	$weekDate=array();
	for($i=0;$i<7;$i++)
	{
		$t= "+".$i." day";
		$nd = strtotime ( $t , strtotime ( $newdate ) ) ;
		$nd = date ( 'Y-m-j' , $nd );
		$weekDate[$i]=$nd;
	}
	echo "<tr>";
	echo "<th>";
	echo "<font color='#1e5799' face='courier' size='3'>";
	echo "Select";
	echo"</font>";
	echo "</th>";
	echo "<th>";
	echo "<font color='#1e5799' face='courier' size='3'>";
	echo "Date";
	echo"</font>";
	echo "</th>";
	echo "<th>";
	echo "<font color='#1e5799' face='courier' size='3'>";
	echo "Number of Hours worked";
	echo"</font>";
	echo "</th>";
	/*foreach( $weekDate as $r)
	{ 
	echo $r." "."</br>";
  	echo"<th scope='col'>$r</th>";
	}
	echo "</tr>";
	echo "<tr>";*/
	$noOfRows=count($weekDate);
	foreach($weekDate as $r)
	{
		echo "<tr>";
		echo "<td>";
		echo"<input name='check' id='check' type='radio' value='$r' />";
		echo "</td>";
		echo "<td>";
		echo $r;
		echo "</td>";
		//code to calculate the total hours worked on a specific day
		$sql=mysqli_query($con,"SELECT * FROM time_clocking where clock_in_date ='$r' and employee_id=$empId");
$time=0;
$time_diff=array();
//$cnt=0;
$init_time=0;
while($row = mysqli_fetch_array($sql))
 {
	//echo "clock in time is ".$row['clock_in_time'];
	//echo "</br>";
	//echo "clock out time is ".$row['clock_out_time'];
	//echo "</br>";
	$time1 = strtotime($row['clock_in_time']);
	$time2 = strtotime($row['clock_out_time']);
	$diff = $time2 - $time1;
	//echo "the difference is".date('H:i:s', $diff)."</br>";
	//$time_diff[$cnt]=$diff;
	//$cnt=$cnt+1;
	$init_time=$init_time+$diff;
 }
 
 		//echo "total time worked is ".date('H:i:s', $init_time)."</br>";
		echo "<td>";
		echo date('H:i:s', $init_time);
		echo "</td>";
		echo "</tr>";
	}
	
}
else
{
	echo "datenot set";
}
}
?>
</table>
<br/> <br/>
<input name="edit" type="submit" value="Edit" style="height: 30px; width: 150px; color: #1e5799"/>
</form>
</body>
</html>
