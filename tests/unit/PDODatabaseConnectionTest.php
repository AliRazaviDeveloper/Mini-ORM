<?php

namespace unit;

use App\Contract\DatabaseInterface;
use App\Database\PDODatabaseConnection;
use App\Exception\ConfigNotFoundException;
use App\Helper\Config;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{

    public function testPDODatabaseConnectionClassImplementDatabaseInterface()
    {
        $config=$this->getConfig();
        $pdoConnection=new PDODatabaseConnection($config);
        $this->assertInstanceOf(DatabaseInterface::class,$pdoConnection);
    }

    public function testShouldBeMethodGetConnectInstansOfPdo()
    {
        $config=$this->getConfig();
        $pdoConnection=new PDODatabaseConnection($config);
        $pdoConnection->connection();
        $this->assertInstanceOf(\PDO::class,$pdoConnection->getConnection());
    }

    /**
     * @throws ConfigNotFoundException
     */
    private function getConfig(){
        return Config::get("database","pdo_testing");
    }
}