<html>
<head>

<style type="text/css">
body {
	height: 1300px;
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
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}

</style>

</head>
<body>
<form action="editTime.php" method="post" name="detailedTime">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Time Clocking</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='time_c.php'> <u><font color='#FFFFFF' face='courier'>Time Clocking</u></a>  <font color='#FFFFFF' face='courier' size='3'> | Edit Time</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Time Clocking </strong></font></p>





<!--logic for time-->
<table width='1000' border='10' cellpadding='10' cellspacing='10' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
  
  <tr>
  <th>Select</th>
  <th>Clock In Hour</th>
  <th>Clock In Min</th>
  <th>Clock Out Hour</th>
  <th>Clock Out Min</th>
  </tr>
  <p>
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
date_default_timezone_set('America/Indiana/Indianapolis');
$day=$_POST['check'];
$_SESSION['mod_date']=$day;
//echo "the selected day is ".$day."</br>";
$con=mysqli_connect("silo.cs.indiana.edu","b561f13_amlakhan","password","b561f13_amlakhan");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $cnt=0;
  $emp_id=$_SESSION['employee_id'];
 $sql=mysqli_query($con,"SELECT * FROM time_clocking where clock_in_date ='$day' and employee_id='$emp_id'");
while($row = mysqli_fetch_array($sql))
 {
	 $time_id=$row['time_id'];
	 echo "<tr>";
	 echo "<td>";
	 echo"<input name='check' id='check' type='radio' value=$cnt.$time_id.$day />";
	 echo "</td>";
	 $clockDate=$row['clock_in_date'];
	 $clockInTime=$row['clock_in_time'];
	 $clockOutTime=$row['clock_out_time'];
	 
	 //echo "the date is".$clockDate."</br>";
	 //echo "the in time is".$clockInTime."</br>";
	 //echo "the out time is".$clockOutTime."</br>";
	 //for intime
	 $inTime = date('H:i:s',strtotime($clockInTime));
	 //echo "in time is".$inTime."</br>";
	 list($hours, $mins, $secs) = explode(':', $inTime);
	 //echo "hours is".$hours."</br>";
	 //echo "mins is".$mins."</br>";
	 echo "<td>";
	 echo "<select name='time_hours[]'>";//for hours
	 
	 for($i=0;$i<24;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		 if($i==$hours)
		 {
			 echo "<option value='$i' selected='selected'>$i</option>";
		 }
		 else
		 {
			 echo "<option value='$i'>$i</option>";
		 }
		 
	 }
	 echo "</select>";
	 echo "</td>";
	 echo "<td>";
	 //for mins
	 echo "<select name='time_mins[]'>";//for mins
	 for($i=0;$i<60;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		if($i==$mins)
		 {
			 echo "<option value='$i' selected='selected'>$i</option>";
		 }
		 else
		 {
			 echo "<option value='$i'>$i</option>";
		 }
	 }
	 echo "</select>";
	 echo "</td>";
	 //for out time
	 $outTime = date('H:i:s',strtotime($clockOutTime));
	 //echo "in time is".$outTime."</br>";
	 list($hours, $mins, $secs) = explode(':', $outTime);
	 //echo "hours is".$hours."</br>";
	 //echo "mins is".$mins."</br>";
	 echo "<td>";
	 echo "<select name='out_time_hours_dd[]'>";//for hours
	 for($i=0;$i<24;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		 if($i==$hours)
		 {
			 echo "<option value='$i' selected='selected'>$i</option>";
		 }
		 else
		 {
			 echo "<option value='$i'>$i</option>";
		 }
		 
	 }
	 echo "</select>";
	 echo "</td>";
	 //for mins
	 echo "<td>";
	 echo "<select name='out_time_mins_dd[]'>";//for hours
	 for($i=0;$i<60;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		if($i==$mins)
		 {
			 echo "<option value='$i' selected='selected'>$i</option>";
		 }
		 else
		 {
			 echo "<option value='$i'>$i</option>";
		 }
	 }
	  echo "</select>";
	  echo "</td>";
	$cnt=$cnt+1;
	echo "</tr>";
 }

?>
 </table>  
 <p>
   <input name="update" id="update" type="submit" value="Update" style="height: 30px; width: 150px; color: #1e5799"/>
 </p>
 <table width='1000' border='10' cellpadding='10' cellspacing='10' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>  
  

   <label></label>
   <br>
   <tr><td><label>Clock In Time (Hours) </label></td>
   <td><?php echo "<select name='upd_time_hours'>";//for hours
	 for($i=0;$i<24;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		echo "<option value='$i'>$i</option>";
	 }
	 echo "</select>";
	 ?></td></tr>
   </br>
   <tr><td><label>Clock in Time (Mins) </label></td>
   <td><?php
echo "<select name='upd_time_mins'>";//for hours
	 for($i=0;$i<60;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		echo "<option value='$i'>$i</option>";
	 }
	  echo "</select>";
	 
?></td>
   <br>
   <label></label>
   <br>
   <tr><td><label>Clock Out Time (Hours)</label></td>
   <td><?php echo "<select name='out_time_hours'>";//for hours
	 for($i=0;$i<24;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		echo "<option value='$i'>$i</option>";
	 }
	 echo "</select>";
	 ?></td></tr>
   </br>
   <tr><td><label>Clock Out Time (Mins)</label></td>
   <td><?php
echo "<select name='out_time_mins'>";//for hours
	 for($i=0;$i<60;$i++)
	 {
		 if(strlen($i)==1)
		 {
			 $i="0".$i;
		 }
		echo "<option value='$i'>$i</option>";
	 }
	  echo "</select>";
	 
?></td>
   
   </table>
   <p>&nbsp;   </p>
   <p>
     <input name="add" id="add" type="submit" value="add" style="height: 30px; width: 150px; color: #1e5799"/>
     <br>
   </p>
 </p>
    
  </p>
</form>
</body>
</html>
