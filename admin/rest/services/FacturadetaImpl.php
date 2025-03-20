<?php

/*
 * FacturadetaImpl
 */

class FacturadetaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function facturadetaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getFacturadeta($variable);
                } else {
                    $response = $this->getFacturadetas($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postFacturadeta($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putFacturadeta($datos);
                break;
        }
        return $response;
    }

    public function getFacturadeta(int $id) {
        $conn = $this->dbcon->dbConnection();
        $facturadeta = array(FacturadetaMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $facturadeta = new Facturadeta();
                $facturadeta->id = $row->id;
                $facturadeta->numeroFactura = $row->numeroFactura;
                $facturadeta->codProducto = $row->codProducto;
                $facturadeta->tipo = $row->tipo;
                $facturadeta->marca = $row->marca;
                $facturadeta->nombreProd = $row->nombreProd;
                $facturadeta->detalle = $row->detalle;
                $facturadeta->precioCosto = $row->precioCosto;
                $facturadeta->stock = $row->stock;
                $facturadeta->cantidad = $row->cantidad;
                $facturadeta->precio = $row->precio;
                $facturadeta->total = $row->total;
                $facturadeta->descuento = $row->descuento;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $facturadeta;
    }

    public function getFacturadetas(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = FacturadetaMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroFactura'])) {
                $sql .= " AND a.numeroFactura = :numeroFactura";
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
            if (isset($filters['precio'])) {
                $sql .= " AND a.precio = :precio";
            }
            if (isset($filters['total'])) {
                $sql .= " AND a.total = :total";
            }
            if (isset($filters['descuento'])) {
                $sql .= " AND a.descuento = :descuento";
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
            if (isset($filters['precio'])) {
                $get_stmt->bindParam(":precio", $filters['precio'], PDO::PARAM_STR);
            }
            if (isset($filters['total'])) {
                $get_stmt->bindParam(":total", $filters['total'], PDO::PARAM_STR);
            }
            if (isset($filters['descuento'])) {
                $get_stmt->bindParam(":descuento", $filters['descuento'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $facturadeta = new Facturadeta();
                $facturadeta->id = $row->id;
                $facturadeta->numeroFactura = $row->numeroFactura;
                $facturadeta->codProducto = $row->codProducto;
                $facturadeta->tipo = $row->tipo;
                $facturadeta->marca = $row->marca;
                $facturadeta->nombreProd = $row->nombreProd;
                $facturadeta->detalle = $row->detalle;
                $facturadeta->precioCosto = $row->precioCosto;
                $facturadeta->stock = $row->stock;
                $facturadeta->cantidad = $row->cantidad;
                $facturadeta->precio = $row->precio;
                $facturadeta->total = $row->total;
                $facturadeta->descuento = $row->descuento;
                $arreglo[] = $facturadeta;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postFacturadeta($data) {
        $this->validateFacturadeta($data);
        return $this->saveFacturadeta($data);
    }

    public function putFacturadeta($data) {
        $this->validateFacturadeta($data);
        return $this->saveFacturadeta($data);
    }

    public function validateFacturadeta($data) {
        
    }
    
    public function saveFacturadeta($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(FacturadetaMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroFactura", $data->numeroFactura, PDO::PARAM_STR);
        $save_stmt->bindParam(":codProducto", $data->codProducto, PDO::PARAM_STR);
        $save_stmt->bindParam(":tipo", $data->tipo, PDO::PARAM_STR);
        $save_stmt->bindParam(":marca", $data->marca, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombreProd", $data->nombreProd, PDO::PARAM_STR);
        $save_stmt->bindParam(":detalle", $data->detalle, PDO::PARAM_STR);
        $save_stmt->bindParam(":precioCosto", $data->precioCosto, PDO::PARAM_STR);
        $save_stmt->bindParam(":stock", $data->stock, PDO::PARAM_STR);
        $save_stmt->bindParam(":cantidad", $data->cantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":precio", $data->precio, PDO::PARAM_STR);
        $save_stmt->bindParam(":total", $data->total, PDO::PARAM_STR);
        $save_stmt->bindParam(":descuento", $data->descuento, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>