<?php

namespace Adupuy\cdqueue\DB\Models;

class Parameters extends Model {

    static $id_tabla = 'idParameter';
    private $idParameter;
    private $idQueue;
    private $param;

    /**
     * @return mixed
     */
    final public function getIdParameter()
    {
        return $this->idParameter;
    }

    /**
     * @param mixed $idParameter
     * @return Parameters
     */
    public function setIdParameter($idParameter)
    {
        $this->idParameter = $idParameter;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getIdQueue()
    {
        return $this->idQueue;
    }

    /**
     * @param mixed $idQueue
     * @return Parameters
     */
    public function setIdQueue($idQueue)
    {
        $this->idQueue = $idQueue;
        return $this;
    }

    /**
     * @return mixed
     */
    final public function getParam()
    {
        return $this->param;
    }

    /**
     * @param mixed $param
     * @return Parameters
     */
    public function setParam($param)
    {
        $this->param = $param;
        return $this;
    }
}
