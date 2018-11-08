## rig-stats
This is simple php page that collects stats from miners and shows it on single page.

## Requirements:
- Linux or windows pc/laptop/server/VM on the same network with miners
- running web server: apache/nginx etc.
- php module

## Installaion:
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
	['10.10.11.63','ASUS02 8xRX570',8,3335],
	['10.10.11.64','BIOSTAR01 10xRX570',10,3335],
	['10.10.11.65','ASUS01 8xRX570',8,3333]
);
```
## Access:
http://<machine_IP>/rig-stats.php

example:

![alt text](https://raw.githubusercontent.com/alxkvx/rig-stats/master/rigs.JPG)
