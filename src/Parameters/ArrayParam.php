<?php

namespace Adupuy\cdqueue\Parameters;

use SebastianBergmann\Type\Parameter;

class ArrayParam extends AbstractParam {

    public function getParam(): array {
        return $this->param;
    }

    public function countParams(): int {
        return count($this->param);
    }

    public function popParam() {
        return array_pop($this->param);
    }

}
