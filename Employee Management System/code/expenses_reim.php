<!DOCTYPE html> 
<html lang="en">
<head>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.min.css" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<title>Expenses Request Form</title>
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
//$host="localhost"; // Host name
//$username="root"; // Mysql username
//$password=""; // Mysql password
//$db_name="employeemanagement"; // Database name
//mysql_connect("$host", "$username", "$password")or die("cannot connect");
//mysql_select_db("$db_name")or die("cannot select DB");
//session_start();
include 'db_conn.php';
?>
<script type="text/javascript">
function validateForm(Expnsblnc)
{
//alert("validate form 1");
//var e1=document.getElementByName("retype")[0].value;
//alert("e1");
//alert("validate form");
var e1 = document.getElementById("retype");
var e2 = e1.options[e1.selectedIndex].value;
//if you need text to be compared then use
var e3 = e1.options[e1.selectedIndex].text;

if(e2==0) //for text use if(strUser1=="Select")
{
alert("Please select an expense type");
return false;
//alert("aftr exp tp");
}

var cd=document.forms["form1"]["ClaimDate"].value;
//alert("cd");
var ca=document.forms["form1"]["ClaimAmount"].value;
//alert("ca");
	

 
if (cd=="")
  	{
  	alert("Please specify a claim date");
  	return false;
  	}

if (ca=="")
  	{
  	alert("Please specify a claim amount");
  	return false;
  	}

	
if(ca >= Expnsblnc)
		{
		alert("This claim cannot be processed. Please contact your supervisor for details.");
		return false;
		}
	else
		document.form1.submit();
}


/*function doSubmit(expnsblnc)
{
	//alert(leaveblnc);
	var clmdate = document.form1.ClaimDate.value;
	var clmamt = document.form1.ClaimAmount.value;
	alert(clmdate);
	alert(clmamt);
	if(clmamt > expnsblnc)
		alert("Not enough Expenses balance");
	
	else
		document.form1.submit();
	
	
}*/
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) 
        alert("Invalid Input");
    charCode='';
    return charCode;
     
}
function enableReason(re)
{
	//alert(re.selectedIndex);
	if(re.selectedIndex == 3)
	{
		document.form1.reason.disabled = false;
		//alert("Enabled");
	}
	else
	{
		document.form1.reason.disabled = true;
	}
	
}


$(function() {
$( "#ClaimDate" ).datepicker({  maxDate: '0'});
});


</script>
<form id="form1" name="form1" method="post" action="expenses_submit.php">

<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>



   <div align="right"><a href='login.html'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>






 <div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Expenses Reimbursements</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Apply Expense Reimbursements </strong></font></p>
<br/><br/>

 
<table width="500" border="0" cellpadding="0" cellspacing="15" bgcolor='#FFFFFF' class="w900">
<tr>
<td width="300"><font color='#1e5799' face='courier' size='3'><strong>Claim Type</td>
<td width='300'><font color='#1e5799' face='courier' size='3'>
<select name='retype' id='retype' onchange="enableReason(this);" >
<?php

$query="SELECT * from claim_tp";
$sql=mysql_query($query);  
echo"<option value='0'>--Select Claim Type--</option>";
$i = 1;
while($row1 = mysql_fetch_array($sql))
	{
	$tname=$row1['type_name'];
	$type_id=$row1['type_id'];
	echo $tname;
	echo"<option value=".$type_id.">".$tname."</option>";	
	$i=$i+1;
	}

	?>
</select>
</td>

  <div class='form-group'>
<label for="from_date"><tr>
<td width="300"><font color='#1e5799' face='courier' size='3'><strong>Claim Date</strong></font>
            </label><td width="300"><font color='#1e5799' face='courier' size='3'>
<input type="text" name="ClaimDate" id="ClaimDate" readonly onClick="GetDate(this);" required/></td> 
</div>
 <div class='form-group'>
<tr>  <td width="300"><font color='#1e5799' face='courier' size='3'><label for="to_date"><strong>Claim Amount</strong>
      </label></td>
 <td width="300"><font color='#1e5799' face='courier' size='3'>     
<input type="text" name="ClaimAmount" id="ClaimAmount" value="" onkeypress = "isNumber(event)"/></td>   
 </tr> </div>
    <div class='form-group'>
    <tr><td width="300">
    <label for="other_reasons"><font color='#1e5799' face='courier' size='3'><strong>If others,give a reason<strong></font></label></td>
      <td width="300"><input type="text" name="reason" id="reason" value="" disabled />
      </td></tr>
      </div>

</table>
 <br/><br/>
  <p><a href="expenses_history.php"><font color='#1e5799' face='courier' size='3'><strong>History</strong></font></a></p>
   
  <br/><br/>
<!-----adding check for allowable expense on budget--->
<?php 
$emp_id=$_SESSION['employee_id'];
$query=mysql_query("SELECT p.budget AS expenses_bal
FROM expenses e, project p, works_on w
WHERE e.employee_id =  '$emp_id'
AND e.employee_id = w.employee_id
AND p.project_id = w.project_id");
$Expnsblnc = mysql_fetch_assoc($query);
$Expnsblnc=$Expnsblnc['expenses_bal'];
//echo "Expenses Balance ".$Expnsblnc;
echo "<input type='button' value='Submit' style='height: 30px; width: 150px; color: #1e5799' onclick='validateForm($Expnsblnc)'></button>";
//onclick='doSubmit($Expnsblnc)'
?>

<!--<input type="submit" value="Submit" style="height: 30px; width: 150px; color: #1e5799"></button>-->
			<button type="reset" value="Reset" style="height: 30px; margin-left:25px;width: 150px; color: #1e5799">Reset</button>
</form>
</body>
</html>