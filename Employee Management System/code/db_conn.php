<?php
//session_start();
$host="silo.cs.indiana.edu"; // Host name 
$username="b561f13_amlakhan"; // Mysql username 
$password="password"; // Mysql password 
$db_name="b561f13_amlakhan"; // Database name                            
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
?>