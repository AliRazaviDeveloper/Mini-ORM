<?php
require_once __DIR__."/vendor/autoload.php";

try {
    var_dump(App\Helper\Config::get('database', "pdo"));
} catch (\App\Exception\ConfigNotFoundException $e) {
    echo $e->getMessage();
}