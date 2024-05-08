<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\DBManager;
use Adupuy\cdqueue\DB\Models\Model;
use Adupuy\cdqueue\DB\Models\Parameters;
use \Adupuy\cdqueue\DB\Models\Queue as Cola;


class Queue
{
    /** @type AbstractWorker[] */
    private static $workers;
    public function __construct()
    {
        DBManager::getConection();
    }

    /*metodo para añadir a la cola*/
    public static function add(string $name, $object): ?Cola {
        Model::StartTrans();
        $queue = new Cola();
        if(!$queue->setName($name)
            ->setFecha((new \DateTime("now"))->getTimestamp())
            ->setSemaphore(false)
            ->set()) {
            Model::FailTrans();
        }
        if(is_array($object))
            foreach ($object as $param) {
                self::categorizeParam($queue, $param);
            }
        else
            self::categorizeParam($queue, $object);
        if(Model::CompleteTrans())
            return $queue;
        return null;
    }
    /*metodo para quitar de la cola*/
    public static function delete($id): bool {
        Model::StartTrans();
        $cola = new Cola();
        if(!$cola->delete($id))
            Model::FailTrans();
        return Model::CompleteTrans();
    }

    /*metodo para registrar workers*/
    public static function registerWorker(string $name, AbstractWorker $worker): void {
        self::$workers[$name] = $worker;
    }

    /*metoo para desregistrar workers*/
    public static function deleteWorker($name): void {
        unset(self::$workers[$name]);
    }

    /*metodo para ejecutar cola*/
    public static function run() {
        $queueToProcess = self::getProcessQueue();
        if($queueToProcess->numColumns() < 1)
            return false;
        while ($command = $queueToProcess->fetchArray(SQLITE3_ASSOC)) {
            $cola = new Cola($command);
            $worker = self::$workers[$cola->getName()];
            $params = self::processParams($cola);
            $worker->execute($params);
        }
    }

    private static function categorizeParam(Cola $queue, $object )
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
    private static function processParams(Cola $cola) {
        $result = $cola->obtainRelation("Parameters", "idQueue", $cola->getId());
        $variables = null;
        while ($param = $result->fetchArray(SQLITE3_ASSOC)) {
            $parameters = new Parameters($param);
            $var = $parameters->getParam();
            if(json_decode($var))
                $variables[] = json_decode($var);
            else
                try {
                    $variables[] = unserialize($var);
                } catch (\Error $e) {
                    $variables[] = $var;
                }
        }
        if(count($variables)==1)
            return $variables[0];
        return $variables;
    }

    private static function getProcessQueue() {
        return DBManager::getConection()->query("SELECT * FROM Queue where semaphore = false");
    }
}
