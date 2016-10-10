<?php

/*

DELETE.PHP

Deletes a specific entry from the the specific table

*/
session_start();    


// connect to the database

include('connect_db.php');

// check if the 'id' variable is set in URL, and check that it is valid
if (isset($_GET['id']) && is_numeric($_GET['id'])){
	// get id value
	$id = $_GET['id'];
	// delete the entry
	$tablename = $_SESSION['table_type'];
	if($tablename == 'basic'){
		// if this professor get deleted, delete all the related information!
		$query = "SELECT basic.name FROM basic WHERE basic.id = $id";
		$result = mysql_query($query)
		or die("Counld not process the query: $query". mysql_error());
		$row = mysql_fetch_array($result);
		if($row){
			// delete publication and research area information:
			$name = $row[0];
			mysql_query("DELETE FROM research WHERE research.name = '$name'")
			or die("Could not delete the related research area information !". mysql_error());
			mysql_query("DELETE FROM publication WHERE publication.author = '$name'")
			or die("Could not delete the related publication information !". mysql_error());
			// delete the academic information!
			$query = "DELETE FROM academic WHERE academic.id = $id";
			mysql_query($query)
			or die("Counld not process the query: $query". mysql_error());
		}		
	}
	$query =  "DELETE FROM $tablename WHERE $tablename.id =$id";
	$result = mysql_query($query)
	or die("Could not process the query: $query". mysql_error());

	// redirect back to the view page
	header("Location: users_page.php");
}
else
// if id isn't set, or isn't valid, redirect back to view page
{
	header("Location: users_page.php");
}



?>
