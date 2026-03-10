<?php
try {
    $dbh = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'Superhero02');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec('DROP DATABASE IF EXISTS room_management');
    $dbh->exec('CREATE DATABASE room_management');
    echo "Database reset successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
