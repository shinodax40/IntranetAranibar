<?php

/*
 * FacturacabeImpl
 */

class FacturacabeImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function facturacabeLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getFacturacabe($variable);
                } else {
                    $response = $this->getFacturacabes($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postFacturacabe($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putFacturacabe($datos);
                break;
        }
        return $response;
    }

    public function getFacturacabe(int $id) {
        $conn = $this->dbcon->dbConnection();
        $facturacabe = array(FacturacabeMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $facturacabe = new Facturacabe();
                $facturacabe->id = $row->id;
                $facturacabe->numeroFactura = $row->numeroFactura;
                $facturacabe->nombre = $row->nombre;
                $facturacabe->fecha = $row->fecha;
                $facturacabe->estadoFac = $row->estadoFac;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $facturacabe;
    }

    public function getFacturacabes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = FacturacabeMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroFactura'])) {
                $sql .= " AND a.numeroFactura = :numeroFactura";
            }
            if (isset($filters['nombre'])) {
                $sql .= " AND a.nombre = :nombre";
            }
            if (isset($filters['fecha'])) {
                $sql .= " AND a.fecha = :fecha";
            }
            if (isset($filters['estadoFac'])) {
                $sql .= " AND a.estadoFac = :estadoFac";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['numeroFactura'])) {
                $get_stmt->bindParam(":numeroFactura", $filters['numeroFactura'], PDO::PARAM_STR);
            }
            if (isset($filters['nombre'])) {
                $get_stmt->bindParam(":nombre", $filters['nombre'], PDO::PARAM_STR);
            }
            if (isset($filters['fecha'])) {
                $get_stmt->bindParam(":fecha", $filters['fecha'], PDO::PARAM_STR);
            }
            if (isset($filters['estadoFac'])) {
                $get_stmt->bindParam(":estadoFac", $filters['estadoFac'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $facturacabe = new Facturacabe();
                $facturacabe->id = $row->id;
                $facturacabe->numeroFactura = $row->numeroFactura;
                $facturacabe->nombre = $row->nombre;
                $facturacabe->fecha = $row->fecha;
                $facturacabe->estadoFac = $row->estadoFac;
                $arreglo[] = $facturacabe;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postFacturacabe($data) {
        $this->validateFacturacabe($data);
        return $this->saveFacturacabe($data);
    }

    public function putFacturacabe($data) {
        $this->validateFacturacabe($data);
        return $this->saveFacturacabe($data);
    }

    public function validateFacturacabe($data) {
        
    }
    
    public function saveFacturacabe($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(FacturacabeMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroFactura", $data->numeroFactura, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":fecha", $data->fecha, PDO::PARAM_STR);
        $save_stmt->bindParam(":estadoFac", $data->estadoFac, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>