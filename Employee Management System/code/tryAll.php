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
include 'db_conn_mysqli.php';
//$con=mysqli_connect("localhost","root","password","employeemanagement");
// Check connection
//if (mysqli_connect_errno())
  //{
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  //}
  
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
  
  
  $row_no=array();
	if(!empty($_POST['checklist'])) 
	{
		//echo "inside if";
		$i=0;
    		foreach($_POST['checklist'] as $check) 
			{
            	//echo $check;
				$pos = strrpos($check, ".");
				//echo "position is".$pos;
				$id = substr($check,strrpos($check, ".")+1);//extract the id
				$row=substr($check,0,strrpos($check, "."));//extract the row number
				$row_no[$row]=$id;
				//echo "row is".$row."</br>";
				//echo"substring is".$id."</br>";
				//echo "the employee id is ".$id."</br>";
			
		
				$continue=true;
				//$queryE="UPDATE employee SET sup_id = $sid WHERE employee_id=$id";
				//$resE=mysqli_query($con,$queryE);
			
			
				//$query="UPDATE employee SET deployed=1 WHERE employee_id=$id";
				//$res=mysqli_query($con,$query);
				//echo "result is ".$res;
			}
	}
	
if(!empty($_POST['project']))
{
	//echo "project not empty";
	foreach($_POST['project'] as $pj)
	{
		//echo $pj."</br>";
		if($pj!=0)
		{
			//echo "pj is ".$pj."</br>";
			$pid = substr($pj,strrpos($pj, ".")+1);//extract the project id
			$prow=substr($pj,0,strrpos($pj, "."));//extract the project name
			//echo "</br>";
			//echo "project id is".$pid."</br>";
			//echo "project row is".$prow."</br>";
			
			if(array_key_exists($prow, $row_no))//check if the same row exists for both project and selected checkbox
			{
				//echo "the project selected in row ".$prow." is ".$pid. "and the employee id is".$row_no[$prow]."</br>";
					
				//if the guy is a manager then just update the manages table and sets deployed to 1	
				$empQuery="select designation from employee where employee_id='$row_no[$prow]'";
				$sqlemp=mysql_query($empQuery);
				$desig=-1;
				while($rowemp = mysql_fetch_array($sqlemp))
  				{
					$desig=$rowemp[0];
				}
				
				if($desig=="4")
				{
					mysqli_query($con,"insert into manages(project_id,employee_id) values ('$pid','$row_no[$prow]')");
					
					$query="UPDATE employee SET deployed=1 WHERE employee_id=$row_no[$prow]";
					$res=mysqli_query($con,$query);
					
					echo "<p><font color='#FFFFFF' face='courier' size='5'><strong>RESOURCE ALLOCATED SUCCESSFULLY</strong></font></p>";
				
				}
				else
				{
					//check the supervisor and update it in the employee table
				$supQuery="select employee_id from manages where project_id=$pid";
				$sql=mysql_query($supQuery);
				$in=false;
				while($row = mysql_fetch_array($sql))
  				{
					$in=true;
					//echo "rpoooooo".$row[0];
					$noOfRows=count($row);
					//echo "no of rows fetched is".count($row);
					//echo "sup is ".$row[0];
					$sid=$row[0];
				

					
					$d=date("Y-m-d");
					//echo "andar ake pid is".$pid."</br>";
					$empp=$row_no[$prow];
					//echo "andar emp id is".$empp."</br>";
										//echo "supervisor for project ".$pid." is ".$sid."and the employee being updated is".$empp."</br>";	
					$s=mysqli_query($con,"insert into 			      	     	works_on(project_id,employee_id,sup_id,hourly_rate,emp_start_date)  values ('$pid','$empp','$sid','14','$d')");
				//		echo "error is".$s;
						
						$query="UPDATE employee SET deployed=1 WHERE employee_id=$row_no[$prow]";
						$res=mysqli_query($con,$query);
				
						$queryE="UPDATE employee SET sup_id = $sid WHERE employee_id=$empp";
						$resE=mysqli_query($con,$queryE);
  
				}
				
				if($in==false)
				{
					echo "<p><font color='#FFFFFF' face='courier' size='5'><strong>NO MANAGER DEFINED FOR THE GIVEN PROJECT</strong></font></p>";
					//echo "no manager defined for the given project";
				}
				else
				{
					echo "<p><font color='#FFFFFF' face='courier' size='5'><strong>RESOURCE ALLOCATED SUCCESSFULLY</strong></font></p>";
				}
			}
					
				
				
				
			}
		}
	}
}
	mysqli_close($con);
?>
</body>
</html>