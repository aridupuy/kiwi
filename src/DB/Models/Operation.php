<?php

namespace Adupuy\cdqueue\DB\Models;

use Adupuy\cdqueue\DB\DBManager;

class Operation extends Model {
    static $id_tabla = 'idOperation';
    private $idOperation;
    private $fecha;
    private $desde;
    private $hasta;

    /**
     * @return mixed
     */
    final public function getIdOperation()
    {
        return $this->idOperation;
    }

    /**
     * @param mixed $idOperation
     * @return Operation
     */
    public function setIdOperation($idOperation)
    {
        $this->idOperation = $idOperation;
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
     * @return Operation
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getDesde()
    {
        return $this->desde;
    }

    /**
     * @param mixed $desde
     * @return Operation
     */
    public function setDesde($desde)
    {
        $this->desde = $desde;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getHasta()
    {
        return $this->hasta;
    }

    /**
     * @param mixed $hasta
     * @return Operation
     */
    public function setHasta($hasta)
    {
        $this->hasta = $hasta;
        return $this;
    }

    public static function getLastOperation() {
        $row= DBManager::getConection()->query("SELECT * FROM Operation ORDER BY idOperation DESC LIMIT 1")->fetchArray();
        return new self($row);
    }
}
