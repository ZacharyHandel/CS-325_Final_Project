<?php
	require("heading.php");
	if(isset($_POST['editButton'])){
		editMember();
	}
	elseif(isset($_POST['selectedMember']))
	{
		editSelected();
	}
	else
	{
		displaySelect();
	}
	require("footing.php");

//FUNCTIONS
function displaySelect()
{
	require("cred.php");
	$db = mysqli_connect($hostname, $username, $password, $database);

	echo <<<STARTFORMBLOCK
	<form method="POST" action="edit.php">
		<label for="member">Select a member to edit:</label>
		<select name="selectedMember" id="member">
STARTFORMBLOCK;

	$members = mysqli_query($db, "SELECT ID, name FROM members ORDER BY name");

	if(!$members) {
		die("Query failed." . mysqli_error());
	}

	while($row = mysqli_fetch_array($members)) {
		$ID = $row[0];
		$name = $row[1];
		echo "<option value='{$ID}'>{$name}</option>";
	}

	echo <<<ENDFORMBLOCK
		</select>
		
		<input type="submit" name="selectMember" value="Edit Member">
	</form>
ENDFORMBLOCK;
}

function editSelected(){
	require("cred.php");
	$db = mysqli_connect($hostname, $username, $password, $database);

	$selectedMember = $_POST['selectedMember'];
	$query = mysqli_prepare($db, "SELECT ID, name, email, expires FROM members WHERE ID=?");
	mysqli_stmt_bind_param($query, "i", $selectedMember);

	if(!mysqli_stmt_execute($query))
		die("error executing query " . $mysqli_stmt_error());

	mysqli_stmt_bind_result($query, $ID, $name, $email, $expires);
	mysqli_stmt_fetch($query);
	mysqli_stmt_close($query);

	echo <<<FORMBLOCK
	<form method="POST" action="edit.php">
	<input type="hidden" name="selectedMember" value="{$ID}">
	<table>
		<tr>
			<th><label for="name">Name: </label></th>
			<th><label for="email">Email: </label></th>
			<th><label for="expires">Expires: </label></th>
		</tr>
		<tr>
			<td><input type="text" id="updatedName" name="updatedName" required maxlength="64" value="{$name}" autocomplete="off"></td>
			<td><input type="text" id="updatedEmail" name="updatedEmail" required maxlength="64" value="{$email}" autocomplete="off"></td>
			<td><input type="text" id="updatedExpires" name="updatedExpires" required maxlength="10" value="{$expires}" autocomplete="off"></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="editButton" value="Edit Member"></td>
		</tr>
	</table>
	</form>

FORMBLOCK;
}

function editMember() {
	require("cred.php");
	$db = mysqli_connect($hostname, $username, $password, $database);

	$updatedName = $_POST['updatedName'];
	$updatedEmail = $_POST['updatedEmail'];
	$updatedExpires = $_POST['updatedExpires'];
	$selectedMember = $_POST['selectedMember'];
	//echo $updatedName . " " . $updatedEmail . " " . $updatedExpires . " " . $selectedMember;
	$query = mysqli_prepare($db, "UPDATE members SET name=?, email=?, expires=? WHERE ID=?");

	mysqli_stmt_bind_param($query, "sssi", $updatedName, $updatedEmail, $updatedExpires, $selectedMember);
	
	if(!mysqli_stmt_execute($query))
	{
		die("ISSUE RUNNING QUERY:" . $mysqli_stmt_error());
	}
	if(mysqli_stmt_execute($query))
	{
		//echo $selectedMember;
		echo <<<SUCCESSBLOCK
		<div class="center">
			<h2>Success! Record edited.</h2>
		</div>
SUCCESSBLOCK;
	} else
	{
		echo <<<FAILBLOCK
		<div>
			<h2>An error occured. Unable to edit record.</h2>
		</div>
FAILBLOCK;
	}	
}

?>
