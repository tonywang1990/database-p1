<?php
/*
Allows user to edit specific entry in database
*/
session_start();

// creates the edit record form
// since this form is used multiple times in this file, made it for easily reuse!
function renderForm($id, $name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, $error)
{
	
?>

<!DOCTYPE HTML> 

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<head>
<title>Edit the Information of the Professor</title>
</head>
<body>

<?php

// if there are any errors, display them
if ($error != '')
{
	echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';
}
?>


<form action="" method="post" class="w3-container w3-card-4 w3-light-grey">
<h2>Edit the Information</h2>
<input class="w3-input w3-border" type="hidden" name="id" value="<?php echo $id; ?>"/>
<input class="w3-input w3-border" type="hidden" name="name" value="<?php echo $name; ?>"/>
<input class="w3-input w3-border" type="hidden" name="dept" value="<?php echo $dept; ?>"/>

<div class="w3-third">

<p>
<label class="w3-label"><strong>ID* :</strong></label>   <?php echo $id; ?>
</p>

<p>
<label class="w3-label"><strong>Name* :</strong></label>   <?php echo $name; ?>
</p>

<p>
<label class="w3-label"><strong>Department* :</strong></label>
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
<label class="w3-label">Email: *</label>
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
<label class="w3-label">Google Scholar Page:</label>
<input class="w3-input w3-border" type="text" name="scholar" value="<?php echo $scholar; ?>"/>
</p>

<p>
<label class="w3-label">Education:</label>
<input class="w3-input w3-border" type="text" name="edu" value="<?php echo $edu; ?>"/>
</p>

<p><strong>* Required</strong></p>

<input class="w3-btn w3-green" type="submit" name="submit" value="Submit">

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
	$id     = $_POST['id'];
        $name   = $_POST['name'];
        $title  = $_POST['title'];
        $office = $_POST['office'];
        $phone  = $_POST['phone'];    
        $photo  = $_POST['photo'];
        $website= $_POST['website'];
        $resume = $_POST['resume'];
	$scholar= $_POST['scholar'];
        $edu    = $_POST['edu'];
	
	$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
	$dept = mysql_real_escape_string(htmlspecialchars($_POST['dept']));

	// check that email/dept fields are both filled in
	if ($email == '' || $dept == '')
	{
		// generate error message
		$error = 'ERROR: Please fill in all required fields!';
		//error, display form
		renderForm($id, $name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, $error);
	}
	else
	{
		// save the data to the database
		$myquery = "UPDATE basic SET name = '$name', dept = '$dept', title = '$title', office = '$office', phone = '$phone', email = '$email', photo = '$photo' WHERE id = '$id'";
		mysql_query($myquery)

		or die("Cannot process the query : ". $myquery. mysql_error());
	
		$myquery = "UPDATE academic SET name = '$name', website = '$website', resume = '$resume', scholar = '$scholar', email = '$email', education = '$edu' WHERE id = '$id'";
                mysql_query($myquery)

                or die("Cannot process the query : ". $myquery. mysql_error());


		// once saved, redirect back to the view page
		header("Location: users_page.php");
	}

}
else
// if the form hasn't been submitted, get the data from the db and display the form
{

// get the 'id' value from the URL (if it exists), making sure that it is valid
if (isset($_GET['id']) && is_numeric($_GET['id']) )
{

	// query db
	$id = $_GET['id'];
	$myquery = "SELECT basic.*, academic.website, academic.resume, academic.scholar, academic.education FROM basic, academic WHERE basic.id = $id AND basic.name = academic.name";
	$result = mysql_query($myquery)

	or die("Cannot process the query: ".$myquery. mysql_error());

	$row = mysql_fetch_array($result);

	// check that the 'id' matches up with a row in the databse
	if($row)
	{
		// get data from db
		$name   = $row[1];
		$dept   = $row[2];
		$title  = $row[3];
		$office = $row[4];
		$phone  = $row[5];
		$email  = $row[6];
		$photo  = $row[7];
		$website = $row[8];
		$resume = $row[9];	
		$scholar = $row[10];
		$edu = $row[11];
		// show form
		renderForm($id, $name, $dept, $title, $office, $phone, $email, $photo, $website, $resume, $scholar, $edu, '');
	}

	else

	// if no match, display result
	{

		echo "No matched results for the id: ".$id."!";

	}

}
else
// if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error
{
	echo 'Error! Invalid id : '.$_GET['id'].' !';
}

}

?>
