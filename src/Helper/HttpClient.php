<?php

namespace App\Helper;

use GuzzleHttp\Client;

class HttpClient extends Client
{
    public function __construct(array $config = [])
    {
        $config=Config::get("app");
        parent::__construct(['base_uri'=>$config['base_uri']]);
    }
}
