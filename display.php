<?php
	require("heading.php");
	displayList();
	require("footing.php");

function displayList()
{
	$background = 0;
	echo <<<HTMLBLOCK
		<table class="center">
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Expiration Date</th>
			<tr>
HTMLBLOCK;

	require("cred.php");

	$db = mysqli_connect($hostname, $username, $password, $database);
	if(!$db) {
		die("Unable to connect to database " . mysql_error());
	}

	$members = mysqli_query($db, 'SELECT name, email, expires FROM members ORDER BY name');

	if(!$members) {
		die("Query failed" . mysqli_error());
	}

	while($row = mysqli_fetch_array($members))
	{
		$name = $row[0];
		$email = $row[1];
		$expires = $row[2];

		if($background++ %2 == 0)
			echo"		<tr style=\"background-color: white\">\n";
		else
			echo"		<tr style=\"background-color: lightgray\">\n";
		echo <<<TABLEDATA
			<td>$name</td>
			<td>$email</td>
			<td>$expires</td>
		</tr>
TABLEDATA;
	}

	echo"		</table>";
	mysqli_close($db);
}
?>
