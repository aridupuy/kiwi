<?php

namespace Adupuy\cdqueue\DB\Models;

use Adupuy\cdqueue\DB\DBManager;
use SQLite3;
class Model
{

    const PREFIJO_GETTERS = "get";
    const PREFIJO_SETTERS = "set";

    private SQLite3 $db;
    private static $transaccion_actual = 0;
    private static $transaccionFallida = false;
    public function __construct($params = false)
    {
        $this->db = DBManager::getConection();
        $this->init($params);
    }

    public function set($forceInsert = false)
    {
        $table = (new \ReflectionClass($this))->getShortName();
        $methods = array_map(
                function($method) {
                    if(substr($method->name, 0 ,3) == self::PREFIJO_GETTERS and strlen($method->name) > strlen(self::PREFIJO_GETTERS)) {
                        return str_replace(self::PREFIJO_GETTERS, "", $method->name);
                    }
                }
                ,(new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_FINAL)
        );
        $metodoId = "set".static::$id_tabla;
        $getmetodoId = "get".static::$id_tabla;
        $isUpdate = false;
        if(empty($this->$getmetodoId()))
            $this->$metodoId($this->generar_id_maximo(static::$id_tabla, $table));
        else
            $isUpdate = true;
        foreach ($methods as $param) {
            if(empty($param))
                continue;
            $method = "get$param";
            if(!empty($this->$method())){
                $parametros[":$param"] = $param;
                $keys[] = ":$param";
                $valores[":$param"] = $this->$method();
            }
        }
        $values = implode(", ",$keys);
        $params =implode(", ",$parametros);
        if(!$isUpdate or $forceInsert) {
            $sql = "INSERT INTO {$table} ({$params}) VALUES ({$values})";
            $prepare = $this->db->prepare($sql);
            foreach ($parametros as $key =>$param) {
                $prepare->bindParam($key, $valores[$key]);
            }
            return $prepare->execute();
        }
        else {
            $id_tabla = static::$id_tabla;

            foreach ($parametros as $key=> $item) {
                $update[] = "$item = $key";
            }
            $update = implode(", ", $update);
            $sql = "UPDATE {$table} SET {$update} WHERE {$id_tabla} = :Id";
            $prepare = $this->db->prepare($sql);
            foreach ($parametros as $key =>$param) {
                $prepare->bindParam($key, $valores[$key]);
            }
            return $prepare->execute();
        }

    }

    public function get($id) {
        $table = (new \ReflectionClass($this))->getShortName();
        $methodId = static::$id_tabla;
        $sql = "select * from {$table} where $methodId=:id";
        $prepare = $this->db->prepare($sql);
        $prepare->bindParam(":id", $id);
        $sql = $prepare->getSQL(true);
        $result = $this->db->query($sql);
        $this->bindParams($result);
    }

    public function delete ($id) {
        $tabla = (new \ReflectionClass($this))->getShortName();
        $id_tabla = static::$id_tabla;
        $prepare = $this->db->prepare("DELETE FROM $tabla where $id_tabla = :id");
        $prepare->bindParam(":id", $id);
        return $prepare->execute();
    }
    private function bindParams(\SQLite3Result $result) {
        $rows = $result->fetchArray(SQLITE3_ASSOC);
        if($rows and count($rows)>0)
            foreach ( $rows as $key=>$item) {
                $method = "set$key";
                $this->$method($item);
            }
        return $this;
    }

    final public function parametros(){
        $parametros=array();
        $metodos=get_class_methods(get_class($this));
        foreach($metodos as $metodo):
            $atributo=explode(self::PREFIJO_GETTERS, $metodo);
            if(isset($atributo[1]) && $atributo[1]!==''){
                $atributo=strtolower($atributo[1]);
                if(!empty($this->$metodo())){
                    $parametros[$atributo]=$this->$metodo();
                }
            }
        endforeach;
        unset($parametros['conexion']);

        return $parametros;
    }
    private function generar_id_maximo($id_tabla,$tabla) {
        # Genera el siguiente ID, tomando el mayor
        $result=$this->db->query("SELECT max($id_tabla) FROM $tabla");

        # Falta verificar el Overflow
        if($result AND $row = $result->fetchArray(SQLITE3_NUM)){
            return $row[0]+1;
        }
        else return false;
    }

    private function init($variables=[]){
        if(empty($variables))
            return true;
        foreach($variables as $propiedad=>$valor):
            $method=self::PREFIJO_SETTERS.ucfirst($propiedad);
            if(method_exists($this, $method) && $valor!=='')
                $this->$method($valor);
        endforeach;
        return true;
    }
    final public static function StartTrans(){
        if(self::$transaccion_actual > 0){
            self::$transaccion_actual++;
            return true;
        }
        self::$transaccion_actual++;
        error_log(self::$transaccion_actual.' | Comienza una transaccion.');
        return DBManager::getConection()->exec("BEGIN");
    }

    final public static function HasFailedTrans(){
        return self::$transaccionFallida == true;
    }

    final public static function CompleteTrans(){
        if(self::$transaccion_actual > 1){
            self::$transaccion_actual--;
            return true;
        }
        self::$transaccion_actual--;
        error_log(self::$transaccion_actual.' | Completa una transaccion.');
        $db =DBManager::getConection();
        $ejecucion = self::$transaccionFallida ? "ROLLBACK" : "COMMIT";
        error_log("Ejecutando $ejecucion ");
        return self::$transaccionFallida ? $db->exec("ROLLBACK"): $db->exec("COMMIT");
    }

    final public static function FailTrans() {
        error_log(self::$transaccion_actual.' | Falla una transaccion.');
        self::$transaccionFallida = true;
    }

    final public function obtainRelation(string $table,string $coloumnIdTabla, int $id)
    {
        return $this->db->query("SELECT * FROM $table where $coloumnIdTabla = $id");
    }
}
