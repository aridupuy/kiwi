<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\Interfaces\Worker;
use Adupuy\cdqueue\Parameters\AbstractParam;

abstract class AbstractWorker implements Worker {
    public abstract function execute(AbstractParam $params = null): bool;

}
