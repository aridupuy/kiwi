<?php

namespace Adupuy\cdqueue\Test\FakeWorkers;

use Adupuy\cdqueue\AbstractWorker;

class FakeWorker extends AbstractWorker
{
    public static $receiveParams;

    public function execute(?array $params)
    {
        self::$receiveParams = $params;
        return true;
    }
}
