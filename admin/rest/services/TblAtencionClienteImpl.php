<?php

/*
 * TblAtencionClienteImpl
 */

class TblAtencionClienteImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblAtencionClienteLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblAtencionCliente($variable);
                } else {
                    $response = $this->getTblAtencionClientes($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblAtencionCliente($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblAtencionCliente($datos);
                break;
        }
        return $response;
    }

    public function getTblAtencionCliente(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_atencion_cliente = array(TblAtencionClienteMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_atencion_cliente = new TblAtencionCliente();
                $tbl_atencion_cliente->id = $row->id;
                $tbl_atencion_cliente->tipoCliente = $row->tipoCliente;
                $tbl_atencion_cliente->diasAtencion = $row->diasAtencion;
                $tbl_atencion_cliente->diasAlarma = $row->diasAlarma;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_atencion_cliente;
    }

    public function getTblAtencionClientes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblAtencionClienteMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['tipoCliente'])) {
                $sql .= " AND a.tipo_cliente = :tipoCliente";
            }
            if (isset($filters['diasAtencion'])) {
                $sql .= " AND a.dias_atencion = :diasAtencion";
            }
            if (isset($filters['diasAlarma'])) {
                $sql .= " AND a.dias_alarma = :diasAlarma";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['tipoCliente'])) {
                $get_stmt->bindParam(":tipoCliente", $filters['tipoCliente'], PDO::PARAM_STR);
            }
            if (isset($filters['diasAtencion'])) {
                $get_stmt->bindParam(":diasAtencion", $filters['diasAtencion'], PDO::PARAM_STR);
            }
            if (isset($filters['diasAlarma'])) {
                $get_stmt->bindParam(":diasAlarma", $filters['diasAlarma'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_atencion_cliente = new TblAtencionCliente();
                $tbl_atencion_cliente->id = $row->id;
                $tbl_atencion_cliente->tipoCliente = $row->tipoCliente;
                $tbl_atencion_cliente->diasAtencion = $row->diasAtencion;
                $tbl_atencion_cliente->diasAlarma = $row->diasAlarma;
                $arreglo[] = $tbl_atencion_cliente;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblAtencionCliente($data) {
        $this->validateTblAtencionCliente($data);
        return $this->saveTblAtencionCliente($data);
    }

    public function putTblAtencionCliente($data) {
        $this->validateTblAtencionCliente($data);
        return $this->saveTblAtencionCliente($data);
    }

    public function validateTblAtencionCliente($data) {
        
    }
    
    public function saveTblAtencionCliente($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblAtencionClienteMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":tipoCliente", $data->tipoCliente, PDO::PARAM_STR);
        $save_stmt->bindParam(":diasAtencion", $data->diasAtencion, PDO::PARAM_STR);
        $save_stmt->bindParam(":diasAlarma", $data->diasAlarma, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>