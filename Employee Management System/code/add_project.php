<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">

html {
  height: 100%;
}



body {
	margin:1%;
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
<title>Add Project</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>
<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Add Project</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Add Project </strong></font></p>




</head>
<body>



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

$projectname=$_POST['projectname'];
//$projectid=$_POST['projectid'];
$startdate=$_POST['startdate'];
$enddate=$_POST['enddate'];
$location=$_POST['location'];
$numbrofpeople=$_POST['numbrofpeople'];
$totalbudget=$_POST['totalbudget'];

//echo "$startdate";


$chk_dup="SELECT distinct name FROM project where name='$projectname'";

$chk_dup1=mysql_query($chk_dup);

$chk_dup2=mysql_num_rows($chk_dup1);

//echo "$chk_dup2";

if ( $chk_dup2 == 0 )
{
$add="insert into project (name,start_date,end_date,location,no_of_ppl,budget) values ('$projectname',str_to_date('$startdate','%m/%d/%Y'),str_to_date('$enddate','%m/%d/%Y'),'$location','$numbrofpeople','$totalbudget')";


//echo "$add";

mysql_query($add);

echo "<font color='#FFFFFF' face='courier' size='3'>You have successfully added a project.</font>";
}

else if ( $chk_dup2 == 1 )
	{
	echo "<font color='#FFFFFF' face='courier' size='3'>You are trying to add an existing project.</font>";
	}
echo"<br/><br/><br/>";
echo"<a href='add project.html'><font color='#FFFFFF' face='courier' size='3'>Go back to Add Project Page</font></a>";
?>

</body>
</html>