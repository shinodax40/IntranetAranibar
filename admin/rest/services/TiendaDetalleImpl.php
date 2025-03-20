<?php

/*
 * DetalleImpl
 */

class DetalleImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function detalleLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getDetalle($variable);
                } else {
                    $response = $this->getDetalles($parametro);
                }
                break;
        }
        return $response;
    }

    public function getDetalle(int $id) {
        $conn = $this->dbcon->dbConnection();
        $detalle = array();
        $get_stmt = $conn->prepare(DetalleMapper::findById);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $detalle = new Detalle();
                $detalle->id = $row->id;
                $detalle->idOrden = $row->idOrden;
                $detalle->idProducto = $row->idProducto;
                $detalle->precio = $row->precio;
                $detalle->cantidad = $row->cantidad;
                $detalle->descuento = $row->descuento;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $detalle;
    }

    public function getDetalles(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = DetalleMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idOrden'])) {
                $sql .= " AND a.id_orden = :idOrden";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idOrden'])) {
                $get_stmt->bindParam(":idOrden", $filters['idOrden'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $detalle = new Detalle();
                $detalle->id = $row->id;
                $detalle->idOrden = $row->idOrden;
                $detalle->idProducto = $row->idProducto;
                $detalle->precio = $row->precio;
                $detalle->cantidad = $row->cantidad;
                $detalle->descuento = $row->descuento;
                $arreglo[] = $detalle;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postDetalle($data) {
        $this->validateDetalle($data);
        return $this->saveDetalle($data);
    }

    public function putDetalle($data) {
        $this->validateDetalle($data);
        return $this->saveDetalle($data);
    }

    public function validateDetalle($data) {
        
    }
    
    public function saveDetalle($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(DetalleMapper::save);
        $save_stmt->bindParam(":idOrden", $data->idOrden, PDO::PARAM_STR);
        $save_stmt->bindParam(":idProducto", $data->idProducto, PDO::PARAM_STR);
        $save_stmt->bindParam(":precio", $data->precio, PDO::PARAM_STR);
        $save_stmt->bindParam(":cantidad", $data->cantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":descuento", $data->descuento, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>