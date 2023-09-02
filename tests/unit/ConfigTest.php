<?php

namespace unit;

use App\Exception\ConfigNotFoundException;
use App\Helper\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @throws ConfigNotFoundException
     */
    public function testIsReturnGetFileContentArray()
    {
        $config=Config::getFileContents("database");
        $this->assertIsArray($config);
    }

    public function testThatExecptThrowExecptionGetFileContent()
    {
        $this->expectException(ConfigNotFoundException::class);
        $config=Config::getFileContents("dummy");
    }

    /**
     * @throws ConfigNotFoundException
     */
    public function testGetMethodReturnValidData()
    {
        $config=Config::get('database', "pdo");
        $expect=[
            "driver"=>"mysql",
            "host"=>"127.0.0.1",
            "dbname"=>"bug_tracker",
            "user"=>"root",
            "password"=>"root"
        ];
        $this->assertEquals($config, $expect);
    }
}