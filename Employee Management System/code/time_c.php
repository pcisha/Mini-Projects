<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Time Clocking</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  

  
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
        });
  
  </script>
  
<script type="text/javascript">
function validateForm()
{
var e1=document.forms["time"]["datepicker"].value;

if (e1=="")
  {
  //alert("Please fill in the clock in time");
  //return false;
  }
 
else
{
	//alert("All the fields are filled");	
}
 
}
</script>
  
  
  
  
  
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

padding:10px 200px;
background:#FFFFFF;
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>


</head>
<body>
 <form id="form1" action="time.php" method="post" name="time" id="time">
 
 <h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>



   <div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>






 <div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Time Clocking</font> </p>
 </div>
<br/>
<br/> 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Time Clocking </strong></font></p>

<font color='#FFFFFF' face='courier' size='3'><strong>Press the Clock IN button to record time and Clock Out to stop recording.</strong></font>
<br/><br/>
<p><font color='#FFFFFF' face='courier' size='3'>Date: <input type="text" name="datepicker" id="datepicker" size="30" /></font></p>
<br/><br/>




<input name="ClockIn" id="ClockIn" type="submit" value="Clock In" style="height: 30px; width: 150px; color: #1e5799">
<input name="ClockOut" id="ClockOut" type="submit" value="Clock Out" style="height: 30px; width: 150px; color: #1e5799; margin-left:25px"/>
<input name="edit" id="edit" onClick="return validateForm()" type="submit" value="Edit" style="height: 30px; width: 150px; color: #1e5799; margin-left:25px"/>

</form>
 
</body>
</html>
<?php
session_start();
if(isset($_SESSION['logged_in']))
{
	if($_SESSION['logged_in']!='1')
	{
		header("Location: login.html");
	}
}
else
{
	header("Location: login.html");
}
?>