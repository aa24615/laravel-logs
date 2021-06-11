<?php

namespace Tests;

use Zyan\LaravelLogs\Logs;

class LogsTest extends \PHPUnit\Framework\TestCase
{

    public function testInit(){

        $logs = new Logs();

        //$this->assertTrue($logs->response());
    }
}