<?php

namespace Adupuy\cdqueue\Test;


use Adupuy\cdqueue\Queue;
use Adupuy\cdqueue\Test\FakeWorkers\FakeWorker;
use stdClass;

class QueueTest extends QueueBaseTestCase
{

    public function testAddToQueueUsingArray() {
        $queue = Queue::add("FakeJob", [1,2,3,4,5]);
        $relations = $queue->obtainRelation("Parameters", "idQueue", $queue->getId());
        $count = $this->countRelations($relations);
        $this->assertEquals(5,$count);
    }

    public function testAddToQueueUsingObject() {
        $expectedObject = new StdClass();
        $expectedObject->cosa1 = 1;
        $expectedObject->cosa2 = 2;
        $expectedObject->cosa3 = 3;
        $expectedObject->cosa4 = 4;
        $expectedObject->cosa5 = 5;

        $queue = Queue::add("FakeJob", $expectedObject);
        $relations = $queue->obtainRelation("Parameters", "idQueue", $queue->getId());
        $count = $this->countRelations($relations);
        $this->assertEquals(1,$count);
    }

    public function testExecuteQueueWhenUseArrayParameter()  {
        $mockWorker = $this->mockWorker();
        $expectedParams = [1,2,3,4,5];
        Queue::add("FakeJob", $expectedParams);
        Queue::registerWorker("FakeJob", $mockWorker);
        Queue::run();
        $this->assertEquals($expectedParams, FakeWorker::$receiveParams);
    }

    public function testExecuteQueueWhenUseObjectParameter()  {
        $mockWorker = $this->mockWorker();
        $expectedObject = new StdClass();
        $expectedObject->cosa1 = 1;
        $expectedObject->cosa2 = 2;
        $expectedObject->cosa3 = 3;
        $expectedObject->cosa4 = 4;
        $expectedObject->cosa5 = 5;
        Queue::add("FakeJob", $expectedObject);
        Queue::registerWorker("FakeJob", $mockWorker);
        Queue::run();
        $this->assertEquals($expectedObject, FakeWorker::$receiveParams);
    }

    private function countRelations($relations): int {
        $count = 0;
        while ($relations->fetchArray()) {
            $count ++;
        }
        return $count;
    }
}
