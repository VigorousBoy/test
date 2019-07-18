<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/18
 * Time: 13:11
 */
$file='/var/www/html/test/crontab/index.txt';
file_put_contents($file,'hello'.PHP_EOL,FILE_APPEND);