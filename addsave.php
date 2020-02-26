<?php

$jsfile = 'json/s9.json';
$l3jsfile = 'json/l3.json';

$id   = $_GET['id'];
$type = $_GET['type'];
$group = $_GET['group'];

$html = '<link href="main.css" type="text/css" rel="stylesheet"/><head><title>Adding</title></head><body>';

if ($type == 'l3') {
	$file = $l3jsfile;
	$a = json_decode(file_get_contents($file),true);
}
else if ($type == 's9')	{
	$file = $jsfile;
	if ($group == 'green') { $file = 'green-s9.json'; }
	$a = json_decode(file_get_contents($file),true);
}
else {
	$html .= "BAD Type!";
}

if ($type) {

	if (!$a[$id]['id']) {
	
		$a[$id]['id']	= $id;
		$a[$id]['ip']	= $_GET['ip'];
		$a[$id]['model']= $_GET['model'];
		$a[$id]['fanck']	= $_GET['fanck'];
		$a[$id]['fanmod']	= $_GET['fanmod'];
		$a[$id]['comment']	= $_GET['comment'];
		$a[$id]['disabled']	= $_GET['disabled'];

		$html.='ADDED:<br><br>File: '.$file.'<br> 
		ID: '.$a[$id]['id'].'<br> 
		IP: '.$a[$id]['ip'].'<br>
		Model: '.$a[$id]['model'].' <br>
		Fan check: '.$a[$id]['fanck'].' <br>
		Fan mode: '.$a[$id]['fanmod'].' <br>
		Disabled: '.$a[$id]['disabled'].'<br> 
		Comment: '.$a[$id]['comment'].'</body>';
	
		$fpd = fopen($file, 'w');
		fwrite($fpd, json_encode($a,JSON_PRETTY_PRINT));
		fclose($fpd);
	}
	else {
		$html.= "ID: $id already exists!";
	}
}

echo $html;

?>
