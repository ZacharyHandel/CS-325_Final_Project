<?php
	require("heading.php");
	if(isset($_POST['deleteButton']))
		deleteMember();
	else
		displayForm();
	require("footing.php");

function displayForm()
{
	require("cred.php");

	$db = mysqli_connect($hostname, $username, $password, $database);

	if(!$db) {
		die("Unable to connect to database " . mysqli_error());
	}

	echo <<<FORMBLOCK
		<form method="POST" action="delete.php">
		<table>
			<tr>
				<th>Delete</th>
				<th>Name</th>
				<th>Email</th>
				<th>Range</th>
			</tr>
			<tr>
FORMBLOCK;
	$members = mysqli_query($db, 'SELECT name, email, expires, ID FROM members ORDER BY name');

	if(!$members) {
		die("Query failed" . mysqli_error());
	}

	while($row = mysqli_fetch_array($members)){
		$name=$row[0];
		$email=$row[1];
		$expires=$row[2];
		$ID=$row[3];
		if($backgrond++ %2 == 0)
			echo"		<tr style=\"background-color: white\">\n";
		else
			echo"		<tr style=\"background-color: lightgray\">\n";
		echo <<<TABLEDATA
			<td><input type="checkbox" value="{$ID}" name="selectedMember"></td>
			<td>$name</td>
			<td>$email</td>
			<td>$expires</td>
		</tr>	
TABLEDATA;
	}
	echo <<<ENDFORMBLOCK
			</table>
		<input type="submit" name="deleteButton" value="Delete Member">
		</form>
ENDFORMBLOCK;
	mysqli_close($db);
}

function deleteMember() {
	$ID = $_POST['selectedMember'];

	require("cred.php");
	$db = mysqli_connect($hostname, $username, $password, $database);
	if(mysqli_connect_errno())
		die("Unable to connect to the database " . mysqli_connect_error());

	$query = mysqli_prepare($db, "DELETE from members WHERE ID=?");
	mysqli_stmt_bind_param($query, 'i', $ID);

	if(mysqli_stmt_execute($query))
	{
		echo <<<SUCCESSBLOCK
		<div class="center">
			<h2>SUCCESS! Record Deleted!</h2>
		</div>
SUCCESSBLOCK;
	} else
	{
		echo "Error executing statement: " . mysqli_stmt_error($query);
		echo "Error details: " . mysqli_error($db);

		echo <<<FAILBLOCK
		<div class="center">
			<h2>An error occured. Unable to delte record.</h2>
		</div>
FAILBLOCK;
	}
}
?>
