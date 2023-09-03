<?php
require_once __DIR__."/../../vendor/autoload.php";
$config = App\Helper\Config::get("database", "pdo_testing");
$connection=new \App\Database\PDODatabaseConnection($config);
try {
    $querybuilder = new \App\Database\PDOQueryBuilder($connection->connection());
    $querybuilder->trancateAll();
} catch (\App\Exception\DatabaseConnectionException $e) {
}
