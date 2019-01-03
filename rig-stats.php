<?php

error_reporting(E_ERROR | E_PARSE);
$start = microtime(true);

$s9 = array(
	'10.10.11.34',
	'10.10.11.35',
	'10.10.11.36'
);
	
$l3 = array(
        '10.1.1.112',
        '10.1.1.32'
);

$rigs = array (
	['10.10.11.62','ESONIC02 8xRX570',8],
	['10.10.11.63','ASUS02 8xRX570',8]
);

$s9num = count($s9);
$l3num = count($l3);
$rigsnum = count($rigs);

$totalhashrate = 0;
$l3hashrate = 0;

function get_api($ip,$command) {
	
	$socket = fsockopen($ip, 4028, $err_code, $err_str, 1);
	if (!$socket) {
		$socket2 = fsockopen($ip, 22, $err_code, $err_str, 1);
			if ($socket2)	{return 1;}
			else 		{return 0;}
	}
	$data = '{"id":1,"jsonrpc":"2.0","command": "'. $command . '"}' . "\r\n\r\n";
	fputs($socket, $data);
	$buffer = null;
	while (!feof($socket)) { $buffer .= fgets($socket, 4028); }
	if ($socket) {  fclose($socket); }
	$buff = substr($buffer,0,strlen($buffer)-1);
	$buff = preg_replace('/}{/','},{',$buff);
	$buff = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $buff);
	if (!json_decode($buff)) { print "BAD json, error: " . json_last_error();}
	else { $json = json_decode($buff,true);}
	return $json;
}

function miner_details($type,$ip) {
	
	$json = get_api($ip,'summary');
	if ($json == 0)	{
		return array(0,0,'<tr><td><span class="box red">Offline</span></td><td></td><td><span class="red">'.$ip.'</span></td></tr>');
	}
	else if ($json == 1) {
		return array(0,0,'<tr><td><span class="box yellow">SSH Only</span></td><td></td><td><span class="red">'.$ip.'</span></td></tr>');
        }
	$getworks       = number_format($json['SUMMARY'][0]['Getworks']);
	$elapsed        = $json['SUMMARY'][0]['Elapsed'];
	$hw             = $json['SUMMARY'][0]['Hardware Errors'];
	$accepted       = $json['SUMMARY'][0]['Accepted'];
	$rejected       = $json['SUMMARY'][0]['Rejected'];
	$stale          = $json['SUMMARY'][0]['Stale'];
	$discarded      = $json['SUMMARY'][0]['Discarded'];
	$ghsav          = $json['SUMMARY'][0]['GHS av'];
	$ghs5s          = round($json['SUMMARY'][0]['GHS 5s']);
	$blocks         = $json['SUMMARY'][0]['Found Blocks'];
	
	$json = get_api($ip,'pools');
	
	for ($i=0; $i<3; $i++) {
		$pool_status[$i]   = $json['POOLS'][$i]['Status'];
		$pool_prio[$i]     = $json['POOLS'][$i]['Priority'];
		$pool_url[$i]      = $json['POOLS'][$i]['URL'];
		$pool_user[$i]     = $json['POOLS'][$i]['User'];
		$pool_diff[$i]     = $json['POOLS'][$i]['Diff'];
		$pool_works[$i]    = $json['POOLS'][$i]['Getworks'];
		$pool_lstime[$i]   = $json['POOLS'][$i]['Last Share Time'];
	}
	
	$json = get_api($ip,'stats');

	$miner_type     = $json['STATS'][0]['Type'];
	$miner_ver      = $json['STATS'][0]['Miner'];
	$miner_compile  = $json['STATS'][0]['CompileTime'];
	$freq           = $json['STATS'][1]['frequency'];
	
	if ($type == 's9') {
		$bmminer_ver    = $json['STATS'][0]['BMMiner'];
		$asic0_btemp    = $json['STATS'][1]['temp6'];
		$asic1_btemp    = $json['STATS'][1]['temp7'];
		$asic2_btemp    = $json['STATS'][1]['temp8'];
		$ctemp0         = $json['STATS'][1]['temp2_6'];
		$ctemp1         = $json['STATS'][1]['temp2_7'];
		$ctemp2         = $json['STATS'][1]['temp2_8'];
		$asic0_chips    = $json['STATS'][1]['chain_acn6'];
		$asic1_chips    = $json['STATS'][1]['chain_acn7'];
		$asic2_chips    = $json['STATS'][1]['chain_acn8'];
		$fan1           = $json['STATS'][1]['fan5'];
		$fan2           = $json['STATS'][1]['fan6'];
		$fan3           = $json['STATS'][1]['fan3'];
		$hrate_ideal	= $json['STATS'][1]['total_rateideal'];
		$asic_chip_sum = $asic0_chips+$asic1_chips+$asic2_chips;
		if ($miner_type == '') { $miner_type = 'Braiins OS';}
		else {		$miner_type     =  preg_replace('/S9S9/','S9',$miner_type);}
		if ($asic0_btemp>90) { $bcl0 = 'red';} else if ($asic0_btemp>80) { $bcl0 = 'orange';} else if ($asic0_btemp>75) { $bcl0 = 'yellow';} else if ($asic0_btemp>50) { $bcl0 = 'green';} else { $bcl0 = 'blue';}
		if ($asic1_btemp>90) { $bcl1 = 'red';} else if ($asic1_btemp>80) { $bcl1 = 'orange';} else if ($asic1_btemp>75) { $bcl1 = 'yellow';} else if ($asic1_btemp>50) { $bcl1 = 'green';} else { $bcl1 = 'blue';}
		if ($asic2_btemp>90) { $bcl2 = 'red';} else if ($asic2_btemp>80) { $bcl2 = 'orange';} else if ($asic2_btemp>75) { $bcl2 = 'yellow';} else if ($asic2_btemp>50) { $bcl2 = 'green';} else { $bcl2 = 'blue';}
		if ($ctemp0>100) { $ccl0 = 'red';} else if ($ctemp0>90) { $ccl0 = 'orange';} else if ($ctemp0>83) { $ccl0 = 'yellow';} else if ($ctemp0>60) { $ccl0 = 'green';} else { $ccl0 = 'blue';}
		if ($ctemp1>100) { $ccl1 = 'red';} else if ($ctemp1>90) { $ccl1 = 'orange';} else if ($ctemp1>83) { $ccl1 = 'yellow';} else if ($ctemp1>60) { $ccl1 = 'green';} else { $ccl1 = 'blue';}
		if ($ctemp2>100) { $ccl2 = 'red';} else if ($ctemp2>90) { $ccl2 = 'orange';} else if ($ctemp2>83) { $ccl2 = 'yellow';} else if ($ctemp2>60) { $ccl2 = 'green';} else { $ccl2 = 'blue';}
		if ($ghs5s>14500) {$thcl = 'greenlight';} else if ($ghs5s>14000) {$thcl = 'green';} else if ($ghs5s>13450) {$thcl = 'blue';} else if ($ghs5s>13000) {$thcl = 'yellow';} else if ($ghs5s>10000) {$thcl = 'orange';} else {$thcl = 'red';}
		if ($asic_chip_sum<189) {$csumcl = 'red';} else {$csumcl = '';}
	}
	else {
		$asic0_btemp    = $json['STATS'][1]['temp1'];
		$asic1_btemp    = $json['STATS'][1]['temp2'];
		$asic2_btemp    = $json['STATS'][1]['temp3'];
		$asic3_btemp    = $json['STATS'][1]['temp4'];
		$ctemp0         = $json['STATS'][1]['temp2_1'];
		$ctemp1         = $json['STATS'][1]['temp2_2'];
		$ctemp2         = $json['STATS'][1]['temp2_3'];
		$ctemp3         = $json['STATS'][1]['temp2_4'];
		$asic0_chips    = $json['STATS'][1]['chain_acn1'];
		$asic1_chips    = $json['STATS'][1]['chain_acn2'];
		$asic2_chips    = $json['STATS'][1]['chain_acn3'];
		$asic3_chips    = $json['STATS'][1]['chain_acn4'];
		$fan1           = $json['STATS'][1]['fan1'];
		$fan2           = $json['STATS'][1]['fan2'];
		$asic_chip_sum = $asic0_chips+$asic1_chips+$asic2_chips+$asic3_chips;
		if ($asic0_btemp>80) { $bcl0 = 'red';} else if ($asic0_btemp>70) { $bcl0 = 'orange';} else if ($asic0_btemp>65) { $bcl0 = 'yellow';} else if ($asic0_btemp>50) { $bcl0 = 'green';} else { $bcl0 = 'blue';}
		if ($asic1_btemp>80) { $bcl1 = 'red';} else if ($asic1_btemp>70) { $bcl1 = 'orange';} else if ($asic1_btemp>65) { $bcl1 = 'yellow';} else if ($asic1_btemp>50) { $bcl1 = 'green';} else { $bcl1 = 'blue';}
		if ($asic2_btemp>80) { $bcl2 = 'red';} else if ($asic2_btemp>70) { $bcl2 = 'orange';} else if ($asic2_btemp>65) { $bcl2 = 'yellow';} else if ($asic2_btemp>50) { $bcl2 = 'green';} else { $bcl2 = 'blue';}
		if ($asic3_btemp>80) { $bcl3 = 'red';} else if ($asic3_btemp>70) { $bcl3 = 'orange';} else if ($asic3_btemp>65) { $bcl3 = 'yellow';} else if ($asic3_btemp>50) { $bcl3 = 'green';} else { $bcl3 = 'blue';}
		if ($ctemp0>80) { $ccl0 = 'red';} else if ($ctemp0>70) { $ccl0 = 'orange';} else if ($ctemp0>65) { $ccl0 = 'yellow';} else if ($ctemp0>50) { $ccl0 = 'green';} else { $ccl0 = 'blue';}
		if ($ctemp1>80) { $ccl1 = 'red';} else if ($ctemp1>70) { $ccl1 = 'orange';} else if ($ctemp1>65) { $ccl1 = 'yellow';} else if ($ctemp1>50) { $ccl1 = 'green';} else { $ccl1 = 'blue';}
		if ($ctemp2>80) { $ccl2 = 'red';} else if ($ctemp2>70) { $ccl2 = 'orange';} else if ($ctemp2>65) { $ccl2 = 'yellow';} else if ($ctemp2>50) { $ccl2 = 'green';} else { $ccl2 = 'blue';}
		if ($ctemp3>80) { $ccl3 = 'red';} else if ($ctemp3>70) { $ccl3 = 'orange';} else if ($ctemp3>65) { $ccl3 = 'yellow';} else if ($ctemp3>50) { $ccl3 = 'green';} else { $ccl3 = 'blue';}
		if ($ghs5s>650) {$thcl = 'greenlight';} else if ($ghs5s>600) {$thcl = 'green';} else if ($ghs5s>500) {$thcl = 'blue';} else if ($ghs5s>450) {$thcl = 'yellow';} else if ($ghs5s>400) {$thcl = 'orange';} else {$thcl = 'red';}
		if ($asic_chip_sum<288) {$csumcl = 'red';} else {$csumcl = '';}
	}
	
	if	 	($elapsed<180) 		{	$uptime = $elapsed . " sec";$upbox ='box red';}
	else if ($elapsed<3600*2)	{	$uptime = floor($elapsed/60) . " min";$upbox ='box yellow'; }
	else if ($elapsed<3600*48)	{	$uptime = floor($elapsed/3600) . " H";	$upbox =' box blue';}
	else 						{	$uptime = floor($elapsed/(3600*24)) . " days";	}
	
	if ($hw > 100000) {	$hw = '<td class="hwred">' . number_format($hw);}
	else 			{	$hw = "<td>" . number_format($hw);	}
	if ($fan1 == 0) { $fan1 = $fan3; }
	$rejrate = round((100*($rejected/$accepted)), 3);
	if ($pool_prio[0] == 0 ) {$poolnum = 0;}
	else if ($pool_prio[1] == 0 ) {$poolnum = 1;}
	else if ($pool_prio[2] == 0 ) {$poolnum = 2;}
	else $poolnum = 3;
	$pool_url[$poolnum] = preg_replace("/stratum\+tcp:\/\/(.*)/","\$1",$pool_url[$poolnum]);
	if (preg_match('/kano.is/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Kano'; }
	else if (preg_match('/viabtc.com/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'ViaBTC'; }
	else if (preg_match('/slushpool.com/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Slush'; }
	else if (preg_match('/sigmapool.com/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Sigma'; }
	$worker_parts = explode('.', $pool_user[$poolnum]);
	$worker_name = $worker_parts[0];
	$worker_id = $worker_parts[1];
	$fan1cl = $fan2cl = 'green';
	if ($fan1>5999) { $fan1cl = 'red'; }
	else if ($fan1>5000) { $fan1cl = 'yellow'; }
	else if ($fan1<3500) { $fan1cl = 'blue'; }
	if ($fan2>5999) { $fan2cl = 'red'; }
	else if ($fan2>5000) { $fan2cl = 'yellow'; }
	else if ($fan2<3500) { $fan2cl = 'blue'; }
	$thdiff = $hrate_ideal - $ghsav;
    if ($thdiff<0)  {$thavcol = 'fgreen';}
	else if ($thdiff>600)   {$thavcol = 'fred';}
    else if ($thdiff>300)   {$thavcol = 'forange';}
	else if ($thdiff>150)   {$thavcol = 'fyellow';}
	else    {$thavcol = 'fblue';}
		
	$html = "<tr>
		<td>$miner_type</td>
		<td>$miner_ver</td>
		<td><a href=\"getinfo.php?ip=$ip\">$ip</a></td>
		<td>$pool_url[$poolnum]</td>
		<td>$worker_name</td>
		<td>$worker_id</td>
		<td>$pool_diff[$poolnum]</td>
		<td>$getworks</td>
		<td>$pool_lstime[$poolnum]</td>
		<td>$blocks</td>
		<td><span class=\"$upbox\">$uptime</span></td>
		<td><span class=\"box $fan1cl\">$fan1</span><span class=\"box $fan2cl\">$fan2</span></td>
		<td>$freq</td>";
	if ($type == 's9') { $html .= "<td>".number_format($hrate_ideal)."</td>"; }
	$html .= "		
		<td><span class=\"box $thcl\">" . number_format($ghs5s). "</span></td>
		<td class=\"ghsav $thavcol\">".number_format($ghsav)."</td>
		$hw</td>
		<td>$rejrate%</td>";
	if ($type == 's9') {
		$html .= "
		<td><span class=\"box $bcl0\"> $asic0_btemp</span><span class=\"box $bcl1\"> $asic1_btemp</span><span class=\"box $bcl2\">$asic2_btemp</span></td>
		<td><span class=\"box $ccl0\">$ctemp0</span><span class=\"box $ccl1\">$ctemp1</span><span class=\"box $ccl2\">$ctemp2</span></td>";
	}
	else {
		$html .= "
        <td><span class=\"box $bcl0\"> $asic0_btemp</span><span class=\"box $bcl1\"> $asic1_btemp</span><span class=\"box $bcl2\">$asic2_btemp</span><span class=\"box $bcl3\">$asic3_btemp</span></td>
        <td><span class=\"box $ccl0\">$ctemp0</span><span class=\"box $ccl1\">$ctemp1</span><span class=\"box $ccl2\">$ctemp2</span><span class=\"box $ccl3\">$ctemp3</span></td>";
	}
	$html .= "<td><span class=\"box $csumcl\">$asic_chip_sum</span></td></tr>";

	return array($ghs5s,$ghsav,$html);
}

$html = '<link href="/stats/main.css" type="text/css" rel="stylesheet"/><head><title>All Rigs</title></head>';
$html .= "<body><table border=0 cellspacing=0 cellpadding=4><tr class=head><td>Type</td><td>Miner</td><td>IP</td><td>Pool</td><td>Worker</td><td>ID</td><td>Diff</td><td>Works</td><td>LS time</td><td>Block</td><td>Uptime</td><td>Fans</td><td>Freq</td><td>TH ideal</td><td>TH 5s</td><td>TH Avg</td><td>HW</td><td>Reject</td><td>Board Temp</td><td>Chip Temp</td><td>Chips</td></tr>";

$totalavg = 0;
for($x = 0; $x < $s9num; $x++) {
	
	$vars = miner_details('s9',$s9[$x]);
	$totalhashrate += $vars[0];
	$totalavg += $vars[1];
	$html .= $vars[2];
}

$html .= "</table><br><span class=bold>Total: ". $s9num . " miners" . " / " . number_format($totalhashrate/1000,2) . " Th  / ".number_format($totalavg/1000,2) ." Th(Avg)</span><br><br>";

$html .= "<table border=0 cellspacing=0 cellpadding=4><tr class=head><td>Type</td><td>Miner</td><td>IP</td><td>Pool</td><td>Worker</td><td>ID</td><td>Diff</td><td>Works</td><td>LS time</td><td>Block</td><td>Uptime</td><td>Fans</td><td>Freq</td><td>MH 5s</td><td>MH Avg</td><td>HW</td><td>Reject</td><td>Board Temp</td><td>Chip Temp</td><td>Chips</td></tr>";

$l3hashavg =0;
for($x = 0; $x < $l3num; $x++) {

        $vars = miner_details('l3',$l3[$x]);
        $l3hashrate += $vars[0];
        $l3hashavg += $vars[1];
        $html .= $vars[2];
}

$html .= "</table><br><span class=bold>Total: ". $l3num . " miners" . " / " . number_format($l3hashrate) . " Mh / ".number_format($l3hashavg). " Mh(Avg)</span><br><br>";

$html .= "<table border=0 cellspacing=0 cellpadding=4><tr class=head><td>Type</td><td>IP</td><td>Pool</td><td>Uptime</td><td>ETH/XVG</td><td>Temp avg</td><td>GPU0</td><td>GPU1</td><td>GPU2</td><td>GPU3</td><td>GPU4</td><td>GPU5</td><td>GPU6</td><td>GPU7</td><td>GPU8</td><td>GPU9</td></tr>";

for($x = 0; $x < $rigsnum; $x++) {
	
	$ip = $rigs[$x][0];
	$name = $rigs[$x][1];
	$gpus = $rigs[$x][2];
	
	$socket = fsockopen($ip, 3333, $err_code, $err_str,1);
	if (!$socket) {
		continue;
	}
	$data = '{"id":1,"jsonrpc":"2.0","method":"miner_getstat2"}' . "\r\n\r\n";
	fputs($socket, $data);
	$buffer = null;
	while (!feof($socket)) { $buffer .= fgets($socket, 3333); }
	if ($socket) {  fclose($socket); }
	
	$json = json_decode($buffer,true);
	
	$vers           = $json['result'][0];
	$elapsed        = $json['result'][1];
	$total_eth      = $json['result'][2];
	$eth_mh         = $json['result'][3];
	$total_xvg      = $json['result'][4];
	$xvg_mh         = $json['result'][5];
	$temp_fan       = $json['result'][6];
	$pools          = $json['result'][7];
	$eth_pool_inv_sw= $json['result'][8];
	$eth_acc_shrs   = $json['result'][9];
	$eth_rej_shrs   = $json['result'][10];
	$eth_inv_shrs   = $json['result'][11];
	$xvg_acc_shrs   = $json['result'][12];
	$xvg_rej_shrs   = $json['result'][13];
	$xvg_inv_shrs   = $json['result'][14];
	
	if ($elapsed<60*2) {
		$uptime = $elapsed . " min";
	}
	else if ($elapsed<60*48) {
		$uptime = floor($elapsed/60) . " H";
	}
	else {
		$uptime = floor($elapsed/(60*24)) . " days";
	}
	
	$eth_pool = preg_replace("/(.*);.*/","\$1",$pools);
	$eth_all = preg_replace("/(\d+);.*/","\$1",$total_eth);
	$xvg_all = preg_replace("/(\d+);.*/","\$1",$total_xvg);
	$eth_all = sprintf('%0.1f', $eth_all/1000);
	$xvg_all = round($xvg_all/1000000, 2);
	
	$inc = '';
	$einc= '';
	$tavg = 0;
	for ($i=0;$i<$gpus;$i++){
		$gpu_eth[$i] = preg_replace("/$einc(\d+).*/","\$1",$eth_mh);
		$gpu_temp[$i] = preg_replace("/$inc(\d+);.*/","\$1",$temp_fan);
		$gpu_fan[$i] = preg_replace("/$inc\d+;(\d+).*/","\$1",$temp_fan);
		$gpu_eth[$i] = sprintf('%0.2f', $gpu_eth[$i]/1000);
		$tavg+= $gpu_temp[$i];
		$einc.= '\d+;';
		$inc .= '\d+;\d+;';
	}
	
	$tavg = round($tavg/$gpus, 1);
	$ethtotal += $eth_all;
	$xvgtotal += $xvg_all;
	if (preg_match("/ethermine.org/", $eth_pool)) { $eth_pool = 'Ethermine';}
	
	$html .= "<tr>
		<td>$name</td>
		<td>$ip</td>
		<td>$eth_pool</td>
		<td>$uptime</td>
		<td>$eth_all / $xvg_all</td>
		<td>${tavg}&deg;</td>";
	
	$gpushtml = '';
	for ($i=0;$i<$gpus;$i++){
		if ($gpu_temp[$i]>78) { $col = 'red';} else if ($gpu_temp[$i]>71) { $col = 'orange';} else if ($gpu_temp[$i]>67) { $col = 'yellow';} else if ($gpu_temp[$i]>59) { $col = 'green';} else if ($gpu_temp[$i]>50) { $col = 'blue';} else { $col = 'fiol';}
		if ($gpu_fan[$i]>90) { $fcol = 'red';} else if ($gpu_fan[$i]>75) { $fcol = 'orange';} else if ($gpu_fan[$i]>50) { $fcol = 'yellow';} else if ($gpu_fan[$i]>30) { $fcol = 'blue';} else { $fcol = 'fiol';}
		$gpushtml.= "<td><span class=\"box\">$gpu_eth[$i]</span><span class=\"box $col\">$gpu_temp[$i]&deg;</span><span class=\"box $fcol\">$gpu_fan[$i]%</span></td>";
	}
	$html .= $gpushtml . "</tr>";
}

$html .=  "</table><br>RIGs: " . $rigsnum . " ETH: " . $ethtotal . " Mh" . " / XVG: " . $xvgtotal . " Gh";

$exec_time = round(microtime(true) - $start, 3);
$html .=  "Load time: " . $exec_time . " sec (" . date('Y-m-d H:i:s') .")";
$html .=  "</body>";

print $html;

?>
