<?php

namespace Adupuy\cdqueue\Test\Models;

use Adupuy\cdqueue\DB\Models\Model;
use Adupuy\cdqueue\DB\Models\Parameters;
use Adupuy\cdqueue\DB\Models\Queue;
use PHPUnit\Framework\TestCase;
class DBBaseTestCase extends TestCase
{

    /** @before*/
    public function start()
    {
        Model::startTrans();
    }

    /** @after */
    public function end()
    {
        Model::FailTrans();
        Model::CompleteTrans();
    }
    protected function mockQueue()
    {
        $queue = new Queue();
        return $queue->setSemaphore(false)
        ->setName("FakeJob")
        ->setFecha("2024-04-01")
        ->setEjecuciones(0)
        ->setErrormessage("")
        ->setId(1);

    }

    protected function mockParameters($objec = null)
    {
        $parameters = new Parameters();
        return $parameters->setParam($objec ??'{"fake":true}')
        ->setIdQueue(1)
        ->setIdParameter(1);
    }
}
