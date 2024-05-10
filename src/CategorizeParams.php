<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\Models\Parameters;
use \Adupuy\cdqueue\DB\Models\Queue;
use Adupuy\cdqueue\Parameters\AbstractParam;
use Adupuy\cdqueue\Parameters\ArrayMultipleParams;
use Adupuy\cdqueue\Parameters\ArrayParam;
use Adupuy\cdqueue\Parameters\NativeParam;
use Adupuy\cdqueue\Parameters\ObjectParam;

class CategorizeParams {

    public static function categorize(Queue $queue, $object)
    {
        $parameters = new Parameters();
        $parameters->setIdQueue($queue->getId());
        switch (true) {
            case is_object($object) :
                $parameters->setParam(serialize($object));
                break;
            case is_array($object) :
                $parameters->setParam(json_encode($object));
                break;
            case !is_array($object) and !is_object($object):
                $parameters->setParam($object);
                break;
        }
        $parameters->set();
    }

    public static function processParams(Queue $cola): AbstractParam {
        $result = $cola->obtainRelation("Parameters", "idQueue", $cola->getId());
        $variables = null;
        while ($param = $result->fetchArray(SQLITE3_ASSOC)) {
            $parameters = new Parameters($param);
            $var = $parameters->getParam();
            if(!is_numeric($var) and is_array(json_decode($var, true))) {
                $variables[] = new ArrayParam(json_decode($var));
            }
            else {
                $object = @unserialize($var);
                if(!empty($object))
                    $variables[] = new ObjectParam($object);
                else
                    $variables[] = new NativeParam($var);
            }
        }
        if(count($variables)==1)
            return $variables[0];
        return new ArrayMultipleParams($variables);
    }
}
