<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\DBManager;
use Adupuy\cdqueue\Test\QueueBaseTestCase;

class CategorizeParamsTest extends QueueBaseTestCase {


    public function provideParams() {
        yield[[1,2,3,4,5], "[1,2,3,4,5]"];
        $expectedObject = new \StdClass();
        $expectedObject->cosa1 = 1;
        $expectedObject->cosa2 = 2;
        $expectedObject->cosa3 = 3;
        $expectedObject->cosa4 = 4;
        $expectedObject->cosa5 = 5;
        yield[$expectedObject, base64_encode(serialize($expectedObject))];
        yield[1, 1];
        yield["fake", "fake"];
        yield[["cosa1"=>1,"cosa2"=>2,"cosa3"=>3], '{"cosa1":1,"cosa2":2,"cosa3":3}'];
    }

    /** @dataProvider provideParams*/
    public function testCategorize($object, $expectedInterpretation) {
        $queue = parent::mockQueue(null);
        CategorizeParams::categorize($queue, $object);
        $result = DBManager::getConection()->query("select * from Parameters where idQueue = {$queue->getId()}");
        while ($row = $result->fetchArray()) {
            $params = $row["Param"];
        }
        $this->assertEquals($expectedInterpretation, $params);
    }
}
