<?php

/*
 * TblClasificacionProductosImpl
 */

class TblClasificacionProductosImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblClasificacionProductosLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblClasificacionProductos($variable);
                } else {
                    $response = $this->getTblClasificacionProductoss($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblClasificacionProductos($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblClasificacionProductos($datos);
                break;
        }
        return $response;
    }

    public function getTblClasificacionProductos(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_clasificacion_productos = array(TblClasificacionProductosMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_clasificacion_productos = new TblClasificacionProductos();
                $tbl_clasificacion_productos->id = $row->id;
                $tbl_clasificacion_productos->nombre = $row->nombre;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_clasificacion_productos;
    }

    public function getTblClasificacionProductoss(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblClasificacionProductosMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['nombre'])) {
                $sql .= " AND a.nombre = :nombre";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['nombre'])) {
                $get_stmt->bindParam(":nombre", $filters['nombre'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_clasificacion_productos = new TblClasificacionProductos();
                $tbl_clasificacion_productos->id = $row->id;
                $tbl_clasificacion_productos->nombre = $row->nombre;
                $arreglo[] = $tbl_clasificacion_productos;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblClasificacionProductos($data) {
        $this->validateTblClasificacionProductos($data);
        return $this->saveTblClasificacionProductos($data);
    }

    public function putTblClasificacionProductos($data) {
        $this->validateTblClasificacionProductos($data);
        return $this->saveTblClasificacionProductos($data);
    }

    public function validateTblClasificacionProductos($data) {
        
    }
    
    public function saveTblClasificacionProductos($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblClasificacionProductosMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>