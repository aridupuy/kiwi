<?php

namespace Adupuy\cdqueue\DB\Models;

use Adupuy\cdqueue\DB\DBManager;

class Queue extends Model {
    static $id_tabla = "id";

    private $id;
    private $name;
    private $fecha;
    private $semaphore;
    private $errormessage;
    private $Ejecuciones;

    /**
     * @return mixed
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Queue
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     * @return Queue
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getSemaphore()
    {
        return $this->semaphore;
    }

    /**
     * @param mixed $semaphore
     * @return Queue
     */
    public function setSemaphore($semaphore)
    {
        $this->semaphore = $semaphore;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Queue
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getErrormessage()
    {
        return $this->errormessage;
    }

    /**
     * @param mixed $errormessage
     */
    public function setErrormessage($errormessage)
    {
        $this->errormessage = $errormessage;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getEjecuciones()
    {
        return $this->Ejecuciones;
    }

    /**
     * @param mixed $Ejecuciones
     */
    public function setEjecuciones($Ejecuciones): self
    {
        $this->Ejecuciones = $Ejecuciones;
        return $this;
    }

    public function deleteParams( $idQueue) {
        DBManager::getConection()->exec("DELETE FROM Parameters where idQueue = $idQueue");
    }
}
