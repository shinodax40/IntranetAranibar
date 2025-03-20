<?php

/*
 * TblCajaImpl
 */

class TblCajaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblCajaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblCaja($variable);
                } else {
                    $response = $this->getTblCajas($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblCaja($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblCaja($datos);
                break;
        }
        return $response;
    }

    public function getTblCaja(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_caja = array(TblCajaMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_caja = new TblCaja();
                $tbl_caja->idCaja = $row->idCaja;
                $tbl_caja->fechaCajaInicio = $row->fechaCajaInicio;
                $tbl_caja->horaCajaInicio = $row->horaCajaInicio;
                $tbl_caja->fechaCajaFin = $row->fechaCajaFin;
                $tbl_caja->horaCajaFin = $row->horaCajaFin;
                $tbl_caja->idUsuario = $row->idUsuario;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_caja;
    }

    public function getTblCajas(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblCajaMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idCaja'])) {
                $sql .= " AND a.id_caja = :idCaja";
            }
            if (isset($filters['fechaCajaInicio'])) {
                $sql .= " AND a.fecha_caja_inicio = :fechaCajaInicio";
            }
            if (isset($filters['horaCajaInicio'])) {
                $sql .= " AND a.hora_caja_inicio = :horaCajaInicio";
            }
            if (isset($filters['fechaCajaFin'])) {
                $sql .= " AND a.fecha_caja_fin = :fechaCajaFin";
            }
            if (isset($filters['horaCajaFin'])) {
                $sql .= " AND a.hora_caja_fin = :horaCajaFin";
            }
            if (isset($filters['idUsuario'])) {
                $sql .= " AND a.id_usuario = :idUsuario";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idCaja'])) {
                $get_stmt->bindParam(":idCaja", $filters['idCaja'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaCajaInicio'])) {
                $get_stmt->bindParam(":fechaCajaInicio", $filters['fechaCajaInicio'], PDO::PARAM_STR);
            }
            if (isset($filters['horaCajaInicio'])) {
                $get_stmt->bindParam(":horaCajaInicio", $filters['horaCajaInicio'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaCajaFin'])) {
                $get_stmt->bindParam(":fechaCajaFin", $filters['fechaCajaFin'], PDO::PARAM_STR);
            }
            if (isset($filters['horaCajaFin'])) {
                $get_stmt->bindParam(":horaCajaFin", $filters['horaCajaFin'], PDO::PARAM_STR);
            }
            if (isset($filters['idUsuario'])) {
                $get_stmt->bindParam(":idUsuario", $filters['idUsuario'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_caja = new TblCaja();
                $tbl_caja->idCaja = $row->idCaja;
                $tbl_caja->fechaCajaInicio = $row->fechaCajaInicio;
                $tbl_caja->horaCajaInicio = $row->horaCajaInicio;
                $tbl_caja->fechaCajaFin = $row->fechaCajaFin;
                $tbl_caja->horaCajaFin = $row->horaCajaFin;
                $tbl_caja->idUsuario = $row->idUsuario;
                $arreglo[] = $tbl_caja;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblCaja($data) {
        $this->validateTblCaja($data);
        return $this->saveTblCaja($data);
    }

    public function putTblCaja($data) {
        $this->validateTblCaja($data);
        return $this->saveTblCaja($data);
    }

    public function validateTblCaja($data) {
        
    }
    
    public function saveTblCaja($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblCajaMapper::save);
        $save_stmt->bindParam(":idCaja", $data->idCaja, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaCajaInicio", $data->fechaCajaInicio, PDO::PARAM_STR);
        $save_stmt->bindParam(":horaCajaInicio", $data->horaCajaInicio, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaCajaFin", $data->fechaCajaFin, PDO::PARAM_STR);
        $save_stmt->bindParam(":horaCajaFin", $data->horaCajaFin, PDO::PARAM_STR);
        $save_stmt->bindParam(":idUsuario", $data->idUsuario, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>