<?php
// remember 
$_SESSION['table_type'] = "publication";
// read query data
if(isset($_GET['pub_search'])){ 
	$name  = $_GET['name']; 
	$ptitle = $_GET['ptitle'];   
	$dept  = $_GET['dept'];   
}
// default value
$name = "'%".$name."%'";
$ptitle = "'%".$ptitle."%'";
$dept= "'".$dept."%'";

// first table -- basic info
$table_name = 'Publication Information';

// connect to my sql
include('connect_db.php');

// query database
if (isset($_GET['basic_search']) or isset($_GET['pub_search']) or isset($_GET['area_search'])){
	$basic_query = "SELECT publication.id, publication.author, basic.dept, basic.photo, publication.author, academic.name, academic.website, academic.resume,academic.scholar, publication.paper FROM basic, academic, publication WHERE basic.name like $name AND basic.dept like $dept AND publication.paper like $ptitle AND basic.name = publication.author AND basic.name = academic.name ";
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
$id     = 0;
$pic    = $numberfields-7;
$website= $numberfields-4;
$resume = $numberfields-3;
$scholar= $numberfields-2;
$paper  = $numberfields-1;
echo '<thead><tr>';
$field = mysql_field_name($result, $pic);
// image
echo '<th style="width:10%">' . htmlspecialchars(ucfirst($field)) . '</th>';
// name and department
for ($i=1; $i<$numberfields-7; $i++ ) {
	$field = mysql_field_name($result, $i);
	echo '<th style="width:15%">' . htmlspecialchars(ucfirst($field)) . '</th>';
}
// paper
$field = mysql_field_name($result, $paper);
echo '<th>' . htmlspecialchars(ucfirst($field)) . '</th>';
// edit/delete function
echo '<th style="width:10%">Operation</th>';
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
	// dept 
	for ($i=2; $i<$numberfields-7 ; $i++ ) {
		echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$i]) . '</td>';
	}
	// paper
	echo '<td style="word-wrap: break-word;">' . htmlspecialchars($row[$paper]) . '</td>';
	// enable delete/edit the current row information in db
        $meta_info="<div><a href=edit_publication.php?" . SID . "id=$row[$id]>Edit</a></br>
                         <a href=delete.php?" . SID . "id=$row[$id]>Delete</a></br>
                   </div>";
	echo '<td style="word-wrap: break-word;">' .$meta_info . '</td>';
	echo '</tr>';
}
echo '</table>';

mysql_close($connection);

?>
