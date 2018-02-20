<?php
$host = $_GET['host'];
// echo $host;
echo gethostbyname($host);
