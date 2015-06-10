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
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='rea.php'> <u><font color='#FFFFFF' face='courier'>Resource Allocation</u></font> </font></a>  <font color='#FFFFFF' face='courier' size='3'> | Unallocated Resources</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Resource Allocation </strong></font></p>




</head>
<body>


<?php
include 'db_conn.php';

$query="SELECT * FROM employee WHERE deployed= 0";
$sql=mysql_query($query);
$empRow=array();

$i=0;
while($row = mysql_fetch_array($sql))
  {	
  	//echo"inside while"."</br>";
	$f_emp_id=$row['employee_id'];
	$f_name=$row['name'];
	$f_email=$row['email_id'];
	$d=$row['designation'];
	$queryd="Select designation_name from designation_mapping where designation_id=$d";
	$sqld=mysql_query($queryd);
	$rowd = mysql_fetch_array($sqld);
	$f_designation=$rowd[0];
	//$f_deployed=$row['deployed'];
	if($row['deployed']==0)
	{
		$f_deployed="Undeployed";
	}
	$f_location=$row['location'];
	
	$empRow[$i]=array('employee_id'=>$f_emp_id,
						'name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'deployed'=>$f_deployed,
						'location'=>$f_location);
	//$temp=array();
	//$temp[$i]=$row;
	/*temp(1)=$f_emp_id;
	temp(2)=$f_name;
	temp(3)=$f_email;
	temp(4)=$f_designation;
	temp(5)=$f_deployed;
	temp(6)=$f_location;
	*/
  //$empRow[$i]=$temp;
  $i=$i+1;
 // echo $f_emp_id."</br>";
 // echo $f_name."</br>";
 // echo $f_email."</br>";
 // echo $f_deployed."</br>";
 // echo $f_location."</br>";
  
  }
//  echo"<html>";
  //echo "<body>";
  echo"<form action='tryAll.php' class='validate' name='allocate' method='post'>";
  echo"
  <table width='1000' border='10' cellpadding='10' cellspacing='25' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
  <caption>
    <font color='#FFFFFF' face='courier' size='3'>Search Results</font>
  </caption>
  <tr>
  <th scope='col'><font color='#1e5799' face='courier' size='3'>Select</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Employee id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Employee Name</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Email id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Designation</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Deployed</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Location</font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='3'>Project</font></th>
  </tr>";
  for($j=0;$j<count($empRow);$j++)
  {		echo "<tr>";
	  //$t=array();
	  $f_emp_id=$empRow[$j]['employee_id'];
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo "<input type='checkbox' name=checklist[] value=$j.$f_emp_id>";
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["employee_id"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["name"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["email_id"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["designation"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["deployed"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["location"];
	  echo"</font>";
	  echo"</td>";
	  
	  //for dropdown
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo"<select name='project[]' id='project'>";
	  $query="SELECT project_id, name from project";
		$sql=mysql_query($query);                       
		echo"<option value='0'>--Select Project--</option>";
	while($row1 = mysql_fetch_array($sql))
	{
	$pid=$row1['project_id'];
	$pname=$row1['name'];
	echo"<option value=$j.$pid name=$pid>$pname</option>";	
	}



echo"</select>";
echo"</font>";
echo "</td>";
 echo "</tr>";
  }
echo "</table>";
echo"<br/>";
echo "<input type='submit' value='Allocate' id='allocate' name='allocate' style='height: 30px; width: 150px; color: #1e5799' />";
echo "</form>";
echo "</body>";
echo"</html>";
  


?>