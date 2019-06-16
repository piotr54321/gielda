<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 06.04.2018
 * Time: 16:39
 */
exec ("php /var/www/html/app/cron/kursy_walut.php > /dev/null 2> /dev/null & echo $!");
//echo'ok';