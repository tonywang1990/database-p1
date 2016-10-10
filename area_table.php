<?php
// remember 
$_SESSION['table_type'] = "research";
// read query data
if(isset($_GET['area_search'])){ 
	$name  = $_GET['name']; 
	$area = $_GET['area'];   
	$dept  = $_GET['dept'];   
}
// default value
$name = "'%".$name."%'";
$area = "'%".$area."%'";
$dept= "'".$dept."%'";

// connect to my sql
include('connect_db.php');

// first table -- basic info
$table_name = 'Research Area';

// query database
if (isset($_GET['basic_search']) or isset($_GET['pub_search']) or isset($_GET['area_search'])){
	$basic_query = "SELECT research.id, basic.name, basic.dept, basic.email, basic.photo, research.name, academic.website, academic.resume, academic.scholar, research.area FROM basic, academic, research WHERE basic.name like $name AND basic.dept like $dept AND research.area like $area AND basic.name = research.name AND basic.name = academic.name ";
	$_SESSION['basic_query'] = $basic_query;
}
else{
	$basic_query = $_SESSION['basic_query'];
}

$result = mysql_query($basic_query."LIMIT $startrow, 5");
if (!$result) {
	echo 'You query is like '.$basic_query."LIMIT $startrow, 5";
	echo 'But we could not that run query! ' . mysql_error();
	exit;
}
$nrows = mysql_num_rows($result);

// generate table
echo '<table class="w3-table-all w3-hoverable" style="able-layout:fixed; width:100%;">';

// table header
$numberfields = mysql_num_fields($result);
$id            = 0;
$pic           = $numberfields-6;
$website       = $numberfields-4;
$resume        = $numberfields-3;
$scholar       = $numberfields-2;
$researcharea  = $numberfields-1;
echo '<thead><tr>';
$field = mysql_field_name($result, $pic);
// image
echo '<th style="width:10%">' . htmlspecialchars(ucfirst($field)) . '</th>';
// name, department and email
for ($i=1; $i<$numberfields-6; $i++ ) {
	$field = mysql_field_name($result, $i);
	echo '<th style="width:12%">' . htmlspecialchars(ucfirst($field)) . '</th>';
}
// research area
$field = mysql_field_name($result, $researcharea);
echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
echo '<th style="width:10%">Action</th>';
echo '<tr></thead>';

// table content
while ($row = mysql_fetch_row($result)) {
	echo '<tr>';
	// image
	echo '<td><img src="'.$row[$pic].'" style="width:120px;height:150px;"></td>';
	// name and href link
	$meta_info="<div>$row[1]</br><a href=$row[$website]>Website</a></br>";
	if($row[$scholar] != '')
		$meta_info = $meta_info."<a href=$row[$scholar]>Google scholar</a></br>";
	if($row[$resume] != '')
		$meta_info = $meta_info."<a href=$row[$resume]>Resume</a></br>";
	$meta_info = $meta_info."</div>";
	echo '<td style="word-wrap: break-word;">' .$meta_info . '</td>';
	// dept and email
	for ($i=2; $i<$numberfields-6 ; $i++ ) {
		if($i == $numberfields-7){
			$meta_info="<a href=mailto:$row[$i]?Subject=Howdy!>$row[$i]</a>";
			echo '<td style="word-wrap: break-word;">' .$meta_info . '</td>';
		}
		else
			echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$i]) . '</td>';
	}
	// area
	echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$researcharea]) . '</td>';
	// enable delete/edit the current row information in db
        $meta_info="<div><a href=edit_area.php?" . SID . "id=$row[$id]>Edit</a></br>
                         <a href=delete.php?" . SID . "id=$row[$id]>Delete</a></br>
                   </div>";
        echo '<td style="word-wrap: break-word;">' .$meta_info . '</td>';	
	echo '</tr>';
}
echo '</table>';

mysql_close($connection);

?>
