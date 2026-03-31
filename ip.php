<?php

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ipaddress = $_SERVER['REMOTE_ADDR'];
}

$browser    = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
$timestamp  = date('Y-m-d H:i:s T');

$file = 'ip.txt';
$fp   = fopen($file, 'a');

fwrite($fp, "Time: "       . $timestamp  . "\r\n");
fwrite($fp, "IP: "         . $ipaddress  . "\r\n");
fwrite($fp, "User-Agent: " . $browser    . "\r\n");
fwrite($fp, str_repeat('-', 40) . "\r\n");

fclose($fp);

