<?php
	require("heading.php");
	
	echo <<<HTMLBLOCK
		<table class="menu">
			<tr>
				<td><a href="display.php">Display List of Members</a></td>
			</tr>
			<tr>
				<td><a href="add.php">Add New Member</a></td>
			</tr>
			<tr>
				<td><a href="edit.php">Edit existing member</a></td>
			</tr>
			<tr>
				<td><a href="delete.php">Delete member(s)</a></td>
			</tr>
		</table>
HTMLBLOCK;
	require("footing.php");
?>
