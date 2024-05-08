<?php

namespace Adupuy\cdqueue\Test\FakeWorkers;

use Adupuy\cdqueue\AbstractWorker;

class FakeWorker extends AbstractWorker
{
    public static $receiveParams;

    public function execute($params = null)
    {
        self::$receiveParams = $params;
        return true;
    }
}
