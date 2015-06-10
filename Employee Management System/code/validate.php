<?php
session_start();
//$host="localhost"; // Host name 
//$username="root"; // Mysql username 
//$password="password"; // Mysql password 
//$db_name="employeemanagement"; // Database name                            
//mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
//mysql_select_db("$db_name")or die("cannot select DB");
include 'db_conn.php';
$usr=$_POST['user_name'];
$pwd=$_POST['password'];

//echo"$usr \n";
//echo"$pwd \n";

$sql=mysql_query("SELECT * FROM authenticate where user_id='$usr' and password='$pwd'");
$suc=null;
//echo "$sql";
while($row = mysql_fetch_array($sql))
  {	
  	echo"inside while";
  
	  $username=$row['user_id'];
	  //echo"user name is $username"; 
	  $password1=$row['password'];
		//echo"password name is $password";
	  
	  if( $username==$usr && $password1==$pwd )
	  {
	  echo"success";
	  $suc="success";
	  $_SESSION['usr_role']=$row['designation_id'];
	  $_SESSION['user_id']=$row['user_id'];
	  $_SESSION['employee_id']=$row['employee_id'];
	  $_SESSION['logged_in']=true;
	 
	 echo $_SESSION['employee_id'];
	 header("Location: home.php"); /* Redirect browser */
	  }
	  else
	  {
	  echo "failure";
	  header("Location: login.html");
	  }
  
   }
   if($suc==null)
   {
   echo "failure";
   header("Location: login.html");
   }
 
 $sql1=mysql_query("select name,deployed from employee where employee_id=(SELECT employee_id FROM authenticate where user_id='$usr' and password='$pwd')");

$row1 = mysql_fetch_array($sql1);

$result1=$row1['name'];  
echo $result1;
$_SESSION['name']=$row1['name'];
$_SESSION['deployed']=$row1['deployed'];

?>