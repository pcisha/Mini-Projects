<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<style type="text/css">
body {
	height: 778px;
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
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='rea.php'> <u><font color='#FFFFFF' face='courier'>Resource Allocation</u></font> </font></a>  <font color='#FFFFFF' face='courier' size='3'> | Search Results</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Resource Allocation </strong></font></p>




</head>
<body>
<?php
//$host="localhost"; // Host name 
//$username="root"; // Mysql username 
//$password="password"; // Mysql password 
//$db_name="employeemanagement"; // Database name                            
//mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
//mysql_select_db("$db_name")or die("cannot select DB");

include 'db_conn.php';
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


$emp_name=$_POST['emp_name'];
$emp_id=$_POST['emp_id'];
$designation=$_POST['designation'];
$email_id=$_POST['email_id'];
$location=$_POST['location'];
$skill=$_POST['skill'];

$first_filled=null;
//echo $emp_name."<br/>";
//echo $emp_id ."<br/>";
//echo $designation ."<br/>";
//echo $email_id ."<br/>";
//echo $location ."<br/>";
//echo "skill is".$skill;


//check if only the skills is entered
if($skill!=0)
{
	
	//echo "skill is not null";
	if($emp_name=="" && $emp_id=="" && $designation=="" && $email_id=="" && $location=="")
	{
		
		//get the skill id from skills first and then extract all
		$query="select * from employee,skill,skills_mapping
where employee.employee_id=skills_mapping.employee_id and skills_mapping.skill_id = skill.skill_id   
and skills_mapping.skill_id='$skill' and employee.deployed='0'";
		$sql=mysql_query($query);
		$empRow=array();
		$empcnt=0;
	
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
			$f_skill_name=$row['skill_name'];
			$f_location=$row['location'];
			if($row['deployed']==0)
			{
				$f_deployed="Undeployed";
			}
			
	
			$empRow[$empcnt]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'skill_name'=>$f_skill_name,
						'location'=>$f_location,
						'deployed'=>$f_deployed);
						
						$empcnt++;
 		 }
			
	}//end of if where nulls are checked
	else
	{
		//handle the combinations where skills entered with any other parameter
		//echo "combo combo combo";
		$query="Select * from employee where";

if($emp_name!="")
{	if($first_filled==null)
	{
		$first_filled="filled";
	}

	//echo "name is not blank"."<br/>";
	$query=$query." name LIKE '%$emp_name%'";
}
if($emp_id!="")
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
if($designation!="")
{
	//echo "designation is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." designation='$designation'";
	}
	else
	{
		$query=$query." and designation='$designation'";
	}
	
}
if($email_id!="")
{
	//echo "email_id is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." email_id='$email_id'";
	}
	else
	{
		$query=$query." and email_id='$email_id'";
	}
	
}
if($location!="")
{
	//echo "location is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." location='$location'";
	}
	else
	{
		$query=$query." and location='$location'";
	}
	
}
$first_filled=null;
//echo "the query is ".$query."</br>";
$query=$query." and employee.deployed='0'";
$sql=mysql_query($query);
$empRow=array();
//echo "$sql";
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
	if($row['deployed']==0)
	{
		$f_deployed="Undeployed";
	}
	$f_location=$row['location'];
	
	$qidExist="select * from skills_mapping where employee_id='$f_emp_id'";
	$sqlqidExist=mysql_query($qidExist);
	while($rowqidExist = mysql_fetch_array($sqlqidExist))
  	{	
		$skl=$rowqidExist['skill_id'];
		if($skl==$skill)
		{
			$qname="select * from skill where skill_id=$skill";
$sqlqname=mysql_query($qname);
$rowqname = mysql_fetch_array($sqlqname);
//echo "sss".$rowqname[0];
	
	$empRow[$i]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'deployed'=>$f_deployed,
						'location'=>$f_location,
						'skill_name'=>$rowqname[0]);
  $i=$i+1;
  //echo $f_emp_id."</br>";
  //echo $f_name."</br>";
  //echo $f_email."</br>";
  //echo $f_deployed."</br>";
  //echo $f_location."</br>";
	
		}
  	}
	
  
  }
		
		
		
		
	}//end of else of skills entered with any other paramtere
}
else if($skill==0)
{
	//echo "no skills entered";
	//if skills not entered and everything else is entered
	
	$query="Select * from employee where";

if($emp_name!="")
{	if($first_filled==null)
	{
		$first_filled="filled";
	}

	//echo "name is not blank"."<br/>";
	$query=$query." name LIKE '%$emp_name%'";
}
if($emp_id!="")
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
if($designation!="")
{
	//echo "designation is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." designation='$designation'";
	}
	else
	{
		$query=$query." and designation='$designation'";
	}
	
}
if($email_id!="")
{
	//echo "email_id is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." email_id='$email_id'";
	}
	else
	{
		$query=$query." and email_id='$email_id'";
	}
	
}
if($location!="")
{
	//echo "location is blank"."<br/>";
	if($first_filled==null)
	{
		$first_filled="filled";
		$query=$query." location='$location'";
	}
	else
	{
		$query=$query." and location='$location'";
	}
	
}
$first_filled=null;
//echo "the query is ".$query."</br>";
$query=$query." and employee.deployed='0'";
$sql=mysql_query($query);
$empRow=array();
//echo "$sql";
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
	if($row['deployed']==0)
	{
		$f_deployed="Undeployed";
	}
	$f_location=$row['location'];
	
	$qname="select skill_name from skills_mapping,skill
where employee_id='$f_emp_id' and skills_mapping.skill_id=skill.skill_id";
$sqlqname=mysql_query($qname);
$rowqname = mysql_fetch_array($sqlqname);
//echo "sss".$rowqname[0];
	
	$empRow[$i]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'deployed'=>$f_deployed,
						'location'=>$f_location,
						'skill_name'=>$rowqname[0]);
  $i=$i+1;
  //echo $f_emp_id."</br>";
  //echo $f_name."</br>";
  //echo $f_email."</br>";
  //echo $f_deployed."</br>";
  //echo $f_location."</br>";
  
  }
}

//display it inside a table
  echo"<form action='tryAll.php' class='validate' name='allocate' method='post'>";
  echo"
  <table width='1000' border='10' cellpadding='10' cellspacing='25' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
  <caption>
   <font color='#FFFFFF' face='courier' size='3'>Search Results</font>
  </caption>
  <tr>
  <th scope='col'><font color='#1e5799' face='courier' size='3'>Select</font></th>
    <th scope='col'> <font color='#1e5799' face='courier' size='3'>Employee id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Employee Name</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Email id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Designation</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Deployed</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Location</font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='3'>Skill</font></th>
	<th scope='col'><font color='#1e5799' face='courier' size='3'>Project</font></th>
  </tr>";
  for($j=0;$j<count($empRow);$j++)
  {		echo "<tr>";
	  //$t=array();
	  $f_emp_id=$empRow[$j]['employee_id'];
	  echo"<td>";
	  echo "<input type='checkbox' name=checklist[] value=$j.$f_emp_id>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["employee_id"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["emp_name"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["email_id"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["designation"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["deployed"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["location"];
	  echo"</td>";
	  
	  echo"<td>";
	  echo $empRow[$j]["skill_name"];
	  echo"</td>";
	  
	  //for dropdown
	  echo"<td>";
	  echo"<select name='project[]' id='project'>";
	  $d=date("Y-m-d");
	  $query="SELECT project_id, name from project where end_date > $d";
		$sql=mysql_query($query);  
		echo"<option value='0'>--Select Project--</option>";                     
	while($row1 = mysql_fetch_array($sql))
	{
	$pid=$row1['project_id'];
	$pname=$row1['name'];
	echo"<option value=$j.$pid name=$pid>$pname</option>";	
	}



echo"</select>";

	  echo "</tr>";
  }
echo "</table>";
echo "<input type='submit' value='Allocate' id='allocate' name='allocate' />";
echo "</form>";
echo "</body>";
echo"</html>";
?>