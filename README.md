## rig-stats
This is simple php page that collects stats from miners and shows it on single page.

![alt text](https://raw.githubusercontent.com/alxkvx/rig-stats/master/miners.JPG)
![alt text](https://raw.githubusercontent.com/alxkvx/rig-stats/master/miner_info.JPG)
## Requirements:
- Linux or windows pc/laptop/raspberryPi/server/VM on the same network with miners
- running web server: apache/nginx etc.
- php with json module

## Installation:
download rig-stats.php and main.css files into web server doc root (f.e. /var/www/html)

Add/edit your miners IP in rig-stats.php, replace with your miners IPs:
```
$s9 = array(
	'10.10.11.54',
	'10.10.11.55',
	'10.10.11.56',
	'10.10.11.58',
	'10.10.11.59'
);

$l3 = array(
	'10.10.11.40',
	'10.10.11.48'
);

$rigs = array (
	['10.10.11.62',<rig_name>,<cards_number>,<claymore_api_port>],
	['10.10.11.63','rig01 8xRX570',8,3335]
);
```
## Access:
http://<machine_IP>/s9.php

http://<machine_IP>/l3.php
http://<machine_IP>/rigs.php
