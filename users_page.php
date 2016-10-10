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


<body style="margin:2%" class="w3-large">
<div class="w3-card-4">

<div class="w3-container w3-orange">
  <h2>Professor Search Engine</h2>
</div>


<!--First search box: basic search, natral join of basic and academic-->
<div class="w3-card-4" style="margin:1%">
<div class="w3-container w3-green">
	<h3>Basic Information</h3>
</div>
<form action="users_page.php" method="get" class="w3-row-padding">
<div class="w3-third">
<label class="w3-label">Faculty Name</label>
<input class="w3-input w3-border" type="text" name="name">
</div>
<div class="w3-third">
<label class="w3-label">Department</label>
<select class="w3-select w3-border" type="text" name="dept">
    <option value="" disabled selected>Choose the department</option>
    <option value="Electrical">Electrical and Computer Engineering</option>
    <option value="Computer">Computer Science and Engineering</option>
    <option value="Material">Material Science and Engineering</option>
    <option value="Aerospace">Aerospace Engineering</option>
    <option value="Biomedical">Biomedical Engineering</option>
    <option value="Chemial">Chemial Engineering</option>
    <option value="Civil">Civil Engineering</option>
    <option value="Engineering Technology and Industrial">Engineering Technology and Industrial</option>
    <option value="Industrial">Industrial and Systems Engineering</option>
    <option value="Nuclear">Nuclear Engineering</option>
    <option value="Ocean">Ocean Engineering</option>
    <option value="Petroleum">Petroleum Engineering</option>
</select>
</div>
<div class="w3-third">
<label class="w3-label">Title</label>
<input class="w3-input w3-border" type="text" name="title">
</div>
<div class="w3-panel w3-padding-1"></div>
<div align="right">
<input class="w3-btn w3-lime" type="button" name="insert" value="Add New Professor" onClick="Javascript:window.location.href = 'insert.php';"/>
<input class="w3-btn w3-green" type="submit" name="basic_search" value="Basic Search">
</div>
<div style="padding:5px"></div>
</form>
</div>


<!--Second search box: publication search, natral join of basic and publication-->
<div class="w3-card-4" style="margin:1%">
<div class="w3-container w3-teal">
	<h3>Publications</h3>
</div>
<form action="users_page.php" method="get" class="w3-row-padding">
<div class="w3-third">
<label class="w3-label">Author Name</label>
<input class="w3-input w3-border" type="text" name="name">
</div>
<div class="w3-third">
<label class="w3-label">Department</label>
<select class="w3-select w3-border" type="text" name="dept">
    <option value="" disabled selected>Choose the department</option>
    <option value="Electrical">Electrical and Computer Engineering</option>
    <option value="Computer">Computer Science and Engineering</option>
    <option value="Material">Material Science and Engineering</option>
    <option value="Aerospace">Aerospace Engineering</option>
    <option value="Biomedical">Biomedical Engineering</option>
    <option value="Chemial">Chemial Engineering</option>
    <option value="Civil">Civil Engineering</option>
    <option value="Engineering Technology and Industrial">Engineering Technology and Industrial</option>
    <option value="Industrial">Industrial and Systems Engineering</option>
    <option value="Nuclear">Nuclear Engineering</option>
    <option value="Ocean">Ocean Engineering</option>
    <option value="Petroleum">Petroleum Engineering</option>
</select>
</div>
<div class="w3-third">
<label class="w3-label">Publication Title</label>
<input class="w3-input w3-border" type="text" name="ptitle">
</div>
<div class="w3-panel w3-padding-1"></div>
<div align="right">
<input class="w3-btn w3-teal" type="submit" name="pub_search" value="Publication Search">
</div>
<div style="padding:5px"></div>
</div>
</form>


<!--Third search box: Area search, natral join of basic and research-->
<div class="w3-card-4" style="margin:1%">
<div class="w3-container w3-indigo">
	<h3>Research Area</h3>
</div>
<form action="users_page.php" method="get" class="w3-row-padding">
<div class="w3-third">
<label class="w3-label">Faculty Name</label>
<input class="w3-input w3-border" type="text" name="name">
</div>
<div class="w3-third">
<label class="w3-label">Department</label>
<select class="w3-select w3-border" type="text" name="dept">
    <option value="" disabled selected>Choose the department</option>
    <option value="Electrical">Electrical and Computer Engineering</option>
    <option value="Computer">Computer Science and Engineering</option>
    <option value="Material">Material Science and Engineering</option>
    <option value="Aerospace">Aerospace Engineering</option>
    <option value="Biomedical">Biomedical Engineering</option>
    <option value="Chemial">Chemial Engineering</option>
    <option value="Civil">Civil Engineering</option>
    <option value="Engineering Technology and Industrial">Engineering Technology and Industrial</option>
    <option value="Industrial">Industrial and Systems Engineering</option>
    <option value="Nuclear">Nuclear Engineering</option>
    <option value="Ocean">Ocean Engineering</option>
    <option value="Petroleum">Petroleum Engineering</option>
</select>
</div>
<div class="w3-third">
<label class="w3-label">Research Area</label>
<input class="w3-input w3-border" type="text" name="area">
</div>
<div class="w3-panel w3-padding-1"></div>
<div align="right">
<input class="w3-btn w3-indigo" type="submit" name="area_search" value="Research Area Search">
</div>
<div style="padding:5px"></div>
</div>
</form>

<div class="w3-panel w3-padding-8"></div>
</div>
<!--End of search box-->

<div class="w3-panel w3-padding-16"></div>

<div class="w3-card-4">
<div class="w3-container w3-blue">
<h2>Search Results</h2>
</div>
<?php

//check if the starting row variable was passed in the URL or not
if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
	//we give the value of the starting row to 0 because nothing was found in URL
	$startrow = 0;
	// if new search, reset the session infomation
	$_SESSION['table_type']="";
	//otherwise we take the value from the URL
} else {
	$startrow = (int)$_GET['startrow'];
}

// basic information search result
if (isset($_GET['basic_search']) or $_SESSION['table_type'] == "basic"){
	include 'basic_table.php';
}
// publication information search result
if (isset($_GET['pub_search']) or $_SESSION['table_type'] == "publication"){
	include 'pub_table.php';
}
// research area information search result
if (isset($_GET['area_search']) or $_SESSION['table_type'] == "research"){
	include 'area_table.php';
}

// add page number
echo '<form action="users_page.php" method="get">';
echo '<div align="right">';
$prev = $startrow - 5;
//only print a "Previous" link if a "Next" was clicked
if ($prev >= 0)
	echo '<a class="w3-btn w3-blue" href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'">Previous</a>';
else 
	echo '<a class="w3-btn w3-disabled">Previous</a>';
if ($nrows == 5)
echo '<a class="w3-btn w3-blue" href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+5).'">Next</a>';
else 
	echo '<a class="w3-btn w3-disabled">Next</a>';
echo '</form>';
echo '</div>';

?>
</div>
</body>
</html>
