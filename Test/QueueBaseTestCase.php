<?php

namespace Adupuy\cdqueue\Test;

use Adupuy\cdqueue\Test\FakeWorkers\FakeWorker;
use Adupuy\cdqueue\Test\Models\DBBaseTestCase;

class QueueBaseTestCase extends DBBaseTestCase
{
        public function mockQueue($objects = null)
        {
            $queue = parent::mockQueue();
            $params = parent::mockParameters($objects);
            var_dump($params->set());
            return $queue;
        }

        public function mockWorker()
        {
            return new FakeWorker();
        }
}
