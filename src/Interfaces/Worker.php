<?php
namespace Adupuy\cdqueue\Interfaces;
use Adupuy\cdqueue\Parameters\AbstractParam;

interface Worker
{
    public function execute(AbstractParam $params = null);

}
