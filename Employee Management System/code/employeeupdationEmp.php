<!--
/****************************************************************
** P561
*****************************************************************/
-->
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Employee Information</title>
  <link type="text/css" rel="stylesheet" href="css/pageStyle.css" />
  <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
</head>
<script>
var employeeid;

$( document ).ready(function() {
	
			$.getJSON('getinfoEmp.php',function(data){
					{
						employeeid = data.employeeid;
						var name = data.name;
						//alert(name);
						var arr = name.split(' ');
						
						$("#firstname").val(arr[0]);
						$("#lastname").val(arr[1]);
						$("#address").val(data.address);
						$("#email").val(data.email);
						$("#de").val(data.designation);
						$("#phoneno").val(data.phonenumber);
						$("#place").val(data.place);
						
						$.getJSON('getSkillsEmp.php',function(data){
						{
							//alert("Got back the data");
							var myarray = new Array();
							$.each(data, function(key, value) { 
								myarray[key] = value;

							});

							for (i=0;i<myarray.length;i++)
							{
								//alert(myarray[i]);
								$("#skill"+myarray[i]).prop("checked", true);
								//alert("Done");
								//$("").prop('checked',true);
							}
							//$('.myCheckbox').prop('checked', true);
						}
				
						});

						$('#email').prop("disabled", true);
						$('#de').prop("disabled", true);

						
					}
				
			});

});

</script>
<script type="text/javascript">
function validateForm ( ) { 
    var isValid = true;
	    
	if ( document.form1.firstname.value == "" ) { 
	    alert ( "First Name cannot be blank" );
		document.form1.firstname.focus() ;
	    isValid = false;
		return isValid;
    } 
	
	if ( document.form1.lastname.value == "" ) { 
            alert ( "Last Name cannot be blank" ); 
			document.form1.lastname.focus() ;
            isValid = false;
			return isValid;
    } 

	if ( document.form1.address.value == "" ) { 
            alert ( "Address cannot be blank" ); 
			document.form1.address.focus() ;
            isValid = false;
			return isValid;
    } 
	
	if ( document.form1.email.value == "" ) { 
            alert ( "Email cannot be blank" ); 
			document.form1.email.focus() ;
            isValid = false;
			return isValid;
    } 
	
	if ( document.form1.phoneno.value == "" ) { 
            alert ( "Phone Number cannot be blank" ); 
			document.form1.phone.focus() ;
            isValid = false;
			return isValid;
    }
	
	if ( document.form1.place.value == "" ) { 
            alert ( "Location cannot be blank" ); 
			document.form1.place.focus() ;
            isValid = false;
			return isValid;
    }
	
	var dropdowncheck = document.getElementById("de");
	var employeeType = dropdowncheck.options[dropdowncheck.selectedIndex].value;
	if(employeeType==0)
	{
		alert("Please select the Designation of the Employee");
		document.getElementById("de").focus();
		isValid = false;
		return isValid;
	}
	
	
	var checkboxcheck = document.getElementsByName('skills[]');
	var totalCheckBoxChecked = 0;
	for (var i = 0; i < checkboxcheck.length; i++)
	{
		if (checkboxcheck[i].checked)
		{
			totalCheckBoxChecked = 1;
			break;
		}

	}
	
	if (totalCheckBoxChecked==0)
	{
		alert("Select the Skills of the Employee");
		//document.getElementsByName('skills[]').focus();
		isValid = false;
		return isValid;
	}
	
	var x=document.form1.email.value;
		
	var atpos=x.indexOf("@");
	var dotpos=x.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
	{
		alert("Not a valid e-mail address.....Please enter a valid email address");
		document.form1.email.focus() ;
		isValid = false;
		return isValid;
	}
	
	var phonenocompare = /^\d{10}$/;
	
	if(!(document.form1.phoneno.value.match(phonenocompare)))  
	{  
		alert("Not a valid Phone Number"); 
		document.form1.phoneno.focus() ;
        isValid = false;
		return isValid;
	}  
	
	
	/*
	else if (!(stripped.length == 10)) {
        alert("The phone number is the wrong length. Make sure you included an area code");
        document.form1.phoneno.focus() ;
		isValid = false;
    }
    */
	return isValid;
}

function clearValues()
{
document.getElementById("firstname").val="";
document.getElementById("lastname").val="";
document.getElementById("address").val="";
document.getElementById("phoneno").val="";
document.getElementById("email").val="";
//document.getElementById("designation").val="";
document.getElementById("place").val="";
//document.getElementById("skills").val="";
document.getElementById('firstname').focus();
}

</script>

<body>
<h3 align="left" class="col-xs-offset-0"><strong><font  color='#FFFFFF' face="courier" size='9'>EMPLOYEE MANAGEMENT PORTAL</font></strong></h3>
<hr>

<div align="right"><a href='logout.php'><font color='#FFFFFF' face='courier' size='3'>Logout</font></a></div>

<div align="left">
   <p><a href='home.php'><font color='#FFFFFF' face='courier' size='3'><u>Home</u></font></a>  <font color='#FFFFFF' face='courier' size='3'> | Employee Information</font> </p>
 </div>
 
 <div align="center">
      <p><font color='#FFFFFF' face='courier' size='5'><strong>Employee Information </strong></font></p>
</div>

<p>&nbsp;</p>


<div id="second">
<form name="form1" method="post" action="employeeupdateintodb.php">
<table border="0" align="center">
<tr>
<td>First Name </td>
<td>
<input type="text" name="firstname" id="firstname"> </td>
</tr>
<tr>
<td>Last Name </td>
<td>
<input type="text" name="lastname" id="lastname"> </td>
</tr>
<tr>
<td>Address</td>
<td><input type="text" name="address" id="address" size"=45"></td>
</tr>
<tr>
<td> Phone Number </td>
<td><input type="text" name="phoneno" id="phoneno"></td>
</tr>
<tr>
<td> Email</td>
<td><input type="text" name="email" id="email"></td>
</tr>
<tr>
<td>Designation</td>
<td>
<select id="de" name="de" >
<option value="0">Select One
<?php

$dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
    or die('Error connecting to MySQL server.');

  $query = "SELECT designation_id, designation_name FROM designation_mapping";

  $result = mysqli_query($dbc, $query)
    or die('Error querying database.');

	while($rowdsg = $result->fetch_array())
	{
		$desigrows[] = $rowdsg;
	}

	foreach($desigrows as $rowd)
	{
		echo '<option value="'.$rowd["designation_id"].'">'.$rowd["designation_name"];
	}
	echo '<br>';
 
  mysqli_close($dbc);

?>
</select>
</td>
</tr>
<tr>
<td>Location</td>
<td>
<input type="text" name="place" id="place"></td>
</tr>
<tr>
<td> Skills: </td>
<td>
<?php

$dbc = mysqli_connect('silo.cs.indiana.edu', 'b561f13_amlakhan', 'password', 'b561f13_amlakhan')
    or die('Error connecting to MySQL server.');

  $query = "SELECT skill_id, skill_name FROM skill";

  $result = mysqli_query($dbc, $query)
    or die('Error querying database.');

	while($rowskill = $result->fetch_array())
	{
		$skillrows[] = $rowskill;
	}

	foreach($skillrows as $rowsk)
	{
		echo '<input type="checkbox" id="skill'.$rowsk["skill_id"].'" name="skills[]" value="'.$rowsk["skill_id"].'">'.$rowsk["skill_name"];
	}
	echo '<br>';
 
  mysqli_close($dbc);

?>
</td>
</tr>
</table>

<p>&nbsp;</p>
<p align="center">
<input class="button" name="button" type="submit" value="Submit Information" onClick="javascript:return validateForm();"/>
<!--
<input class="button" type="reset" name="Reset" value="Reset" onClick="clearValues()">
-->
</p>
</form> 
</div>
</body>
</html> 
