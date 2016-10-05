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


<body style="position:absolute; left:2%; right:2%">
<h1>CSCE 608 Database Project 1</h1>
<div class="w3-card-4">

<div class="w3-container w3-green">
  <h2>Professor Search Engine</h2>
</div>

<div class="w3-panel w3-padding-4"></div>
<form action="users_page.php" method="post" class="w3-container">

<label class="w3-label">Name</label>
<input class="w3-input" type="text" name="name">

<label class="w3-label">Department</label>
<input class="w3-input" type="text" name="dept">

<label class="w3-label">Title</label>
<input class="w3-input" type="text" name="title">

<input class="w3-btn-block w3-teal" type="submit" name="Search" value="Search">
</form>

<div class="w3-panel w3-padding-8"></div>
</div>


<div class="w3-panel w3-padding-16">
</div>

<div class="w3-card-4">
<div class="w3-container w3-blue">
<h2>Search Results</h2>
</div>
<?php
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
$db_name = 'tonybest-prof';
$db_selected = mysql_select_db($db_name, $link);
if (!$db_selected) {
	die ('Can\'t use db: ' . mysql_error());
}

// read query data
if(isset($_POST['Search'])){ 
	$name  = $_POST['name']; 
	$title = $_POST['title'];   
	$dept  = $_POST['dept'];   
}
// default value
$name = "'%".$name."%'";
$title = "'".$title."%'";
$dept= "'".$dept."%'";

//check if the starting row variable was passed in the URL or not
if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
	//we give the value of the starting row to 0 because nothing was found in URL
	$startrow = 0;
	//otherwise we take the value from the URL
} else {
	$startrow = (int)$_GET['startrow'];
}

// first table -- basic info
$table_name = 'Basic Information';
echo '<div class="w3-container w3-blue">';
echo '<h3>'.$table_name.'</h3>';
echo '</div>';
// query database
$query = "SELECT * FROM basic WHERE name like $name AND dept like $dept AND title like $title LIMIT $startrow, 5";
$result = mysql_query($query);
if (!$result) {
	echo 'Could not run query: ' . mysql_error();
	exit;
}
// generate table
echo '<table class="w3-table-all w3-hoverable" style="table-layout:fixed; width:100%;">';

// table header
$numberfields = mysql_num_fields($result);
echo '<thead><tr>';
$last = $numberfields-1;
$field = mysql_field_name($result, $last);
echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
for ($i=1; $i<$numberfields-1 ; $i++ ) {
	$field = mysql_field_name($result, $i);
	echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
}
echo '<tr></thead>';
// show table content
while ($row = mysql_fetch_row($result)) {
	echo '<tr>';
	$last = $numberfields-1;
	echo '<td><img src="'.$row[$last].'" style="width:120px;height:150px;"></td>';
	for ($i=1; $i<$numberfields-1 ; $i++ ) {
		echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$i]) . '</td>';
	}
	echo '</tr>';
}
echo '</table>';

// add page number
//echo '<form method="get" class="w3-container w3-blue">';
echo '<div align="right">';
$prev = $startrow - 10;
//only print a "Previous" link if a "Next" was clicked
if ($prev >= 0)
	echo '<a class="w3-btn w3-blue" href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'">Previous</a>';
else 
	echo '<a class="w3-btn w3-disabled">Previous</a>';
echo '<a class="w3-btn w3-blue" href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+10).'">Next</a>';
//echo '</form>';
echo '</div>';
mysql_close($link);
?>
</div>

</body>
</html>
