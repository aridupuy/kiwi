<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\Interfaces\Worker;

abstract class AbstractWorker implements Worker {
    public abstract function execute($params = null);

}
