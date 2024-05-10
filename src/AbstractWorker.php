<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\Interfaces\Worker;
use Adupuy\cdqueue\Parameters\AbstractParam;

abstract class AbstractWorker implements Worker {
    private $errorMessage;
    public abstract function execute(AbstractParam $params = null): bool;

    public final function setErrorMessage(string $message): void {
           $this->errorMessage = $message;
    }

    public final function getErrorMessage(): ?string {
        return $this->errorMessage;
    }

}
