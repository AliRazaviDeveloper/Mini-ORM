<?php

namespace App\Contract;

interface DatabaseInterface
{
    public function connection();

    public function getConnection();
}