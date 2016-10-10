<?php 
session_start(); 
if(!$_SESSION['logged']){ 
    //header("Location: login_page.php"); 
	echo 'logged out '.$_SESSION['username']; 
    exit; 
} 
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<head>
<title>Database Project 1</title>
</head>
<body>
<form action="query_result.php" method="post">
Name:<br>
<input type="text" name="name"><br><br>
Department:<br>
<input type="text" name="dept"><br><br>
Title:<br>
<input type="text" name="title"><br><br>
<input type="submit" name="Search" value="Search">
</form>

<?php
echo "CSCE 608 Database Project 1\n";
$link = mysql_connect( $_SESSION['host'], $_SESSION['username'], $_SESSION['password'],$_SESSION['database']);
//echo $_SESSION['host'], $_SESSION['username'], $_SESSION['password'],$_SESSION['database'];
//$link = mysql_connect('database2.cs.tamu.edu', 'tonybest', '11235813', 'tonybest-db1');
// select database
/*
$res = mysql_query("SHOW DATABASES");

while ($row = mysql_fetch_assoc($res)) {
	echo $row['Database'] . "<br />\n";
}
*/
// choose tonybest-prof database
$db_selected = mysql_select_db('tonybest-prof', $link);
if (!$db_selected) {
	die ('Can\'t use db: ' . mysql_error());
}

echo 'Basic Information';
$name = "'Robert Balog'";
$title = "'Associate Professor'";
$result = mysql_query("SELECT * FROM basic WHERE title='Associate Professor'");
if (!$result) {
	echo 'Could not run query: ' . mysql_error();
	exit;
}

echo '<table class="w3-table-all w3-hoverable w3-card-4">';
// show table content
while ($row = mysql_fetch_row($result)) {
	echo '<tr>';
	foreach($row as $field) {
		echo '<td>' . htmlspecialchars($field) . '</td>';

	}
	echo '</tr>';
}
echo '</table>';

mysql_close($link);
?>


</body>
</html>
