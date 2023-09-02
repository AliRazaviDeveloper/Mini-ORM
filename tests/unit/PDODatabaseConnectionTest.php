<?php

namespace unit;

use App\Contract\DatabaseInterface;
use App\Database\PDODatabaseConnection;
use App\Exception\ConfigNotFoundException;
use App\Exception\DatabaseConnectionException;
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

    public function testShouldBePDOConnectionDatabaseReturnValidInstanse()
    {
        $config=$this->getConfig();
        $pdoConnection=new PDODatabaseConnection($config);
        $pdoConnection->connection();
        $this->assertInstanceOf(PDODatabaseConnection::class,$pdoConnection->connection());
    }
    public function testItThatExcpetThrowExceptionInValidConfigDatabase()
    {
        $this->expectException(DatabaseConnectionException::class);
        $config=$this->getConfig();
        $config['dbname']="dummy";
        $pdoConnection=new PDODatabaseConnection($config);
        $pdoConnection->connection();

    }

    /**
     * @throws ConfigNotFoundException
     */
    private function getConfig(){
        return Config::get("database","pdo_testing");
    }
}