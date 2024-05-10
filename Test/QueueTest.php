<?php

namespace Adupuy\cdqueue\Test;

use Adupuy\cdqueue\Parameters\ArrayMultipleParams;
use Adupuy\cdqueue\Parameters\ArrayParam;
use Adupuy\cdqueue\Parameters\ObjectParam;
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
        Queue::add("FakeJob", [$expectedParams]);
        Queue::registerWorker("FakeJob", $mockWorker);
        Queue::run();
        $this->assertEquals(new ArrayParam($expectedParams), FakeWorker::$receiveParams);
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
        $this->assertEquals(new ObjectParam($expectedObject), FakeWorker::$receiveParams);
    }

    public function testExecuteQueueWhenUseMultipleObjectsParameter()  {
        $mockWorker = $this->mockWorker();
        $expectedObject = new StdClass();
        $expectedObject->cosa1 = 1;
        $expectedObject->cosa2 = 2;
        $expectedObject->cosa3 = 3;
        $expectedObject->cosa4 = 4;
        $expectedObject->cosa5 = 5;
        $expectedArray = [1,2,3,4,5];
        $expectedValue = "abc";
        Queue::add("FakeJob", [$expectedObject, $expectedArray, $expectedValue]);
        Queue::registerWorker("FakeJob", $mockWorker);
        Queue::run();
        $this->assertMultipleAbstractParams([$expectedObject, $expectedArray, $expectedValue], FakeWorker::$receiveParams);
    }

    private function assertMultipleAbstractParams($expectedParams, ArrayMultipleParams $params) {
        foreach ($expectedParams as $item) {
            $this->assertEquals(1, in_array($item, $params->getParam()), "cada item debe coincidir");
        }
    }

    private function countRelations($relations): int {
        $count = 0;
        while ($relations->fetchArray()) {
            $count ++;
        }
        return $count;
    }
}
