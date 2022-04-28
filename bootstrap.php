<?php
    define('ROOTDIR', __DIR__ . DIRECTORY_SEPARATOR);
    session_start();
    require_once ROOTDIR . 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(ROOTDIR);
    $dotenv->load();

    try {
        $conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                                         $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>