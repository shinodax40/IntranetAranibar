<?php

/*
 * BoletatotalImpl
 */

class BoletatotalImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function boletatotalLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getBoletatotal($variable);
                } else {
                    $response = $this->getBoletatotals($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postBoletatotal($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putBoletatotal($datos);
                break;
        }
        return $response;
    }

    public function getBoletatotal(int $id) {
        $conn = $this->dbcon->dbConnection();
        $boletatotal = array(BoletatotalMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $boletatotal = new Boletatotal();
                $boletatotal->id = $row->id;
                $boletatotal->numeroBoleta = $row->numeroBoleta;
                $boletatotal->totalCantidad = $row->totalCantidad;
                $boletatotal->totalPrecio = $row->totalPrecio;
                $boletatotal->totalBoleta = $row->totalBoleta;
                $boletatotal->fechaBoleta = $row->fechaBoleta;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $boletatotal;
    }

    public function getBoletatotals(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = BoletatotalMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroBoleta'])) {
                $sql .= " AND a.numeroBoleta = :numeroBoleta";
            }
            if (isset($filters['totalCantidad'])) {
                $sql .= " AND a.totalCantidad = :totalCantidad";
            }
            if (isset($filters['totalPrecio'])) {
                $sql .= " AND a.totalPrecio = :totalPrecio";
            }
            if (isset($filters['totalBoleta'])) {
                $sql .= " AND a.totalBoleta = :totalBoleta";
            }
            if (isset($filters['fechaBoleta'])) {
                $sql .= " AND a.fechaBoleta = :fechaBoleta";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['numeroBoleta'])) {
                $get_stmt->bindParam(":numeroBoleta", $filters['numeroBoleta'], PDO::PARAM_STR);
            }
            if (isset($filters['totalCantidad'])) {
                $get_stmt->bindParam(":totalCantidad", $filters['totalCantidad'], PDO::PARAM_STR);
            }
            if (isset($filters['totalPrecio'])) {
                $get_stmt->bindParam(":totalPrecio", $filters['totalPrecio'], PDO::PARAM_STR);
            }
            if (isset($filters['totalBoleta'])) {
                $get_stmt->bindParam(":totalBoleta", $filters['totalBoleta'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaBoleta'])) {
                $get_stmt->bindParam(":fechaBoleta", $filters['fechaBoleta'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $boletatotal = new Boletatotal();
                $boletatotal->id = $row->id;
                $boletatotal->numeroBoleta = $row->numeroBoleta;
                $boletatotal->totalCantidad = $row->totalCantidad;
                $boletatotal->totalPrecio = $row->totalPrecio;
                $boletatotal->totalBoleta = $row->totalBoleta;
                $boletatotal->fechaBoleta = $row->fechaBoleta;
                $arreglo[] = $boletatotal;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postBoletatotal($data) {
        $this->validateBoletatotal($data);
        return $this->saveBoletatotal($data);
    }

    public function putBoletatotal($data) {
        $this->validateBoletatotal($data);
        return $this->saveBoletatotal($data);
    }

    public function validateBoletatotal($data) {
        
    }
    
    public function saveBoletatotal($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(BoletatotalMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroBoleta", $data->numeroBoleta, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalCantidad", $data->totalCantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalPrecio", $data->totalPrecio, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalBoleta", $data->totalBoleta, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaBoleta", $data->fechaBoleta, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>