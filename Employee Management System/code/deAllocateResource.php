<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">

html {
  height: 100%;
}



body {
	
	max-height: 750px;
	overflow:auto;
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
/*border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;*/
}



</style>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resource Allocation</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='resourceDeallocation.php'> <u><font color='#FFFFFF' face='courier'>Resource De-Allocation</u></font> </font></a>  <font color='#FFFFFF' face='courier' size='3'> | Messages</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Resource Allocation </strong></font></p>




</head>
<body>

<?php
include 'db_conn_mysqli.php';

session_start();
if(isset($_SESSION['logged_in']))
{
	
	if($_SESSION['logged_in'])
	{
		//echo "logged in";
		//header("Location: login.html");
		if($_SESSION['usr_role']==4||$_SESSION['usr_role']==2)
		{
			header("Location: login.html");
		}
	}
}
else
{
	header("Location: login.html");
}
  
  $designation=$_SESSION['usr_role'];
  //$row_no=array();
//echo "checking";
if(!empty($_POST['checklist'])) {
	//echo "inside if";
	$i=0;
    foreach($_POST['checklist'] as $check) {
            //echo $check;
			$pos = strrpos($check, ".");
			//echo "position is".$pos;
			$pid = substr($check,strrpos($check, ".")+1);//extract the id
			$row=substr($check,0,strrpos($check, "."));//extract the row number
			//$row_no[$row]=$id;
			//echo "row is".$row;
			//echo"substring is".$id."</br>";
			//echo "the project id is ".$pid."</br>";
			$pos = strrpos($row, ".");
			//echo "position is".$pos;
			$eid = substr($row,strrpos($row, ".")+1);//extract the id
			$r=substr($row,0,strrpos($row, "."));//extract the row number
			//echo "eid is".$eid;
			//echo "r is".$r;
			$d=date("Y-m-d");
			//echo $d;
			$query="UPDATE employee SET deployed=0 WHERE employee_id=$eid";
			$res=mysqli_query($con,$query);
			
			$query="Select designation from employee WHERE employee_id=$eid";
			$reslt=mysqli_query($con,$query);
			
			$rowd = $reslt->fetch_array();
			
			$designation=$rowd[0];
			
			//echo "designation is".$designation;
			
			if($designation==2)
			{
			//echo "yayyy";	
			//$query1="UPDATE works_on SET emp_end_date ='$d' WHERE employee_id='$eid' and project_id='$pid'";
			//$res1=mysqli_query($con,$query1);
			//echo"res1 is".$res1;
			$query2="DELETE FROM works_on WHERE employee_id='$eid' and project_id='$pid'";
			$resd2=mysqli_query($con,$query2);
			
			$querys="UPDATE employee SET sup_id='3' WHERE employee_id=$eid";
			$ress=mysqli_query($con,$querys);
			//echo"ress is".$ress;
			}
			else
			{
			//echo "its the pm yayyyy";
			$query1="DELETE FROM manages WHERE employee_id = '$eid'";
			$resd=mysqli_query($con,$query1);
			//echo "del".$resd;
			}
			
			
			//mysqli_query($con,"UPDATE works_on SET emp_end_date = $d WHERE employee_id=$eid and project_id=$pid");
			//echo "result is ".$res1;
			
    }
}

echo "<p><font color='#FFFFFF' face='courier' size='5'><strong>RESOURCE DE-ALLOCATED SUCCESSFULLY</strong></font></p>";

?>

</body>
</html>