<?php

header('Content-type:application/json');
header("Access-Control-Allow-Origin: *");

$host = $_GET['host'];
echo gethostbyname($host);
