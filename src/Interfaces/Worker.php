<?php
namespace Adupuy\cdqueue\Interfaces;
interface Worker
{
    public function execute(?array $params);

}
