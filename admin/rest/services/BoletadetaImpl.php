<?php

/*
 * BoletadetaImpl
 */

class BoletadetaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function boletadetaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getBoletadeta($variable);
                } else {
                    $response = $this->getBoletadetas($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postBoletadeta($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putBoletadeta($datos);
                break;
        }
        return $response;
    }

    public function getBoletadeta(int $id) {
        $conn = $this->dbcon->dbConnection();
        $boletadeta = array(BoletadetaMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $boletadeta = new Boletadeta();
                $boletadeta->id = $row->id;
                $boletadeta->numeroBoleta = $row->numeroBoleta;
                $boletadeta->codProducto = $row->codProducto;
                $boletadeta->tipo = $row->tipo;
                $boletadeta->marca = $row->marca;
                $boletadeta->nombreProd = $row->nombreProd;
                $boletadeta->detalle = $row->detalle;
                $boletadeta->precioCosto = $row->precioCosto;
                $boletadeta->stock = $row->stock;
                $boletadeta->cantidad = $row->cantidad;
                $boletadeta->precioCostoActual = $row->precioCostoActual;
                $boletadeta->total = $row->total;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $boletadeta;
    }

    public function getBoletadetas(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = BoletadetaMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroBoleta'])) {
                $sql .= " AND a.numeroBoleta = :numeroBoleta";
            }
            if (isset($filters['codProducto'])) {
                $sql .= " AND a.codProducto = :codProducto";
            }
            if (isset($filters['tipo'])) {
                $sql .= " AND a.tipo = :tipo";
            }
            if (isset($filters['marca'])) {
                $sql .= " AND a.marca = :marca";
            }
            if (isset($filters['nombreProd'])) {
                $sql .= " AND a.nombreProd = :nombreProd";
            }
            if (isset($filters['detalle'])) {
                $sql .= " AND a.detalle = :detalle";
            }
            if (isset($filters['precioCosto'])) {
                $sql .= " AND a.precioCosto = :precioCosto";
            }
            if (isset($filters['stock'])) {
                $sql .= " AND a.stock = :stock";
            }
            if (isset($filters['cantidad'])) {
                $sql .= " AND a.cantidad = :cantidad";
            }
            if (isset($filters['precioCostoActual'])) {
                $sql .= " AND a.precioCostoActual = :precioCostoActual";
            }
            if (isset($filters['total'])) {
                $sql .= " AND a.total = :total";
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
            if (isset($filters['codProducto'])) {
                $get_stmt->bindParam(":codProducto", $filters['codProducto'], PDO::PARAM_STR);
            }
            if (isset($filters['tipo'])) {
                $get_stmt->bindParam(":tipo", $filters['tipo'], PDO::PARAM_STR);
            }
            if (isset($filters['marca'])) {
                $get_stmt->bindParam(":marca", $filters['marca'], PDO::PARAM_STR);
            }
            if (isset($filters['nombreProd'])) {
                $get_stmt->bindParam(":nombreProd", $filters['nombreProd'], PDO::PARAM_STR);
            }
            if (isset($filters['detalle'])) {
                $get_stmt->bindParam(":detalle", $filters['detalle'], PDO::PARAM_STR);
            }
            if (isset($filters['precioCosto'])) {
                $get_stmt->bindParam(":precioCosto", $filters['precioCosto'], PDO::PARAM_STR);
            }
            if (isset($filters['stock'])) {
                $get_stmt->bindParam(":stock", $filters['stock'], PDO::PARAM_STR);
            }
            if (isset($filters['cantidad'])) {
                $get_stmt->bindParam(":cantidad", $filters['cantidad'], PDO::PARAM_STR);
            }
            if (isset($filters['precioCostoActual'])) {
                $get_stmt->bindParam(":precioCostoActual", $filters['precioCostoActual'], PDO::PARAM_STR);
            }
            if (isset($filters['total'])) {
                $get_stmt->bindParam(":total", $filters['total'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $boletadeta = new Boletadeta();
                $boletadeta->id = $row->id;
                $boletadeta->numeroBoleta = $row->numeroBoleta;
                $boletadeta->codProducto = $row->codProducto;
                $boletadeta->tipo = $row->tipo;
                $boletadeta->marca = $row->marca;
                $boletadeta->nombreProd = $row->nombreProd;
                $boletadeta->detalle = $row->detalle;
                $boletadeta->precioCosto = $row->precioCosto;
                $boletadeta->stock = $row->stock;
                $boletadeta->cantidad = $row->cantidad;
                $boletadeta->precioCostoActual = $row->precioCostoActual;
                $boletadeta->total = $row->total;
                $arreglo[] = $boletadeta;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postBoletadeta($data) {
        $this->validateBoletadeta($data);
        return $this->saveBoletadeta($data);
    }

    public function putBoletadeta($data) {
        $this->validateBoletadeta($data);
        return $this->saveBoletadeta($data);
    }

    public function validateBoletadeta($data) {
        
    }
    
    public function saveBoletadeta($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(BoletadetaMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroBoleta", $data->numeroBoleta, PDO::PARAM_STR);
        $save_stmt->bindParam(":codProducto", $data->codProducto, PDO::PARAM_STR);
        $save_stmt->bindParam(":tipo", $data->tipo, PDO::PARAM_STR);
        $save_stmt->bindParam(":marca", $data->marca, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombreProd", $data->nombreProd, PDO::PARAM_STR);
        $save_stmt->bindParam(":detalle", $data->detalle, PDO::PARAM_STR);
        $save_stmt->bindParam(":precioCosto", $data->precioCosto, PDO::PARAM_STR);
        $save_stmt->bindParam(":stock", $data->stock, PDO::PARAM_STR);
        $save_stmt->bindParam(":cantidad", $data->cantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":precioCostoActual", $data->precioCostoActual, PDO::PARAM_STR);
        $save_stmt->bindParam(":total", $data->total, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>