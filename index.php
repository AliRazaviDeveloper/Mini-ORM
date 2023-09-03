<?php

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;

require_once __DIR__."/vendor/autoload.php";
$config = App\Helper\Config::get("database","pdo_testing");
$pdoConnection = new PDODatabaseConnection($config);
$queryBuilder = new PDOQueryBuilder($pdoConnection->connection());
function json_response($data = null, $statusCode = 200){
    header_remove();
    header('Content-type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
function request(){
   return json_decode( file_get_contents("php://input"),true);
}

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    $queryBuilder->table('bugs')->create(request());
    json_response(['message'=>"bug report created "], 201);
}

if ($_SERVER['REQUEST_METHOD'] === "PUT"){
    $queryBuilder->table('bugs')->update(request());
    json_response(['message'=>"bug report updated "], 200);
}
if ($_SERVER['REQUEST_METHOD'] === "DELETE"){
    $queryBuilder->table('bugs')->where('id',request()['id'])->delete();
    json_response(['message'=>"bug report delete "], 204);
}
if ($_SERVER['REQUEST_METHOD'] === "GET"){
    $bug=$queryBuilder->table('bugs')->find(request()['id']);
    json_response($bug, 200);
}