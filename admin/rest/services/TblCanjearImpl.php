<?php

/*
 * TblCanjearImpl
 */

class TblCanjearImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblCanjearLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblCanjear($variable);
                } else {
                    $response = $this->getTblCanjears($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblCanjear($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblCanjear($datos);
                break;
        }
        return $response;
    }

    public function getTblCanjear(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_canjear = array(TblCanjearMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_canjear = new TblCanjear();
                $tbl_canjear->id = $row->id;
                $tbl_canjear->idProd = $row->idProd;
                $tbl_canjear->fechaIngreso = $row->fechaIngreso;
                $tbl_canjear->cantidad = $row->cantidad;
                $tbl_canjear->idPedido = $row->idPedido;
                $tbl_canjear->devid = $row->devid;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_canjear;
    }

    public function getTblCanjears(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblCanjearMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['idProd'])) {
                $sql .= " AND a.id_prod = :idProd";
            }
            if (isset($filters['fechaIngreso'])) {
                $sql .= " AND a.fecha_ingreso = :fechaIngreso";
            }
            if (isset($filters['cantidad'])) {
                $sql .= " AND a.cantidad = :cantidad";
            }
            if (isset($filters['idPedido'])) {
                $sql .= " AND a.id_pedido = :idPedido";
            }
            if (isset($filters['devid'])) {
                $sql .= " AND a.devid = :devid";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['idProd'])) {
                $get_stmt->bindParam(":idProd", $filters['idProd'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaIngreso'])) {
                $get_stmt->bindParam(":fechaIngreso", $filters['fechaIngreso'], PDO::PARAM_STR);
            }
            if (isset($filters['cantidad'])) {
                $get_stmt->bindParam(":cantidad", $filters['cantidad'], PDO::PARAM_STR);
            }
            if (isset($filters['idPedido'])) {
                $get_stmt->bindParam(":idPedido", $filters['idPedido'], PDO::PARAM_STR);
            }
            if (isset($filters['devid'])) {
                $get_stmt->bindParam(":devid", $filters['devid'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_canjear = new TblCanjear();
                $tbl_canjear->id = $row->id;
                $tbl_canjear->idProd = $row->idProd;
                $tbl_canjear->fechaIngreso = $row->fechaIngreso;
                $tbl_canjear->cantidad = $row->cantidad;
                $tbl_canjear->idPedido = $row->idPedido;
                $tbl_canjear->devid = $row->devid;
                $arreglo[] = $tbl_canjear;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblCanjear($data) {
        $this->validateTblCanjear($data);
        return $this->saveTblCanjear($data);
    }

    public function putTblCanjear($data) {
        $this->validateTblCanjear($data);
        return $this->saveTblCanjear($data);
    }

    public function validateTblCanjear($data) {
        
    }
    
    public function saveTblCanjear($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblCanjearMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":idProd", $data->idProd, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaIngreso", $data->fechaIngreso, PDO::PARAM_STR);
        $save_stmt->bindParam(":cantidad", $data->cantidad, PDO::PARAM_STR);
        $save_stmt->bindParam(":idPedido", $data->idPedido, PDO::PARAM_STR);
        $save_stmt->bindParam(":devid", $data->devid, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>