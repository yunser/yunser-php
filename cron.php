<?php
/**
 * Created by PhpStorm.
 * User: cjh1
 * Date: 2017/6/30
 * Time: 0:42
 */
header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");
header('Content-type:text/json');

date_default_timezone_set('PRC');

include 'crontab.class.php';

$cron = $_GET['cron'];
$number = intval($_GET['number']);

$times = array();

$cron = CronExpression::factory($cron);
for ($i = 0; $i < $number; $i++) {
    $times[]= $cron->getNextRunDate(null, $i)->format('Y-m-d H:i:s');
}

$data['code'] = 0;
$data['data'] = $times;
echo json_encode($data);

