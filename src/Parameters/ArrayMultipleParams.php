<?php

namespace Adupuy\cdqueue\Parameters;

class ArrayMultipleParams extends AbstractParam {

    public function getParam(): array {
        foreach ($this->param as $item) {
            $param[] = $item->getParam();
        }
        return $param;
    }

    public function getParamSimple(){
        return $this->param;
    }
}
