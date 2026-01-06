<?php
$fp = fsockopen('redis', 6379, $errno, $errstr, 5);
if ($fp) {
    echo "Connected to Redis!";
    fclose($fp);
} else {
    echo "Redis Connection Failed: $errstr ($errno)";
}
echo "\n";
