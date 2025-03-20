<?php

/*
 * FacturatotalImpl
 */

class FacturatotalImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function facturatotalLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getFacturatotal($variable);
                } else {
                    $response = $this->getFacturatotals($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postFacturatotal($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putFacturatotal($datos);
                break;
        }
        return $response;
    }

    public function getFacturatotal(int $id) {
        $conn = $this->dbcon->dbConnection();
        $facturatotal = array(FacturatotalMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $facturatotal = new Facturatotal();
                $facturatotal->id = $row->id;
                $facturatotal->numeroFactura = $row->numeroFactura;
                $facturatotal->totalCantidad = $row->totalCantidad;
                $facturatotal->totalPrecio = $row->totalPrecio;
                $facturatotal->totalFactura = $row->totalFactura;
                $facturatotal->fechaFactura = $row->fechaFactura;
                $facturatotal->totalDescuento = $row->totalDescuento;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $facturatotal;
    }

    public function getFacturatotals(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = FacturatotalMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroFactura'])) {
                $sql .= " AND a.numeroFactura = :numeroFactura";
            }
            if (isset($filters['totalCantidad'])) {
                $sql .= " AND a.totalCantidad = :totalCantidad";
            }
            if (isset($filters['totalPrecio'])) {
                $sql .= " AND a.totalPrecio = :totalPrecio";
            }
            if (isset($filters['totalFactura'])) {
                $sql .= " AND a.totalFactura = :totalFactura";
            }
            if (isset($filters['fechaFactura'])) {
                $sql .= " AND a.fechaFactura = :fechaFactura";
            }
            if (isset($filters['totalDescuento'])) {
                $sql .= " AND a.totalDescuento = :totalDescuento";
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
            if (isset($filters['totalCantidad'])) {
                $get_stmt->bindParam(":totalCantidad", $filters['totalCantidad'], PDO::PARAM_STR);
            }
            if (isset($filters['totalPrecio'])) {
                $get_stmt->bindParam(":totalPrecio", $filters['totalPrecio'], PDO::PARAM_STR);
            }
            if (isset($filters['totalFactura'])) {
                $get_stmt->bindParam(":totalFactura", $filters['totalFactura'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaFactura'])) {
                $get_stmt->bindParam(":fechaFactura", $filters['fechaFactura'], PDO::PARAM_STR);
            }
            if (isset($filters['totalDescuento'])) {
                $get_stmt->bindParam(":totalDescuento", $filters['totalDescuento'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $facturatotal = new Facturatotal();
                $facturatotal->id = $row->id;
                $facturatotal->numeroFactura = $row->numeroFactura;
                $facturatotal->totalCantidad = $row->totalCantidad;
                $facturatotal->totalPrecio = $row->totalPrecio;
                $facturatotal->totalFactura = $row->totalFactura;
                $facturatotal->fechaFactura = $row->fechaFactura;
                $facturatotal->totalDescuento = $row->totalDescuento;
                $arreglo[] = $facturatotal;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postFacturatotal($data) {
        $this->validateFacturatotal($data);
        return $this->saveFacturatotal($data);
    }

    public function putFacturatotal($data) {
        $this->validateFacturatotal($data);
        return $this->saveFacturatotal($data);
    }

    public function validateFacturatotal($data) {
        
    }
    
    public function saveFacturatotal($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(FacturatotalMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroFactura", $data->numeroFactura, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalCantidad", $data->totalCantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalPrecio", $data->totalPrecio, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalFactura", $data->totalFactura, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaFactura", $data->fechaFactura, PDO::PARAM_STR);
        $save_stmt->bindParam(":totalDescuento", $data->totalDescuento, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>