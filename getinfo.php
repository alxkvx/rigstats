<?php
error_reporting(E_ERROR | E_PARSE);
$start = microtime(true);

$ip = $_GET['ip'];

print '<link href="/stats/main.css" type="text/css" rel="stylesheet"/>
<head><title>S9 data: '.$ip.'</title></head>
<body><table border=0 cellspacing=0 cellpadding=4><tr class=head><td>Type</td><td>Miner</td><td>IP</td><td>Pool</td><td>Worker</td><td>ID</td><td>Diff</td><td>Works</td><td>LS time</td><td>Block</td><td>Uptime</td><td>Fans</td><td>Freq</td><td>TH ideal</td><td>TH 5s</td><td>TH Avg</td><td>HW</td><td>Reject</td><td>Board Temp</td><td>Chip Temp</td><td>Chips</td></tr>';

function get_api($ip,$command) {
	
	$socket = fsockopen($ip, 4028, $err_code, $err_str, 1);
	if (!$socket) {
		$socket2 = fsockopen($ip, 22, $err_code, $err_str, 1);
			if ($socket2)   {return 1;}
			else            {return 0;}
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
	if ($json == 0) {
		return '<tr><td><span class="box red">Offline</span></td><td></td><td><span class="red">'.$ip.'</span></td></tr></table>';
	}
	else if ($json == 1) {
		return '<tr><td><span class="box yellow">SSH Only</span></td><td></td><td><span class="red">'.$ip.'</span></td></tr></table>';
	}
	$getworks       = number_format($json['SUMMARY'][0]['Getworks']);
	$elapsed        = $json['SUMMARY'][0]['Elapsed'];
	$hw             = $json['SUMMARY'][0]['Hardware Errors'];
	$accepted       = $json['SUMMARY'][0]['Accepted'];
	$rejected       = $json['SUMMARY'][0]['Rejected'];
	$stale          = $json['SUMMARY'][0]['Stale'];
	$discarded      = $json['SUMMARY'][0]['Discarded'];
	$ghsav          = $json['SUMMARY'][0]['GHS av'];
	$ghs5s          = $json['SUMMARY'][0]['GHS 5s'];
	$blocks         = $json['SUMMARY'][0]['Found Blocks'];
	
	$json = get_api($ip,'pools');
	
	$pool_status[0]   = $json['POOLS'][0]['Status'];
	$pool_status[1]   = $json['POOLS'][1]['Status'];
	$pool_status[2]   = $json['POOLS'][2]['Status'];
	$pool_status[3]   = $json['POOLS'][3]['Status'];
	$pool_status[4]   = $json['POOLS'][4]['Status'];
	$pool_prio[0]     = $json['POOLS'][0]['Priority'];
	$pool_prio[1]     = $json['POOLS'][1]['Priority'];
	$pool_prio[2]     = $json['POOLS'][2]['Priority'];
	$pool_prio[3]     = $json['POOLS'][3]['Priority'];
	$pool_prio[4]     = $json['POOLS'][4]['Priority'];
	$pool_url[0]      = $json['POOLS'][0]['URL'];
	$pool_url[1]      = $json['POOLS'][1]['URL'];
	$pool_url[2]      = $json['POOLS'][2]['URL'];
	$pool_url[3]      = $json['POOLS'][3]['URL'];
	$pool_url[4]      = $json['POOLS'][4]['URL'];
	$pool_user[0]     = $json['POOLS'][0]['User'];
	$pool_user[1]     = $json['POOLS'][1]['User'];
	$pool_user[2]     = $json['POOLS'][2]['User'];
	$pool_user[3]     = $json['POOLS'][3]['User'];
	$pool_user[4]     = $json['POOLS'][4]['User'];
	$pool_diff[0]     = $json['POOLS'][0]['Diff'];
	$pool_diff[1]     = $json['POOLS'][1]['Diff'];
	$pool_diff[2]     = $json['POOLS'][2]['Diff'];
	$pool_diff[3]     = $json['POOLS'][3]['Diff'];
	$pool_diff[4]     = $json['POOLS'][4]['Diff'];
	$pool_works[0]    = $json['POOLS'][0]['Getworks'];
	$pool_works[1]    = $json['POOLS'][1]['Getworks'];
	$pool_works[2]    = $json['POOLS'][2]['Getworks'];
	$pool_works[3]    = $json['POOLS'][3]['Getworks'];
	$pool_works[4]    = $json['POOLS'][4]['Getworks'];
	$pool_lstime[0]   = $json['POOLS'][0]['Last Share Time'];
	$pool_lstime[1]   = $json['POOLS'][1]['Last Share Time'];
	$pool_lstime[2]   = $json['POOLS'][2]['Last Share Time'];
	$pool_lstime[3]   = $json['POOLS'][3]['Last Share Time'];
	$pool_lstime[4]   = $json['POOLS'][4]['Last Share Time'];
	$pool_accept[0]   = $json['POOLS'][0]['Accepted'];
	$pool_accept[1]   = $json['POOLS'][1]['Accepted'];
	$pool_accept[2]   = $json['POOLS'][2]['Accepted'];
	$pool_accept[3]   = $json['POOLS'][3]['Accepted'];
	$pool_accept[4]   = $json['POOLS'][4]['Accepted'];
	$pool_reject[0]   = $json['POOLS'][0]['Rejected'];
	$pool_reject[1]   = $json['POOLS'][1]['Rejected'];
	$pool_reject[2]   = $json['POOLS'][2]['Rejected'];
	$pool_reject[3]   = $json['POOLS'][3]['Rejected'];
	$pool_reject[4]   = $json['POOLS'][4]['Rejected'];
	$pool_discard[0]   = $json['POOLS'][0]['Discarded'];
	$pool_discard[1]   = $json['POOLS'][1]['Discarded'];
	$pool_discard[2]   = $json['POOLS'][2]['Discarded'];
	$pool_discard[3]   = $json['POOLS'][3]['Discarded'];
	$pool_discard[4]   = $json['POOLS'][4]['Discarded'];
	$pool_diffa[0]    = $json['POOLS'][0]['Difficulty Accepted'];
	$pool_diffa[1]    = $json['POOLS'][1]['Difficulty Accepted'];
	$pool_diffa[2]    = $json['POOLS'][2]['Difficulty Accepted'];
	$pool_diffa[3]    = $json['POOLS'][3]['Difficulty Accepted'];
	$pool_diffa[4]    = $json['POOLS'][4]['Difficulty Accepted'];
	$pool_diffr[0]    = $json['POOLS'][0]['Difficulty Rejected'];
	$pool_diffr[1]    = $json['POOLS'][1]['Difficulty Rejected'];
	$pool_diffr[2]    = $json['POOLS'][2]['Difficulty Rejected'];
	$pool_diffr[3]    = $json['POOLS'][3]['Difficulty Rejected'];
	$pool_diffr[4]    = $json['POOLS'][4]['Difficulty Rejected'];
	
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
			$asic0_hrideal  = $json['STATS'][1]['chain_rateideal6'];
			$asic1_hrideal  = $json['STATS'][1]['chain_rateideal7'];
			$asic2_hrideal  = $json['STATS'][1]['chain_rateideal8'];
			$asic0_hr       = $json['STATS'][1]['chain_rate6'];
			$asic1_hr       = $json['STATS'][1]['chain_rate7'];
			$asic2_hr       = $json['STATS'][1]['chain_rate8'];
			$asic0_freq     = $json['STATS'][1]['freq_avg6'];
			$asic1_freq     = $json['STATS'][1]['freq_avg7'];
			$asic2_freq     = $json['STATS'][1]['freq_avg8'];
			$asic0_hw       = $json['STATS'][1]['chain_hw6'];
			$asic1_hw       = $json['STATS'][1]['chain_hw7'];
			$asic2_hw       = $json['STATS'][1]['chain_hw8'];
			$asic0_chain    = $json['STATS'][1]['chain_acs6'];
			$asic1_chain    = $json['STATS'][1]['chain_acs7'];
			$asic2_chain    = $json['STATS'][1]['chain_acs8'];
			$fan1           = $json['STATS'][1]['fan5'];
			$fan2           = $json['STATS'][1]['fan6'];
			$fan3           = $json['STATS'][1]['fan3'];
			$hrate_ideal    = $json['STATS'][1]['total_rateideal'];
			$asic_chip_sum = $asic0_chips+$asic1_chips+$asic2_chips;
			if ($miner_type == '') { $miner_type = 'Braiins OS';}
			else {          $miner_type     =  preg_replace('/S9S9/','S9',$miner_type);}
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
	
	if              ($elapsed<180)          {       $uptime = $elapsed . " sec";$upbox ='box red';}
	else if ($elapsed<3600*2)       {       $uptime = floor($elapsed/60) . " min";$upbox ='box yellow'; }
	else if ($elapsed<3600*48)      {       $uptime = floor($elapsed/3600) . " H";  $upbox =' box blue';}
	else                                            {       $uptime = floor($elapsed/(3600*24)) . " days";  }
	
	if ($hw > 100000) {     $hw = '<td class="hwred">' . number_format($hw);}
	else                    {       $hw = "<td>" . number_format($hw);      }
	if ($fan1 == 0) { $fan1 = $fan3; }
	$rejrate = round((100*($rejected/$accepted)), 3);
	if ($pool_prio[0] == 0 ) {$poolnum = 0;}
	else if ($pool_prio[1] == 0 ) {$poolnum = 1;}
	else if ($pool_prio[2] == 0 ) {$poolnum = 2;}
	else $poolnum = 3;
	$pool_url[$poolnum] = preg_replace("/stratum\+tcp:\/\/(.*)/","\$1",$pool_url[$poolnum]);
	if (preg_match('/kano.is/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Kano'; }
	else if (preg_match('/viabtc.com/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'ViaBTC'; }
	else if (preg_match('/sigmapool.com/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Sigma'; }
	else if (preg_match('/slushpool/', $pool_url[$poolnum])) { $pool_url[$poolnum] = 'Slush'; }
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
	$diff1 = round($asic0_hr - $asic0_hrideal);
	$diff2 = round($asic1_hr - $asic1_hrideal);
	$diff3 = round($asic2_hr - $asic2_hrideal);
	$difftotal = round($ghs5s - $hrate_ideal);
	if ($diff1>0) {$dfcol='green';} else if ($diff1 < -400) {$dfcol='red';} else if ($diff1 < -200) {$dfcol='orange';} else if ($diff1 < -50) {$dfcol='yellow';} else {$dfcol='blue';}
	if ($diff2>0) {$dfcol2='green';} else if ($diff2 < -400) {$dfcol2='red';} else if ($diff2 < -200) {$dfcol2='orange';} else if ($diff2 < -50) {$dfcol2='yellow';} else {$dfcol2='blue';}
	if ($diff3>0) {$dfcol3='green';} else if ($diff3 < -400) {$dfcol3='red';} else if ($diff3 < -200) {$dfcol3='orange';} else if ($diff3 < -50) {$dfcol3='yellow';} else {$dfcol3='blue';}
	if ($difftotal>0) {$dftotcol='green';} else if ($difftotal < -500) {$dftotcol='red';} else if ($difftotal < -250) {$dftotcol='orange';} else if ($difftotal < -50) {$dftotcol='yellow';} else {$dftotcol='blue';}
	$thdiff = $hrate_ideal - $ghsav;
	if ($thdiff<0)  {$thavcol = 'fgreen';}
	else if ($thdiff>600)   {$thavcol = 'fred';}
	else if ($thdiff>300)   {$thavcol = 'forange';}
	else if ($thdiff>150)   {$thavcol = 'fyellow';}
	else    {$thavcol = 'fblue';}
	
	$html = "<tr>
			<td class=type>$miner_type</td>
			<td class=miner>$miner_ver</td>
			<td><a href=\"s9info.php?ip=$ip\">$ip</a></td>
			<td>$pool_url[$poolnum]</td>
			<td class=wname>$worker_name</td>
			<td class=wid>$worker_id</td>
			<td class=diff>$pool_diff[$poolnum]</td>
			<td class=works>$getworks</td>
			<td class=lstime>$pool_lstime[$poolnum]</td>
			<td class=blocks>$blocks</td>
			<td><span class=\"uptime $upbox\">$uptime</span></td>
			<td><span class=\"fan1 box $fan1cl\">$fan1</span><span class=\"fan2 box $fan2cl\">$fan2</span></td>
			<td class=freq>$freq</td>
			<td class=hrideal>". number_format(round($hrate_ideal))."</td>
			<td><span class=\"hrate box $thcl\">" . number_format($ghs5s). "</span></td>                
			<td class=\"ghsav $thavcol\">".number_format($ghsav)."</td>
			$hw</td>
			<td class=rrate>$rejrate%</td>
			<td><span class=\"btemp1 box $bcl0\"> $asic0_btemp</span><span class=\"btemp2 box $bcl1\"> $asic1_btemp</span><span class=\"btemp3 box $bcl2\">$asic2_btemp</span></td>
			<td><span class=\"ctemp1 box $ccl0\">$ctemp0</span><span class=\"ctemp2 box $ccl1\">$ctemp1</span><span class=\"ctemp3 box $ccl2\">$ctemp2</span></td>
			<td class=reload rel=\"$ip\"><span class=\"chips box $csumcl\">$asic_chip_sum</span></td>
	</tr></table><br>";
	
	$html .= "<div class=pools><table border=0 cellspacing=0 cellpadding=3><tr class=head><td>#</td><td>Pool URL</td><td>Worker</td><td>Status</td><td>Priority</td><td>Diff</td><td>Works</td><td>Accepted</td><td>Rejected</td><td>Discarded</td><td>DiffA</td><td>DiffA(%)</td><td>DiffR</td><td>LSTime</td><tr>";
	
	$pworks_total = 0;
	$pdiffa_total = 0;
	$pdiffr_total = 0;
	for ($i=0; $i<5; $i++) {
		$pworks_total += $pool_works[$i];
		$pdiffa_total += $pool_diffa[$i];
		$pdiffr_total += $pool_diffr[$i];
	}
	for ($i=0; $i<5; $i++) {
		$pdiffa_prc = round(100*$pool_diffa[$i]/$pdiffa_total,2);
		$html .= "<tr><td>$i</td><td>$pool_url[$i]</td><td>$pool_user[$i]</td><td>$pool_status[$i]</td><td>$pool_prio[$i]</td><td>$pool_diff[$i]</td><td>$pool_works[$i]</td><td>$pool_accept[$i]</td><td>$pool_reject[$i]</td><td>$pool_discard[$i]</td><td>".number_format($pool_diffa[$i])."</td><td>$pdiffa_prc%</td><td>$pool_diffr[$i]</td><td>$pool_lstime[$i]</td><tr>";
	}
	$html .= "<tr><td></td><td>Total</td><td></td><td></td><td></td><td></td><td>$pworks_total</td><td></td><td></td><td></td><td>".number_format($pdiffa_total)."</td><td>100%</td><td>".number_format($pdiffr_total)."</td><tr></table></div><br>";
	
	
	$html .= "<div class=asics><table border=0 cellspacing=0 cellpadding=5>
	<tr class=head><td>Chain</td><td>Chips</td><td>Freq</td><td>TH Ideal</td><td>TH Real</td><td>TH Diff</td><td>HW</td><td>Chips Chain</td><tr>
	<tr><td>#6</td><td>$asic0_chips</td><td>$asic0_freq</td><td>".number_format($asic0_hrideal)."</td><td>".number_format($asic0_hr)."</td><td><span class=\"box $dfcol\">$diff1</span></td><td>$asic0_hw</td><td>$asic0_chain</td><tr>
	<tr><td>#7</td><td>$asic1_chips</td><td>$asic1_freq</td><td>".number_format($asic1_hrideal)."</td><td>".number_format($asic1_hr)."</td><td><span class=\"box $dfcol2\">$diff2</span></td><td>$asic1_hw</td><td>$asic1_chain</td><tr>
	<tr><td>#8</td><td>$asic2_chips</td><td>$asic2_freq</td><td>".number_format($asic2_hrideal)."</td><td>".number_format($asic2_hr)."</td><td><span class=\"box $dfcol3\">$diff3</span></td><td>$asic2_hw</td><td>$asic2_chain</td><tr>
	<tr class=bold><td>Total</td><td></td><td></td><td>".number_format($hrate_ideal)."</td><td>".number_format($ghs5s)."</td><td><span class=\"box $dftotcol\">".number_format($difftotal)."</span></td><td></td><td></td><tr>
	</table></div>";
	
	return $html;
}

$s9htm = miner_details('s9',$ip);

$exec_time = round(microtime(true) - $start, 3);

print "$s9htm<br>Load time: " . $exec_time . " sec (" . date('Y-m-d H:i:s') .")</body>";

?>