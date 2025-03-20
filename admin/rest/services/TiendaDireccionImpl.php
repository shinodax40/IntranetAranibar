<?php

/*
 * DireccionImpl
 */

class DireccionImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function direccionLogic($metodo, $variable, $parametro, $datos, $cuenta) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getDireccion($variable);
                } else {
                    $response = $this->getDirecciones($parametro);
                }
                break;
            case METODO_POST:
                $datos->idCliente = $cuenta->id;
                $response = $this->postDireccion($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $datos->idCliente = $cuenta->id;
                $response = $this->putDireccion($datos);
                break;
        }
        return $response;
    }

    public function getDireccion(int $id) {
        $conn = $this->dbcon->dbConnection();
        $direccion = array();
        $get_stmt = $conn->prepare(DireccionMapper::findById);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $direccion = new Direccion();
                $direccion->id = $row->id;
                $direccion->calle = $row->calle;
                $direccion->numero = $row->numero;
                $direccion->tipo = $row->tipo;
                $direccion->idCliente = $row->idCliente;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $direccion;
    }

    public function getDirecciones(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = DireccionMapper::findAll;
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
                $direccion = new Direccion();
                $direccion->id = $row->id;
                $direccion->calle = $row->calle;
                $direccion->numero = $row->numero;
                $direccion->tipo = $row->tipo;
                $direccion->idCliente = $row->idCliente;
                $arreglo[] = $direccion;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postDireccion($data) {
        $this->validateDireccion($data);
        return $this->saveDireccion($data);
    }

    public function putDireccion($data) {
        $this->validateDireccion($data);
        return $this->saveDireccion($data);
    }

    public function validateDireccion($data) {
        
    }

    public function saveDireccion($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(DireccionMapper::save);
        $save_stmt->bindParam(":calle", $data->calle, PDO::PARAM_STR);
        $save_stmt->bindParam(":numero", $data->numero, PDO::PARAM_STR);
        $save_stmt->bindParam(":tipo", $data->tipo, PDO::PARAM_STR);
        $save_stmt->bindParam(":idCliente", $data->idCliente, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }

}

?>