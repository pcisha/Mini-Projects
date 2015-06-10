<!DOCTYPE html> 
<html lang="en">
<head>
<!--<script src="js/htmlDatePicker.js" type="text/javascript"></script>
	<link href="css/htmlDatePicker.css" rel="stylesheet">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">-->

<!---jquery --->
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />


<title>Leave Request Form</title>
<!-----css------->
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






</head>
<body>
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

include 'db_conn.php';
?>
<script type="text/javascript">

function validateForm(leaveblnc)
{
//alert("validate form 1");
//var e1=document.getElementByName("retype")[0].value;
//alert("e1");

var e = document.getElementById("retype");
var strUser = e.options[e.selectedIndex].value;
//if you need text to be compared then use
var strUser1 = e.options[e.selectedIndex].text;
if(strUser==0) //for text use if(strUser1=="Select")
{
alert("Please select a leave type");
return false;
}

var e2=document.forms["form1"]["startdate"].value;
//alert("e2");
var e3=document.forms["form1"]["enddate"].value;
//alert("e3");
//alert("validate form 2");
/*if (e1.value == "retype")
	{
    alert("Please select a leave type");
	}*/
 
if (e2=="")
  	{
  	alert("Please specify a start date");
  	return false;
  	}

else if (e3=="")
  	{
  	alert("Please specify an end date");
  	return false;
 	 }

else
	{
	//alert("All the fields are filled");	
	//alert("ba");
	
	}

<!--the leave balance check-->
var stDate = new Date(document.form1.startdate.value);
//alert(stDate);

	var enDate = new Date(document.form1.enddate.value);
	//alert(enDate);
	var diffDays = Math.round(Math.abs((stDate.getTime() - enDate.getTime())/(60*60*24*1000)));
	
	
	//var diffDays = stDate-enDate;
	
		if(stDate > enDate)
		{
		alert("End date should be on or after the starting date");
		return false;
		}
		else
		{
			//alert("everything kool");
			//alert(diffDays);
			//alert(leaveblnc);
		if (diffDays > leaveblnc)
	    {
		alert("Not enough leave balance");
		return false;
		}
		else
		document.form1.submit();
		}
	


}
/*function doSubmit(leaveblnc)
{
	//alert(leaveblnc);
	var stDate = new Date(document.form1.startdate.value);
	var enDate = new Date(document.form1.enddate.value);
	var diffDays = Math.round(Math.abs((stDate.getTime() - enDate.getTime())/(60*60*24*1000)));
	//var diffDays = stDate-enDate;
	//alert(diffDays);
	if(stDate > enDate)
		alert("End date should be on or after the starting date");
	else if (diffDays > leaveblnc)
	    alert("Not enough leave balance");
	else
		document.form1.submit();
	
	
}*/
function enableReason(re)
{
	//alert(re.selectedIndex);
	if(re.selectedIndex == 4)
	{
		document.form1.reason.disabled = false;
		//alert("Enabled");
	}
	else
	{
		document.form1.reason.disabled = true;
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
    $("#startdate").datepicker({
        minDate: 0,
        //maxDate: "+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#enddate").datepicker("option","minDate", selected)
        }
    });
    $("#enddate").datepicker({ 
        //minDate: 0,
        //maxDate:"+60D",
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#startdate").datepicker("option","maxDate", selected)
        }
    });  
});




</script>



<form id="form1" name="form1" method="post" action="leave_submit.php" > 


<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>



   <div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>






 <div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Leave Management</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Apply Leave </strong></font></p>




<table width="434" border="0" cellpadding="0" cellspacing="15" bgcolor='#FFFFFF' class="w900">
<tr>
<td width="300"><font color='#1e5799' face='courier' size='3'><strong>Leave Type</strong></font></td>
<td>
<select name='retype' id='retype' onchange="enableReason(this);" >

<?php
//echo "<select name='retype' id='retype' onchange='enableReason(this);'>";

$query="SELECT * from leave_type";
$sql=mysql_query($query);
echo"<font color='#1e5799' face='courier' size='3'>";  
echo"<option value='0'>--Select Leave Type--</option>";
$i = 1;
while($row1 = mysql_fetch_array($sql))
	{
	$tname=$row1['type_name'];
	$type_id=$row1['type_id'];
	echo $tname;
	echo"<option value=".$type_id.">".$tname."</option>";	
	$i=$i+1;
	}
	
	echo"</font>";
	echo"</select>";
	?>

</select>
</td>  

<tr>
  <div class='form-group'>
<label for="from_date"><td width="160"><font color='#1e5799' face='courier' size='3'><strong>Start Date</strong></font></td>
            </label>
<td width="160"><input type="text" name="startdate" id="startdate" readonly onClick="GetDate(this);" required/></td></div></tr>

<tr> 
 <div class='form-group'>
<td width="160">  <label for="to_date"><font color='#1e5799' face='courier' size='3'><strong>End Date</strong></font></label></td>
<td width="160"><input type="text" name="enddate" id="enddate" readonly onClick="GetDate(this);" required />    </td></div>    

<tr>    
    <div class='form-group'>
<td width="200"><label for="other_reasons"><font color='#1e5799' face='courier' size='3'><strong>If others,give a reason</strong></font></label>
<td width="160"><input type="text" name="reason" id="reason" value="" disabled/></td>
      
      </div>
</tr>
</table>

<br/>
  <p><a href="leave_history.php"><font color='#0B0B61' face='courier' size='3'><strong>History</strong></font></a></p>
   
  
<?php 
$emp_id=$_SESSION['employee_id'];

$query=mysql_query("select leave_bal from employee where employee_id='$emp_id'");
$leaveblnc = mysql_fetch_assoc($query);
$leaveblnc=$leaveblnc['leave_bal'];
echo"<br/>";
echo "<font color='#0B0B61' face='courier' size='3'><strong>Leave Balance ".$leaveblnc;
$disabled_btn=false;
if ($leaveblnc <= 0)
{
	$disabled_btn=true; 
} 
if($disabled_btn==true) 
{
	//echo  "<a href='leave_submit.php'></a><input style='margin-left:20px' type='submit' name='buttonsubmit' id='buttonsubmit' value='Request Emergency Leave' />";
	//echo "<button type='button' class='btn btn-primary' onclick='doSubmit($leaveblnc)'>Submit</button>";
	echo "<br>";
	echo "<br>";
echo"<input type='submit' value='Request Emergency Leave' style='height: 30px; width: 200px; color: #1e5799'/>";	
echo "<button type='reset' value='Reset' style='height: 30px; width: 150px;margin-left:25px; color: #1e5799'>Reset</button>";
}
else
{
	echo "<br>";
	echo "<br>";
	echo"<input type='submit' value='Submit' style='height: 30px; width: 150px; color: #1e5799' onClick='return validateForm($leaveblnc)'/>";
	echo "<button type='reset' value='Reset' style='height: 30px; width: 150px;margin-left:25px; color: #1e5799'>Reset</button>";
}

?>
<br/><br/>

			<!--<input type="submit" value="Submit" style="height: 30px; width: 150px; color: #1e5799"/>-->
            
</form>
</body>
</html>