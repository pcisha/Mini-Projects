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
$con=mysqli_connect("silo.cs.indiana.edu","b561f13_amlakhan","password","b561f13_amlakhan");
// Check connection

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
if(isset($_POST['add']))
{
	//echo "add button pressed"."</br>";
	if(isset($_POST['upd_time_hours']) && isset($_POST['upd_time_mins']))
	{
	$hrs= $_POST['upd_time_hours'];
	//echo "$hrs";
	$mins= $_POST['upd_time_mins'];
	//echo "$mins";
	
	//get the current date
	$date=$_SESSION['mod_date'];
	unset($_SESSION['mod_date']);//date("Y-m-d",time());
	//echo "dat is".$date."<br>";
	
	$dtstamp=$date." ".$hrs.":".$mins.":"."00";
	$ts = strtotime($dtstamp);
	$fts=date('Y-m-d H:i:s', $ts);
	//echo "dadadada".$fts;
	}
	
	if(isset($_POST['out_time_hours']) && isset($_POST['out_time_mins']))
	{
		$outHrs= $_POST['out_time_hours'];
		//echo "out hrs"."$outHrs"."<br>";
		$outMins= $_POST['out_time_mins'];
		//echo "out mins"."$outMins"."<br>";
		
		$odtstamp=$date." ".$outHrs.":".$outMins.":"."00";
		$ots = strtotime($odtstamp);
		$ofts=date('Y-m-d H:i:s', $ots);
		//echo "dadadada".$ofts;
	}
	
	$emp_id=$_SESSION['employee_id'];
	
	//insert the data
	$r=mysqli_query($con,"INSERT INTO time_clocking (clock_in_date, clock_in_time,clock_out_time,employee_id)
				VALUES ('$date','$fts','$ofts','$emp_id')");
				
				//echo"the result of the query is".$r;
}

if(isset($_POST['update']))
{
	echo "update button pressed"."</br>";
	echo "row selected".$_POST['check']."</br>";
	
	$rn=$_POST['check'];
	
	$dte = substr($rn,strrpos($rn, ".")+1);//extract the row
	$rno1=substr($rn,0,strrpos($rn, "."));//extract the time id
	
	$tid = substr($rno1,strrpos($rno1, ".")+1);//extract the row
	$rno=substr($rno1,0,strrpos($rno1, "."));//extract the time id
	
	echo "row no is".$rno."</br>";
	echo "date is".$dte."</br>";
	echo "time id id".$tid."</br>";
	
	$intmHr=$_POST['time_hours'];
	$intmMins=$_POST['time_mins'];
	$outtmHr=$_POST['out_time_hours_dd'];
	$outtmMins=$_POST['out_time_mins_dd'];
	
	echo "clock in hours for selected row is".$intmHr[$rno]."</br>";
	echo "clock in mins for selected row is".$intmMins[$rno]."</br>";
	echo "clock out hours for selected row is".$outtmHr[$rno]."</br>";
	echo "clock out for selected row is".$outtmMins[$rno]."</br>";
	
	$indtstamp=$dte." ".$intmHr[$rno].":".$intmMins[$rno].":"."00";
	$ints = strtotime($indtstamp);
	$infts=date('Y-m-d H:i:s', $ints);
	//echo "In time Stamp".$infts."</br>";
	
	$oudtstamp=$dte." ".$outtmHr[$rno].":".$outtmMins[$rno].":"."00";
	$outts = strtotime($oudtstamp);
	$outfts=date('Y-m-d H:i:s', $outts);
	echo "out time Stamp".$outfts."</br>";
	
	//contruct in time and out time
	$query="UPDATE time_clocking SET clock_out_time='$outfts', clock_in_time='$infts' WHERE time_id=$tid";
	$res=mysqli_query($con,$query);
	echo "res is".$res;
	
}
header("Location: time_c.php");
?>