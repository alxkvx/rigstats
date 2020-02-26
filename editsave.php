<?php

$jsfile = 's9list.json';
$jsfileand = 's9and.json';
$jsfilegrg = 's9georg.json';
$l3jsfile = 'l3list.json';

$id   = $_GET['id'];
$type = $_GET['type'];

if ($type == 'l3') {
	$file = $l3jsfile;
	$a = json_decode(file_get_contents($file),true);
}
else if (preg_match('/AB/', $id)) {
	$file = $jsfileand;
	$a = json_decode(file_get_contents($file),true);
}
else if (preg_match('/G/', $id)) {
	$file = $jsfilegrg;
	$a = json_decode(file_get_contents($file),true);
}
else		 {
	$file = $jsfile;
	$a = json_decode(file_get_contents($file),true);
}

$fp = fopen('list-saved.json', 'w');
fwrite($fp, json_encode($a,JSON_PRETTY_PRINT));
fclose($fp);

$a[$id]['id']		= $id;
$a[$id]['ip']		= $_GET['ip'];
$a[$id]['model']	= $_GET['model'];
$a[$id]['fanck']	= $_GET['fanck'];
$a[$id]['fanmod']	= $_GET['fanmod'];
$a[$id]['comment']	= $_GET['comment'];
$a[$id]['disabled']	= $_GET['disabled'];

print '<link href="main.css" type="text/css" rel="stylesheet"/><head><title>Save</title></head><body>
		File: '.$file.'<br> 
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

?>
