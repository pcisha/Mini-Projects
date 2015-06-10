<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script>
function validateForm()
{
var e1=document.forms["form1"]["emp_name"].value;
var e2=document.forms["form1"]["emp_id"].value;
var e3=document.forms["form1"]["email_id"].value;
var e4=document.forms["form1"]["fromDatepicker"].value;
var e5=document.forms["form1"]["toDatepicker"].value;


if(e4=="")
{
	alert("Please specify the start date from which you want to generate the report.");
	return false;
}
else if(e5=="")
{
	alert("Please specify the date till which you want to generate the report.");
	return false;	
}

else if (e1=="" && e2=="" && e3=="")
  {
  alert("Please specify at least one search criteria");
  return false;
  }
else
{
	//alert("done");	
}

 
}
</script>


<!------datepicker--------------->
<script>
/*$(function() {
$( "#startdate" ).datepicker();

$( "#enddate" ).datepicker();
});*/


$(document).ready(function(){
    $("#fromDatepicker").datepicker({
        //minDate: 0,
        //maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#toDatepicker").datepicker("option","minDate", selected)
        }
    });
    $("#toDatepicker").datepicker({ 
        minDate: 0,
        //maxDate:"+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#fromDatepicker").datepicker("option","maxDate", selected)
        }
    });  
});




</script>




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
border-radius:20px;
box-shadow: 10px 10px 5px #1e5799;
}



</style>




<title>GENERATE REPORT</title>
</head>

<body>



<form id="form1" name="form1"  method="post" action="report.php" onsubmit="return validateForm()">

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>

<div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | GENERATE REPORT</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>GENERATE REPORT </strong></font></p>

<p><font color='#FFFFFF' face='courier' size='3'>Enter any number of search parameters below. You may choose to fill any number of search parameters with a minimum of one.</font></p>

<!--table creation for fields-->
<table width="500" border="0" cellpadding="20" cellspacing="30" bgcolor='#FFFFFF' class="w900"> 

<tr>
<td width="160">
<label><font color='#1e5799' face='courier' size='3'><strong>Employee Name</strong></font></label></td>
<td width="161">  <input name="emp_name" type="text" class="required" id="emp_name" style="margin-left:17px" size="30" maxlength="200"/></td>
</tr>

  
  <tr>
  <td width="160">
<label><font color='#1e5799' face='courier' size='3'><strong>
  Employee ID</strong></font></label></td>
<td width="161"><input name="emp_id" type="text" class="required" id="emp_id" style="margin-left:17px" size="30" maxlength="100"/></td>
</tr>
  
  <tr>
 <td width="160"> 
  <label><font color='#1e5799' face='courier' size='3'><strong>
Email ID</strong></font></label></td>
<td width="161"><input name="email_id" type="text" class="required" id="email_id" style="margin-left:17px" size="30" maxlength="100"/></td>
</tr>
<tr>
<td width="160"> 
  <label><font color='#1e5799' face='courier' size='3'><strong>
From Date</strong></font></label></td>
<td width="180"><input type="text" name="fromDatepicker" id="fromDatepicker" size="30" style="margin-left:17px"/>
</td>
</tr>
<tr>
<td width="160"> 
  <label><font color='#1e5799' face='courier' size='3'><strong>
To Date</strong></font></label></td>

<td width="160">
<input type="text" name="toDatepicker" id="toDatepicker" size="30" style="margin-left:17px"/>
</td>
</tr>

</table>
<br/>
<p>
  <input name="search" type="submit" value="search" style="height: 30px; width: 150px; color: #1e5799"/>
  </p>
</form>

<p>&nbsp;</p>
</body>
</html>
<?php
session_start();
if(isset($_SESSION['logged_in']))
{
}
else
{
	header("Location: login.html");
}
?>