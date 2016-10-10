<?php
/*

CONNECT_DB.PHP

Allows PHP to connect to your database

*/
session_start();


// Database Variables (edit with your own server information)
$server = $_SESSION['host'];
$user = $_SESSION['username'];
$pass = $_SESSION['password'];
$db = $SESSION['database'];

// Connect to Database
$connection = mysql_connect($server, $user, $pass, $db)
or die ("Could not connect to server ".$server.", ".$user.", ".$pass.", ".$db."... \n" . mysql_error ());

$db_name = 'tonybest-prof';
mysql_select_db($db_name, $connection)
or die ("Could not connect to database ... \n" . mysql_error ());



?>
