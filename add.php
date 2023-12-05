<?php
	require("heading.php");
	//if add button is set
	if(isset($_POST['addButton']))
		addMember();
	else
		displayForm();

	require("footing.php");

function displayForm()
{
	echo <<<DISPLAYFORM
		<form method="POST" action="add.php">
		<table>
			<tr>
				<th><label for="name">Name: </label></th>
				<th><label for="email">Email: </label></th>
				<th><label for="expires">Expires: </label></th>
			</tr>
			<tr>
				<td><input type="text" id="name" name="name" required maxlength="64" placeholder="name of member" autocomplete="off"></td>
				<td><input type="text" id="email" name="email" required maxlength="32" placeholder="member@email.com" autocomplete="off" pattern="^[a-zA-Z0.9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"></td>
				
				<td><input type="text" id="expires" name="expires" required maxlength="10" placeholder="YYYY-MM-DD" autocomplete="off"></td>
			</tr>

			<tr>
				<td colspan="3" class="center"><input type="submit" name="addButton" value="Add Member"></td>
			</tr>

DISPLAYFORM;
}

function addMember()
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$expires = $_POST['expires'];

	//echo"VALUES IN POST ARRAY: " . $name . $email . $expires;

	//filter values
	$name = trim($name);
	$name = filter_var($name, FILTER_VALIDATE_REGEXP,
		array("options"=>array("regexp"=>"/^[a-z ,.'-]+$/i")));

	//echo"NAME DEBUG: " . $name;

	$email = trim($email);
	//$email = filter_var($name, FILTER_VALIDATE_REGEXP,
		//array("options"=>array("regexp"=>"/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/")));

	//echo"EMAIL DEBUG: " . $email;

	$expires = trim($expires);
	$expires = filter_var($expires, FILTER_VALIDATE_REGEXP,
		array("options"=>array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")));

	//echo"EXPIRES DEBUG: " . $expires;

	if($name != false && $email != false && $expires != false)
	{
		require("cred.php");
		$db = mysqli_connect($hostname, $username, $password, $database);
		if(mysqli_connect_errno())
			die("Unable to connect to database " . mysqli_connect_error());
		$query = mysqli_prepare($db, "INSERT INTO members (name, email, expires) VALUES(?,?,?)");

		mysqli_stmt_bind_param($query, 'sss', $name, $email, $expires);

		if(mysqli_stmt_execute($query))
		{
			echo <<<SUCCESS
			<div class="center">
				<h2>Successfully added {$name} to the database!</h2>
			</div>
SUCCESS;
		} else
		{
			echo "error executing statement: " . mysqli_stmt_error($query);
			echo "error details: " . mysqli_error($db);

			echo <<<FAIL
			<div class="center">
				<h2>An error occured. Unable to add record.</h2>
			</div>
FAIL;
		}

		mysqli_close($db);
	}
	else
	{
		die("invalid inputs");
	}
}


?>

