<?php

namespace Adupuy\cdqueue\Test;

use Adupuy\cdqueue\DB\DBManager;
use \Adupuy\cdqueue\Queue as Cola;
use Adupuy\cdqueue\Semaphore;

class SemaphoreTest extends QueueBaseTestCase {

    public function queueTestProvider($max) {
        for($i=0; $i<$max; $i++){
            Cola::add("FakeJob", [1,2,3,4,5]);
        }
    }

    public function testTomarSemaforo() {
        $expectedMax = 5;
        $this->queueTestProvider($expectedMax * 2 );
        $offset = 0;
        Semaphore::tomarSemaforo($offset,$expectedMax);
        $result = DBManager::getConection()->query("select count(*) from Queue where Semaphore = true")->fetchArray();
        $this->assertEquals($expectedMax, $result[0]);
    }

    public function testTomarSemaforoWithLimit1000() {
        $expectedMax = 1000;
        $this->queueTestProvider($expectedMax+1);
        $offset = 0;
        Semaphore::tomarSemaforo($offset,$expectedMax);
        $result = DBManager::getConection()->query("select count(*) from Queue where Semaphore = true")->fetchArray();
        $this->assertEquals($expectedMax, $result[0]);
    }

    public function testLiberarSemaforo() {
        $expectedMax = 5;
        $this->queueTestProvider($expectedMax+1);
        Semaphore::tomarSemaforo(0, $expectedMax);
        $result = DBManager::getConection()->query("select id from Queue where Semaphore = true");
        $ids = [];
        while ($id = $result->fetchArray()) {
            $ids []= $id[0];
        }
        Semaphore::liberarSemaforo($ids);
        $result = DBManager::getConection()->query("select count(*) from Queue where Semaphore = true")->fetchArray();
        $this->assertEquals(0, $result[0]);

    }
}
