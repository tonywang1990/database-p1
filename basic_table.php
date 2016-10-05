<?php
// remember 
$_SESSION['table_type'] = "basic";
// read query data
if(isset($_GET['basic_search'])){ 
	$name  = $_GET['name']; 
	$title = $_GET['title'];   
	$dept  = $_GET['dept'];   
}
// default value
$name = "'%".$name."%'";
$title = "'".$title."%'";
$dept= "'".$dept."%'";

// first table -- basic info
$table_name = 'Basic Information';

// query database
if (isset($_GET['basic_search']) or isset($_GET['pub_search']) or isset($_GET['area_search'])){
	$basic_query = "SELECT basic.*, academic.website, academic.resume, academic.scholar, academic.education FROM basic, academic WHERE basic.name like $name AND dept like $dept AND title like $title AND basic.name = academic.name ";
	$_SESSION['basic_query'] = $basic_query;
}
else{
	$basic_query = $_SESSION['basic_query'];
}

$result = mysql_query($basic_query."LIMIT $startrow, 5");
$nrows = mysql_num_rows($result);
if (!$result) {
	echo 'Could not run query: ' . mysql_error();
	exit;
}


// generate table
echo '<table class="w3-table-all w3-hoverable" style="table-layout:fixed; width:100%;">';

// table header
$numberfields = mysql_num_fields($result);
$pic    = $numberfields-5;
$website= $numberfields-4;
$resume = $numberfields-3;
$scholar= $numberfields-2;
$edu    = $numberfields-1;
echo '<thead><tr>';
$field = mysql_field_name($result, $pic);
// image
echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
// name and others
for ($i=1; $i<$numberfields-5 ; $i++ ) {
	$field = mysql_field_name($result, $i);
	echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
}
// education
$field = mysql_field_name($result, $edu);
echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
echo '<tr></thead>';

// table content
while ($row = mysql_fetch_row($result)) {
	echo '<tr>';
	// image
	echo '<td><img src="'.$row[$pic].'" style="width:120px;height:150px;"></td>';
	// name and href link
	$meta_info="<div>$row[1]</br>
		<a href=$row[$website]>Website</a></br>
		<a href=$row[$scholar]>Google scholar</a></br>
		<a href=$row[$resume]>Resume</a></br>
		</div>";
	echo '<td style="word-wrap: break-word;">' .$meta_info . '</td>';
	// other stuff
	for ($i=2; $i<$numberfields-5 ; $i++ ) {
		echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$i]) . '</td>';
	}
	// education
	echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$edu]) . '</td>';
	echo '</tr>';
}
echo '</table>';
?>
