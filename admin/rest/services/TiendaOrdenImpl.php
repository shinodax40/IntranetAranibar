<?php

/*
 * OrdenImpl
 */

class OrdenImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function ordenLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getOrden($variable);
                } else {
                    $response = $this->getOrdens($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postOrden($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putOrden($datos);
                break;
        }
        return $response;
    }

    public function getOrden(int $id) {
        $conn = $this->dbcon->dbConnection();
        $orden = array();
        $get_stmt = $conn->prepare(OrdenMapper::findById);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $orden = new Orden();
                $orden->id = $row->id;
                $orden->fecha = $row->fecha;
                $orden->metodoPago = $row->metodoPago;
                $orden->estado = $row->estado;
                $orden->nombre = $row->nombre;
                $orden->celular = $row->celular;
                $orden->correo = $row->correo;
                $orden->calle = $row->calle;
                $orden->numero = $row->numero;
                $orden->idCliente = $row->idCliente;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $orden;
    }

    public function getOrdenes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = OrdenMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idCliente'])) {
                $sql .= " AND a.id_cliente = :idCliente";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idCliente'])) {
                $get_stmt->bindParam(":idCliente", $filters['idCliente'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $orden = new Orden();
                $orden->id = $row->id;
                $orden->fecha = $row->fecha;
                $orden->metodoPago = $row->metodoPago;
                $orden->estado = $row->estado;
                $orden->nombre = $row->nombre;
                $orden->celular = $row->celular;
                $orden->correo = $row->correo;
                $orden->calle = $row->calle;
                $orden->numero = $row->numero;
                $orden->idCliente = $row->idCliente;
                $arreglo[] = $orden;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postOrden($data) {
        $this->validateOrden($data);
        return $this->saveOrden($data);
    }

    public function putOrden($data) {
        $this->validateOrden($data);
        return $this->saveOrden($data);
    }

    public function validateOrden($data) {
        
    }
    
    public function saveOrden($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(OrdenMapper::save);
        $save_stmt->bindParam(":fecha", $data->fecha, PDO::PARAM_STR);
        $save_stmt->bindParam(":metodoPago", $data->metodoPago, PDO::PARAM_STR);
        $save_stmt->bindParam(":estado", $data->estado, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":celular", $data->celular, PDO::PARAM_STR);
        $save_stmt->bindParam(":correo", $data->correo, PDO::PARAM_STR);
        $save_stmt->bindParam(":calle", $data->calle, PDO::PARAM_STR);
        $save_stmt->bindParam(":numero", $data->numero, PDO::PARAM_STR);
        $save_stmt->bindParam(":idCliente", $data->idCliente, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>