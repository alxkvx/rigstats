<?php

$type = $_GET['type'];

print '<link href="main.css" type="text/css" rel="stylesheet"/><head><title>Add</title></head><body>
	<form action="addsave.php">
		ID: <input type=text name=id value="" size=3> 
		IP: <input type=text name=ip value="" size=8> 
		Model: <input type=text name=model value="" size=5> 
		Fan check: <input type=text name=fanck value="" size=1> 
		Fan mode: <input type=text name=fanmod value="" size=1> 
		Disabled: <input type=text name=disabled value="" size=1> 
		Comment: <input type=text name=comment value="" size=30>
		Group: <input type=text name=group value="" size=10>
		<input type="hidden" name="type" value='.$type.'>
		<input type=submit value="Save">
	</form>
	<form action="scan.php">
		<input type=submit value="Scan">
	</form>
	</body>';
?>
