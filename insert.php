<?php
/*
Allows user to insert a new professor information into database
*/
session_start();

// creates the insertion record form
// since this form is used multiple times in this file, made it for easily reuse!
function renderForm($name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, $publications, $areas, $error)
{
	
?>

<!DOCTYPE HTML> 

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<head>
<title>Create the Information of the New Professor</title>
<SCRIPT>
  function addRow(tableID) {

   var table = document.getElementById(tableID);

   var rowCount = table.rows.length;
   var row = table.insertRow(rowCount);

   var colCount = table.rows[0].cells.length;

   for(var i=0; i<colCount; i++) {

    var newcell = row.insertCell(i);

    newcell.innerHTML = table.rows[0].cells[i].innerHTML;
    switch(newcell.childNodes[0].type) {
     case "text":
       newcell.childNodes[0].value = "";
       break;
     case "checkbox":
       newcell.childNodes[0].checked = false;
       break;
     case "select-one":
       newcell.childNodes[0].selectedIndex = 0;
       break;
    }
   }
  }

  function deleteRow(tableID) {
   try {
   var table = document.getElementById(tableID);
   var rowCount = table.rows.length;

   for(var i=0; i<rowCount; i++) {
    var row = table.rows[i];
    var chkbox = row.cells[0].childNodes[0];
    if(null != chkbox && true == chkbox.checked) {
     if(rowCount <= 1) {
      alert("Cannot delete all the rows.");
      break;
     }
     table.deleteRow(i);
     rowCount--;
     i--;
    }
   }
   }catch(e) {
    alert(e);
   }
  }
 </SCRIPT>
</head>

<?php

// if there are any errors, display them
if ($error != '')
{
	echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
}
?>

<body style="margin:10%; margin-left:20%; margin-right:20%">
<div class="w3-card-4 w3-large">
<div class="w3-container w3-lime">
<h2>New professor</h2>
</div>

<form action="" method="post" class="w3-container w3-card-4 w3-light-grey">

<div>
<p>
<label class="w3-label"><strong>Name: *</strong></label>
<input class="w3-input w3-border" type="text" name="name" value="<?php echo $name; ?>"/>
</p>


<p>
<label class="w3-label"><strong>Department: *</strong></label>
<select class="w3-select w3-border" type="text" name="dept">
    <option value="" disabled selected>Choose the department</option>
    <option value="Electrical and Computer Engineering">Electrical and Computer Engineering</option>
    <option value="Computer Science and Engineering">Computer Science and Engineering</option>
    <option value="Material Science and Engineering">Material Science and Engineering</option>
    <option value="Aerospace Engineering">Aerospace Engineering</option>
    <option value="Biomedical Engineering">Biomedical Engineering</option>
    <option value="Chemial Engineering">Chemial Engineering</option>
    <option value="Civil Engineering">Civil Engineering</option>
    <option value="Engineering Technology and Industrial">Engineering Technology and Industrial</option>
    <option value="Industrial and Systems Engineering">Industrial and Systems Engineering</option>
    <option value="Nuclear Engineering">Nuclear Engineering</option>
    <option value="Ocean Engineering">Ocean Engineering</option>
    <option value="Petroleum Engineering">Petroleum Engineering</option>
</select>

<p>
<label class="w3-label">Title:</label>
<input class="w3-input w3-border" type="text" name="title" value="<?php echo $title; ?>"/>
</p>

<p>
<label class="w3-label">Office:</label>
<input class="w3-input w3-border" type="text" name="office" value="<?php echo $office; ?>"/>
</p>

<p>
<label class="w3-label">Phone:</label>
<input class="w3-input w3-border" type="text" name="phone" value="<?php echo $phone; ?>"/>
</p>

<p>
<label class="w3-label"><strong>Email: *</strong></label>
<input class="w3-input w3-border" type="text" name="email" value="<?php echo $email; ?>"/>
</p>

<p>
<label class="w3-label">Photo(hyperlink):</label>
<input class="w3-input w3-border" type="text" name="photo" value="<?php echo $photo; ?>"/>
</p>

<p>
<label class="w3-label">Website:</label>
<input class="w3-input w3-border" type="text" name="website" value="<?php echo $website; ?>"/>
</p>

<p>
<label class="w3-label">Resume(hyperlink):</label>
<input class="w3-input w3-border" type="text" name="resume" value="<?php echo $resume; ?>"/>
</p>

<p>
<label class="w3-label">Google Scholar Page(hyperlink):</label>
<input class="w3-input w3-border" type="text" name="scholar" value="<?php echo $scholar; ?>"/>
</p>

<p>
<label class="w3-label">Education:</label>
<input class="w3-input w3-border" type="text" name="edu" value="<?php echo $edu; ?>"/>
</p>

<p>
<label class="w3-label">Publications:</label>
<table class="w3-table w3-stripedd w3-border" id="pub_table">
<?php
if(isset($_POST['publications'])){
	$cnt = count($_POST['publications']);
	for($i = 0; $i < $cnt; $i++)
	    echo '<tr><td><input class="w3-check" type="checkbox" name="chk"/></td>
    	<td><input class="w3-input w3-border" type="text" name="publications[]" value="'. $_POST['publications'][$i].'"/></td></tr>';
	
}
else{
    echo '<tr><td><input class="w3-check" type="checkbox" name="chk"/></td>
    	<td><input class="w3-input w3-border" type="text" name="publications[]"/></td></tr>';
}
?>
</table>

<p>
<input class="w3-btn w3-gray" type="button" value="New Publication" onclick="addRow('pub_table')"/>
<input class="w3-btn w3-gray" type="button" value="Delete Publication" onclick="deleteRow('pub_table')"/>
</p>

<p>
<label class="w3-label">Research Areas:</label>
<table class="w3-table w3-stripedd w3-border" id="area_table">
<?php
if(isset($_POST['areas'])){
	$cnt = count($_POST['areas']);
	for($i = 0; $i < $cnt; $i++)
	    echo '<tr><td><input class="w3-check" type="checkbox" name="chk"/></td>
    	<td><input class="w3-input w3-border" type="text" name="areas[]" value="'. $_POST['areas'][$i].'"/></td></tr>';
	
}
else{
    echo '<tr><td><input class="w3-check" type="checkbox" name="chk"/></td>
    	<td><input class="w3-input w3-border" type="text" name="areas[]"/></td></tr>';
}
?>
</table>
<p>
<input class="w3-btn w3-gray" type="button" value="New Area" onclick="addRow('area_table')"/>
<input class="w3-btn w3-gray" type="button" value="Delete Area" onclick="deleteRow('area_table')"/>
</p>


<p><strong>* Required</strong></p>

<input class="w3-btn-block w3-lime" type="submit" name="submit" value="Submit">
<div class="w3-panel w3-padding-4"></div>

</div>

</form>

</body>

</html>

<?php

}



// connect to the database

include('connect_db.php');

// check if the form has been submitted. If it has, process the form and save it to the database
if (isset($_POST['submit']))
{

	// get form data, making sure it is valid
	$name = mysql_real_escape_string(htmlspecialchars($_POST['name']));
	$dept = mysql_real_escape_string(htmlspecialchars($_POST['dept']));
        $title  = $_POST['title'];
        $office = $_POST['office'];
        $phone  = $_POST['phone'];    
	$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
        $photo  = $_POST['photo'];
        $website= $_POST['website'];
        $resume = $_POST['resume'];
	$scholar= $_POST['scholar'];
        $edu    = $_POST['edu'];
	$publications = $_POST['publications'];
	$areas  = $_POST['areas'];
	
	// check that email/dept fields are both filled in
	if ($name == '' || $email == '' || $dept == '')
	{
		// generate error message
		$error = 'ERROR: Please fill in all required fields!';
		//error, display form
		renderForm($name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, $publications, $areas, $error);
	}
	else
	{
		// insert the new data to the database
		// if the name and department matched, insert the new publication/area
		// else if this is a new professor, create the record!
		$myquery = "SELECT basic.name, basic.dept FROM basic WHERE basic.name = '$name' AND dept = '$dept'";

		$result = mysql_query($myquery)

		or die("Cannot process the query : ". $myquery. mysql_error());
		
		$row = mysql_fetch_array($result);	
		if(!$row){
			// insert into basic and academic table for new professor
			$q = "INSERT INTO basic (id, name, dept, title, office, phone, email, photo) VALUES (null, '$name', '$dept', '$title', '$office', '$phone', '$email', '$photo')";
			mysql_query($q)

                        or die("Cannot process the query : ". $q . mysql_error());
			$q = "INSERT INTO academic (id, name, website, resume, scholar, email, education) VALUES (null, '$name', '$website', '$resume', '$scholar', '$email', '$edu')";
			mysql_query($q)

                        or die("Cannot process the query : ". $q . mysql_error());
			
			// insert into publication and research table
			
		}
		// insert into publication and research table
		if(isset($_POST['publications']) ){
			$cnt=count($_POST['publications']);
			for($i = 0; $i < $cnt; $i++){	
				if(!empty($_POST['publications'][$i])){
				$pub = $_POST['publications'][$i];
				$q = "INSERT INTO publication (id, author, paper) VALUES (null, '$name', '$pub')";
				mysql_query($q)
		
				or die("Cannot process the query : ". $q . mysql_error());
				}
			}
		}
		if(isset($_POST['areas']) ){
			$cnt=count($_POST['areas']);
			for($i = 0; $i < $cnt; $i++){	
				if(!empty($_POST['areas'][$i])){
				$area = $_POST['areas'][$i];
				$q = "INSERT INTO research (id, name, area) VALUES (null, '$name', '$area')";
				mysql_query($q)
		
				or die("Cannot process the query : ". $q . mysql_error());
				}
			}
		}
		
		// once done, redirect back to the view page
		header("Location: users_page.php");
	}

}
else
// if the form hasn't been submitted, simply deplay the form
{
	
	// get the data:
	$name = $_POST['name'];
	$dept = $_POST['dept'];
        $title  = $_POST['title'];
        $office = $_POST['office'];
        $phone  = $_POST['phone'];    
	$email  = $_POST['email'];
        $photo  = $_POST['photo'];
        $website= $_POST['website'];
        $resume = $_POST['resume'];
	$scholar= $_POST['scholar'];
        $edu    = $_POST['edu'];
	$publications = $_POST['publications'];
	$areas  = $_POST['areas'];

	renderForm($name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, $publications, $areas, '');
	
}


?>
