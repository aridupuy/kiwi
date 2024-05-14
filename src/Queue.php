<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\DBManager;
use Adupuy\cdqueue\DB\Models\Model;
use Adupuy\cdqueue\DB\Models\Operation;
use \Adupuy\cdqueue\DB\Models\Queue as Cola;
use Adupuy\cdqueue\Parameters\AbstractParam;
use Dompdf\Exception;
use PHPUnit\Framework\Error;


class Queue
{
    /** @type AbstractWorker[] */
    private static $workers;
    const MAX_PER_DEPH = 1000;
    private static $offset;
    private static $operacion;
    private static $idsAliberar;

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

    public static function registerInformation()
    {

    }

    /*metoo para desregistrar workers*/
    public static function deleteWorker($name): void {
        unset(self::$workers[$name]);
    }

    /*metodo para ejecutar cola*/
    public static function run() {
        /*Registrar Proceso*/
        $queueLastOperation = self::getLastOperation();
        $operation = new Operation();
        $operation->setDesde($queueLastOperation->getHasta() ? $queueLastOperation->getHasta() + 1 : 0);
        $operation->setHasta($queueLastOperation->getDesde() + self::MAX_PER_DEPH);
        $operation->setFecha((new \DateTime("now"))->format("Y-m-d H:i:s"));
        if(!$operation->set()) {
            return false;
        }
        self::$operacion = $operation;

        self::$offset = $queueLastOperation->getDesde() > 0 ? $queueLastOperation->getDesde()+ self::MAX_PER_DEPH : 0;
        /*tomar cola*/
        $result = self::getProcessQueue(self::MAX_PER_DEPH);
        $queueToProcess = self::extractData($result);
        /*semaforizar cola*/
        self::tomarSemaforo();
        self::$idsAliberar = [];

        foreach ($queueToProcess as $queue) {
            self::$idsAliberar[] = $queue["Id"];
        }

        foreach ($queueToProcess as $command) {
            $cola = new Cola($command);

            $worker = self::$workers[$cola->getName()];
            $params = self::processParams($cola);
            try {
                if($worker->execute($params)){
                    $cola->delete($cola->getId());
                    $cola->deleteParams($cola->getId());
                }
                else{
                    $cola->setErrormessage($worker->getErrorMessage());
                    $cola->setEjecuciones($cola->getEjecuciones()+1);
                    $cola->set();
                }
            } catch (\Exception $e ){
                error_log($e->getMessage());
                continue;
            } catch (\Error $e) {
                error_log($e->getMessage());
                continue;
            }
        }
        return true;
    }

    private static function extractData ($data): array {
        $return = [];
        while ($command = $data->fetchArray(SQLITE3_ASSOC)) {
            $return [] = $command;
        }
        return $return;
    }
    private static function tomarSemaforo(): void {
        $offset = self::$offset;
        $max = self::MAX_PER_DEPH;
        Semaphore::tomarSemaforo($offset, $max);
    }

    public static function liberarSemaforo(): void {
        Semaphore::liberarSemaforo(self::$idsAliberar);
    }

    public static function liberarOperacion(): void {
        if(empty(self::$operacion))
            return;
        self::$operacion->delete(self::$operacion->getIdOperation());
    }
    private static function getLastOperation(): Operation {
        return Operation::getLastOperation();
    }

    private static function categorizeParam(Cola $queue, $object ): void
    {
        CategorizeParams::categorize($queue, $object);
    }
    private static function processParams(Cola $cola): AbstractParam {
        return CategorizeParams::processParams($cola);
    }

    private static function getProcessQueue(int $max): \SQLite3Result {
        $offset = self::$offset;
        $ejecuciones = Semaphore::MAX_EXECUTION;
        return DBManager::getConection()->query("SELECT * FROM Queue where Semaphore = false and Ejecuciones < $ejecuciones LIMIT $max OFFSET $offset");
    }
}


register_shutdown_function(function() {
    error_log( "Terminando libero disponibilidad");
    Queue::liberarSemaforo();
    Queue::liberarOperacion();
});

set_exception_handler(function (...$string) {
    error_log( "Terminando libero por error");

    Queue::liberarSemaforo();
    Queue::liberarOperacion();
    exit();
});
