<?php

namespace Adupuy\cdqueue\Test\Models;
use \Adupuy\cdqueue\DB\Models\Queue;


class ModelTest extends DBBaseTestCase
{

    public function testSet() {
        $queue = $this->mockQueue();
        $result = $queue->set();

        $this->assertNotEmpty($result);
    }

    public function testGet() {
        $newQueue = $this->mockQueue();
        $newQueue->set();
        $queue = new Queue();
        $queue->get($newQueue->getId());

        $this->assertEquals($queue->getId(), $newQueue->getId());
    }

    public function testGetWithNotExists() {
        $queue = new Queue();
        $queue->get(-1);

        $this->assertNull($queue->getId());
    }

}
