<?php

namespace App\Database;

use App\Contract\DatabaseInterface;
use App\Exception\ConfigNotValidException;
use App\Exception\DatabaseConnectionException;
use PDOException;


class PDODatabaseConnection implements DatabaseInterface
{

    protected $config;
    protected $connection;

    const REQUIRED_CONFIG=[
        "driver",
        "host",
        "dbname",
        "user",
        "password"
    ];


    /**
     * @throws ConfigNotValidException
     */
    public function __construct($config)
    {
        if(!$this->isValidConfig($config)){
            throw new ConfigNotValidException();
        }
        $this->config=$config;
    }

    /**
     * @throws DatabaseConnectionException
     */
    public function connection()
    {
        $dsn=$this->generateDsn($this->config);
        try {
            $this->connection = new \PDO(...$dsn);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_OBJ);

        } catch(PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage());
        }
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function generateDsn($config){
        $dsn="{$config['driver']}:host={$config['host']};dbname={$config['dbname']}";
        return[
            $dsn,
            $config['user'],
            $config['password']
        ];
    }

    private function isValidConfig(array $config): bool
    {
        $array=array_intersect(array_keys($config),self::REQUIRED_CONFIG);
        return count($array) === count(self::REQUIRED_CONFIG);
    }
}