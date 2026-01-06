<?php
$host = $argv[1] ?? '172.18.0.4'; 
try {
    echo "Attempting connection to $host...\n";
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=blogmix", 'sail', 'password');
    echo "Connected successfully to MySQL ($host)!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
echo "\n";
