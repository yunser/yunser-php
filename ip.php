<?php

header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");

$ip = $_SERVER["REMOTE_ADDR"];

echo $ip;
