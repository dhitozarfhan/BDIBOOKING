<?php
try {
    $dbh = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'Superhero02');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec('CREATE DATABASE room_management');
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
