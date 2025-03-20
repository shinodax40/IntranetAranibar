<?php

/*
 * TblCodigoBarraExtraImpl
 */

class TblCodigoBarraExtraImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblCodigoBarraExtraLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblCodigoBarraExtra($variable);
                } else {
                    $response = $this->getTblCodigoBarraExtras($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblCodigoBarraExtra($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblCodigoBarraExtra($datos);
                break;
        }
        return $response;
    }

    public function getTblCodigoBarraExtra(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_codigo_barra_extra = array(TblCodigoBarraExtraMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_codigo_barra_extra = new TblCodigoBarraExtra();
                $tbl_codigo_barra_extra->id = $row->id;
                $tbl_codigo_barra_extra->idProd = $row->idProd;
                $tbl_codigo_barra_extra->codigoBarra = $row->codigoBarra;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_codigo_barra_extra;
    }

    public function getTblCodigoBarraExtras(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblCodigoBarraExtraMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['idProd'])) {
                $sql .= " AND a.id_prod = :idProd";
            }
            if (isset($filters['codigoBarra'])) {
                $sql .= " AND a.codigo_barra = :codigoBarra";
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
            if (isset($filters['codigoBarra'])) {
                $get_stmt->bindParam(":codigoBarra", $filters['codigoBarra'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_codigo_barra_extra = new TblCodigoBarraExtra();
                $tbl_codigo_barra_extra->id = $row->id;
                $tbl_codigo_barra_extra->idProd = $row->idProd;
                $tbl_codigo_barra_extra->codigoBarra = $row->codigoBarra;
                $arreglo[] = $tbl_codigo_barra_extra;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblCodigoBarraExtra($data) {
        $this->validateTblCodigoBarraExtra($data);
        return $this->saveTblCodigoBarraExtra($data);
    }

    public function putTblCodigoBarraExtra($data) {
        $this->validateTblCodigoBarraExtra($data);
        return $this->saveTblCodigoBarraExtra($data);
    }

    public function validateTblCodigoBarraExtra($data) {
        
    }
    
    public function saveTblCodigoBarraExtra($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblCodigoBarraExtraMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":idProd", $data->idProd, PDO::PARAM_STR);
        $save_stmt->bindParam(":codigoBarra", $data->codigoBarra, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>