<?php

namespace Adupuy\cdqueue\Test\FakeWorkers;

use Adupuy\cdqueue\AbstractWorker;
use Adupuy\cdqueue\Parameters\AbstractParam;

class FakeWorker extends AbstractWorker
{
    public static AbstractParam $receiveParams;

    public function execute(AbstractParam $params = null): bool {
        self::$receiveParams = $params;
        return true;
    }
}
