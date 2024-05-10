<?php

namespace Adupuy\cdqueue\Parameters;

abstract class AbstractParam {

    protected $param;

    public function __construct($param = null) {
        $this->param = $param;
    }

    public abstract function getParam();
}
