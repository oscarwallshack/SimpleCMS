<?php

$config = require_once 'config.php';

try {
    $db = new PDO(
        "mysql: host={$config['host']};
        db_name = {$config['db_name']};
        charset=UTF8",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (Exception $error) {
    
    echo "Kod błędu: ".$error->getCode() . "<br>";
    echo "<h3>Informacja developerska: </h3>" . $error . "<br>";

    exit('Database error');
}
