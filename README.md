## rig-stats
This is simple php page that collects stats from miners and shows it on single page.

![alt text](https://raw.githubusercontent.com/alxkvx/rig-stats/master/miners.JPG)
![alt text](https://raw.githubusercontent.com/alxkvx/rig-stats/master/miner_info.JPG)
## Requirements:
- Linux or windows pc/laptop/raspberryPi/server/VM on the same network with miners
- running web server: apache/nginx etc.
- php with json module

## Installation:
download files into web server doc root (f.e. /var/www/html):
```
root@raspberrypi:/var/www/html/# git clone https://github.com/alxkvx/rig-stats.git
```

Add/edit your miners IP in rig-stats.php, replace with your miners IPs:

## Access:
http://<machine_IP>/s9.php

http://<machine_IP>/l3.php

http://<machine_IP>/rigs.php
