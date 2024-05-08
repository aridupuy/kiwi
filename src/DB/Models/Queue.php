<?php

namespace Adupuy\cdqueue\DB\Models;

class Queue extends Model {
    static $id_tabla = "id";

    private $id;
    private $name;
    private $fecha;
    private $semaphore;

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

}
