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
<title>Resource Allocation</title>

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr/>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | <a href='resourceDeallocation.html'> <u><font color='#FFFFFF' face='courier'>Resource Deallocation</u></font> </font></a>  <font color='#FFFFFF' face='courier' size='3'> | De-allocate Resources</font></p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Resource Deallocation </strong></font></p>




</head>
<body>

<?php
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
$project_name=$_POST['proj_name'];


//echo "emp name is".$emp_name."</br>";
//echo "emp id is".$emp_id."</br>";
//echo "emp designation is".$designation."</br>";
//echo "emp email is".$email_id."</br>";
//echo "emp location is".$location."</br>";
//echo "emp project is".$project_name."</br>";

//check if only the project name is entered
if($project_name!="")
{
	
	//echo "project name is not null";
	if($emp_name=="" && $emp_id=="" && $designation=="" && $email_id=="" && $location=="")
	{
		//all are empty so just query the works on and the project table to get the employee names working on that proect
		$query="SELECT employee.employee_id, employee.name, email_id, designation, project.name, employee.location,project.project_id
				FROM employee, works_on, project
				WHERE project.name LIKE  '%$project_name%'
				AND works_on.project_id = project.project_id
				AND works_on.employee_id = employee.employee_id AND employee.deployed='1'";
		$sql=mysql_query($query);
		$empRow=array();
		$empcnt=0;
		while($row = mysql_fetch_array($sql))
  		{	
  			//echo"inside while"."</br>";
			$f_emp_id=$row['employee_id'];
			$f_name=$row[1];
			$f_email=$row['email_id'];
			$f_project_name=$row[4];
			$f_location=$row['location'];
			$f_project_id=$row['project_id'];
			
			$d=$row['designation'];
			$queryd="Select designation_name from designation_mapping where designation_id=$d";
			$sqld=mysql_query($queryd);
			$rowd = mysql_fetch_array($sqld);
			$f_designation=$rowd[0];
	
			//$f_designation=$row['designation'];
	
			$empRow[$empcnt]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'project_name'=>$f_project_name,
						'location'=>$f_location,
						'project_id'=>$f_project_id);
						
						$empcnt++;
 		 }
			
	}//end of if where nulls are checked
	else
	{
		$first_filled=null;
	$query="SELECT * FROM employee WHERE";
	
	
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
	echo "emp_id is blank"."<br/>";
	
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
		$query=$query. "and deployed='1'";
		echo "the query is ".$query."</br>";
	
		$sql=mysql_query($query);
		$empRow=array();
		$empcnt=0;
		while($row = mysql_fetch_array($sql))
  		{
			//bla bla
		$f_emp_id=$row['employee_id'];
		$f_name=$row['name'];
		$f_email=$row['email_id'];
		//$f_designation=$row['designation'];
		$d=$row['designation'];
		$queryd="Select designation_name from designation_mapping where designation_id=$d";
		$sqld=mysql_query($queryd);
		$rowd = mysql_fetch_array($sqld);
		$f_designation=$rowd[0];
	
		$f_location=$row['location'];
		$f_project_name=$project_name;
		
		echo "employee id is".$f_emp_id."</br>";
		echo "name is".$f_name."</br>";
		echo "employee email id is".$f_email."</br>";
		echo "employee desig is".$f_designation."</br>";
		echo "employee location is".$f_location."</br>";
		echo "project name is".$f_project_name."</br>";
		
		$queryPname="SELECT project_id from project where name='$f_project_name'";
		$sqlPname=mysql_query($queryPname);
		$rowPname = mysql_fetch_array($sqlPname);
		
		$f_project_id=$rowPname[0];
		$empRow[$empcnt]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'project_name'=>$f_project_name,
						'location'=>$f_location,
						'project_id'=>$f_project_id);
						
						$empcnt++;
		
		//echo "employee project id is".$f_project_id."</br>";
		
		}

	}
}
//handle the combination where project not entered and everything else entered
else if($project_name=="")
{
	$first_filled=null;
	$query="SELECT * FROM employee WHERE";
	
	
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
	echo "emp_id is blank"."<br/>";
	
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
		$query=$query." and designation='$designation' and employee.designation <> 4";
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
$query=$query. "and deployed='1'";
//echo "the query is ".$query."</br>";

		$sql=mysql_query($query);
		$empRow=array();
		$empcnt=0;
		while($row = mysql_fetch_array($sql))
  		{
			$f_emp_id=$row['employee_id'];
			$f_name=$row['name'];
			$f_email=$row['email_id'];
			//$f_designation=$row['designation'];
			$f_location=$row['location'];
			$d=$row['designation'];
			$queryd="Select designation_name from designation_mapping where designation_id=$d";
			$sqld=mysql_query($queryd);
			$rowd = mysql_fetch_array($sqld);
			$f_designation=$rowd[0];
	
			
			//echo "employee id is".$f_emp_id."</br>";
			//echo "name is".$f_name."</br>";
			//echo "employee email id is".$f_email."</br>";
			//echo "employee desig is".$f_designation."</br>";
			//echo "employee location is".$f_location."</br>";
			
			if($f_designation==4)
			{
				
			$queryPid="SELECT max(project_id) from manages where employee_id='$f_emp_id'";
			$sqlPid=mysql_query($queryPid);
			$rowPid = mysql_fetch_array($sqlPid);
			$f_project_id=$rowPid[0];
			//echo "andar aaya and project id is".$f_project_id;
			}
			else
			{
			$queryPid="SELECT max(project_id) from works_on where employee_id='$f_emp_id'";
			$sqlPid=mysql_query($queryPid);
			$rowPid = mysql_fetch_array($sqlPid);
			$f_project_id=$rowPid[0];
			
			}
			//echo "employee project id is".$f_project_id."</br>";
			
			$queryPname="SELECT name from project where project_id='$f_project_id'";
			$sqlPname=mysql_query($queryPname);
			$rowPname = mysql_fetch_array($sqlPname);
			$f_project_name=$rowPname[0];
			//echo "employee Pname is".$f_project_name."</br>";
			
			$empRow[$empcnt]=array('employee_id'=>$f_emp_id,
						'emp_name'=>$f_name,
						'email_id'=>$f_email,
						'designation'=>$f_designation,
						'project_name'=>$f_project_name,
						'location'=>$f_location,
						'project_id'=>$f_project_id);
						
						$empcnt++;

		}


}

//display it inside a table
echo"<html>";
  echo "<body>";
  echo"<form action='deAllocateResource.php' class='validate' name='allocate' method='post'>";
  echo"
  <table width='1000' border='10' cellpadding='10' cellspacing='25' bgcolor='#FFFFFF' class='w900' bordercolor='#1e5799'>
 <caption>
 <font color='#FFFFFF' face='courier' size='3'>Search Results</font>
 <br/>
 </caption>
  <tr>
  <th scope='col'><font color='#1e5799' face='courier' size='3'>Select</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Employee id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Employee Name</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Email id</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Designation</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Project Name</font></th>
    <th scope='col'><font color='#1e5799' face='courier' size='3'>Location</font></th>
  </tr>";
  for($j=0;$j<count($empRow);$j++)
  {		echo "<tr>";
	  $f_emp_id=$empRow[$j]['employee_id'];
	  $f_pr_id=$empRow[$j]['project_id'];
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo "<input type='checkbox' name=checklist[] value=$j.$f_emp_id.$f_pr_id>";
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["employee_id"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["emp_name"];
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
	  echo $empRow[$j]["project_name"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo"<td>";
	  echo"<font color='#1e5799' face='courier' size='2.7'>";
	  echo $empRow[$j]["location"];
	  echo"</font>";
	  echo"</td>";
	  
	  echo "</tr>";
  }
echo "</table>";
echo"<br/><br/>";
echo "<input type='submit' value='DeAllocate' id='deallocate' name='deallocate' style='height: 30px; width: 150px; color: #1e5799'/>";
echo "</form>";
echo "</body>";
echo"</html>";
?>