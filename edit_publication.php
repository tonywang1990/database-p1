<?php
/*
Allows user to edit specific entry in database
*/
session_start();

// creates the edit record form
// since this form is used multiple times in this file, made it for easily reuse!
function renderForm($id, $name, $paper, $error)
{
	
?>

<!DOCTYPE HTML> 

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<head>
<title>Edit the Publication Information of the Professor</title>
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
<div class="w3-container w3-teal">
<h2>Edit Publications</h2>
</div>

<form action="" method="post" class="w3-container">
<input class="w3-input w3-border" type="hidden" name="id" value="<?php echo $id; ?>"/>
<input class="w3-input w3-border" type="hidden" name="name" value="<?php echo $name; ?>"/>

<p><label class="w3-label"><strong>Publication ID* :</strong></label>   <?php echo $id; ?></p>

<p>
<label class="w3-label"><strong>Name* :</strong></label>   <?php echo $name; ?>
</p>

<p>
<label class="w3-label"><strong>Publication* :</strong></label>
<input class="w3-input w3-border" type="text" name="paper" value="<?php echo htmlspecialchars($paper); ?>"/>
</p>

<p><strong>* Required</strong></p>

<input class="w3-btn-block w3-teal" type="submit" name="submit" value="Submit">

<div class="w3-panel w3-padding-4"></div>
</div>
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
        $id     = mysql_real_escape_string(htmlspecialchars($_POST['id']));
	$author = mysql_real_escape_string(htmlspecialchars($_POST['name']));
	$paper  = mysql_real_escape_string(htmlspecialchars($_POST['paper']));
        

	// check that email/dept fields are both filled in
	if ($id == '' || $author == '' || $paper == '')
	{
		// generate error message
		$error = 'ERROR: Please fill in all required fields!';
		//error, display form
		renderForm($id, $author, $paper, $error);
	}
	else
	{
		// save the data to the database
		$myquery = "UPDATE publication SET author = '$author', paper = '$paper' WHERE id = '$id'";
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
	$myquery = "SELECT publication.*  FROM publication WHERE publication.id = $id";
	$result = mysql_query($myquery)

	or die("Cannot process the query: ".$myquery. mysql_error());

	$row = mysql_fetch_array($result);

	// check that the 'id' matches up with a row in the databse
	if($row)
	{
		// get data from db
		$name   = $row[1];
		$paper  = $row[2];
		// show form
		renderForm($id, $name, $paper, '');
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
