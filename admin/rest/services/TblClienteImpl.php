<?php

/*
 * TblClienteImpl
 */

class TblClienteImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblClienteLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblCliente($variable);
                } else {
                    $response = $this->getTblClientes($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblCliente($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblCliente($datos);
                break;
        }
        return $response;
    }

    public function getTblCliente(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_cliente = array(TblClienteMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_cliente = new TblCliente();
                $tbl_cliente->idCliente = $row->idCliente;
                $tbl_cliente->rut = $row->rut;
                $tbl_cliente->nombre = $row->nombre;
                $tbl_cliente->direccion = $row->direccion;
                $tbl_cliente->correo = $row->correo;
                $tbl_cliente->telefono = $row->telefono;
                $tbl_cliente->giro = $row->giro;
                $tbl_cliente->comuna = $row->comuna;
                $tbl_cliente->tipoComprador = $row->tipoComprador;
                $tbl_cliente->idUsuario = $row->idUsuario;
                $tbl_cliente->observacion = $row->observacion;
                $tbl_cliente->banderaNew = $row->banderaNew;
                $tbl_cliente->fechaIngreso = $row->fechaIngreso;
                $tbl_cliente->activo = $row->activo;
                $tbl_cliente->idObservacion = $row->idObservacion;
                $tbl_cliente->fechaObsMod = $row->fechaObsMod;
                $tbl_cliente->credito = $row->credito;
                $tbl_cliente->idSector = $row->idSector;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_cliente;
    }

    public function getTblClientes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblClienteMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idCliente'])) {
                $sql .= " AND a.id_cliente = :idCliente";
            }
            if (isset($filters['rut'])) {
                $sql .= " AND a.rut = :rut";
            }
            if (isset($filters['nombre'])) {
                $sql .= " AND a.nombre = :nombre";
            }
            if (isset($filters['direccion'])) {
                $sql .= " AND a.direccion = :direccion";
            }
            if (isset($filters['correo'])) {
                $sql .= " AND a.correo = :correo";
            }
            if (isset($filters['telefono'])) {
                $sql .= " AND a.telefono = :telefono";
            }
            if (isset($filters['giro'])) {
                $sql .= " AND a.giro = :giro";
            }
            if (isset($filters['comuna'])) {
                $sql .= " AND a.comuna = :comuna";
            }
            if (isset($filters['tipoComprador'])) {
                $sql .= " AND a.tipo_comprador = :tipoComprador";
            }
            if (isset($filters['idUsuario'])) {
                $sql .= " AND a.id_usuario = :idUsuario";
            }
            if (isset($filters['observacion'])) {
                $sql .= " AND a.observacion = :observacion";
            }
            if (isset($filters['banderaNew'])) {
                $sql .= " AND a.bandera_new = :banderaNew";
            }
            if (isset($filters['fechaIngreso'])) {
                $sql .= " AND a.fecha_ingreso = :fechaIngreso";
            }
            if (isset($filters['activo'])) {
                $sql .= " AND a.activo = :activo";
            }
            if (isset($filters['idObservacion'])) {
                $sql .= " AND a.id_observacion = :idObservacion";
            }
            if (isset($filters['fechaObsMod'])) {
                $sql .= " AND a.fecha_obs_mod = :fechaObsMod";
            }
            if (isset($filters['credito'])) {
                $sql .= " AND a.credito = :credito";
            }
            if (isset($filters['idSector'])) {
                $sql .= " AND a.id_sector = :idSector";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idCliente'])) {
                $get_stmt->bindParam(":idCliente", $filters['idCliente'], PDO::PARAM_STR);
            }
            if (isset($filters['rut'])) {
                $get_stmt->bindParam(":rut", $filters['rut'], PDO::PARAM_STR);
            }
            if (isset($filters['nombre'])) {
                $get_stmt->bindParam(":nombre", $filters['nombre'], PDO::PARAM_STR);
            }
            if (isset($filters['direccion'])) {
                $get_stmt->bindParam(":direccion", $filters['direccion'], PDO::PARAM_STR);
            }
            if (isset($filters['correo'])) {
                $get_stmt->bindParam(":correo", $filters['correo'], PDO::PARAM_STR);
            }
            if (isset($filters['telefono'])) {
                $get_stmt->bindParam(":telefono", $filters['telefono'], PDO::PARAM_STR);
            }
            if (isset($filters['giro'])) {
                $get_stmt->bindParam(":giro", $filters['giro'], PDO::PARAM_STR);
            }
            if (isset($filters['comuna'])) {
                $get_stmt->bindParam(":comuna", $filters['comuna'], PDO::PARAM_STR);
            }
            if (isset($filters['tipoComprador'])) {
                $get_stmt->bindParam(":tipoComprador", $filters['tipoComprador'], PDO::PARAM_STR);
            }
            if (isset($filters['idUsuario'])) {
                $get_stmt->bindParam(":idUsuario", $filters['idUsuario'], PDO::PARAM_STR);
            }
            if (isset($filters['observacion'])) {
                $get_stmt->bindParam(":observacion", $filters['observacion'], PDO::PARAM_STR);
            }
            if (isset($filters['banderaNew'])) {
                $get_stmt->bindParam(":banderaNew", $filters['banderaNew'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaIngreso'])) {
                $get_stmt->bindParam(":fechaIngreso", $filters['fechaIngreso'], PDO::PARAM_STR);
            }
            if (isset($filters['activo'])) {
                $get_stmt->bindParam(":activo", $filters['activo'], PDO::PARAM_STR);
            }
            if (isset($filters['idObservacion'])) {
                $get_stmt->bindParam(":idObservacion", $filters['idObservacion'], PDO::PARAM_STR);
            }
            if (isset($filters['fechaObsMod'])) {
                $get_stmt->bindParam(":fechaObsMod", $filters['fechaObsMod'], PDO::PARAM_STR);
            }
            if (isset($filters['credito'])) {
                $get_stmt->bindParam(":credito", $filters['credito'], PDO::PARAM_STR);
            }
            if (isset($filters['idSector'])) {
                $get_stmt->bindParam(":idSector", $filters['idSector'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_cliente = new TblCliente();
                $tbl_cliente->idCliente = $row->idCliente;
                $tbl_cliente->rut = $row->rut;
                $tbl_cliente->nombre = $row->nombre;
                $tbl_cliente->direccion = $row->direccion;
                $tbl_cliente->correo = $row->correo;
                $tbl_cliente->telefono = $row->telefono;
                $tbl_cliente->giro = $row->giro;
                $tbl_cliente->comuna = $row->comuna;
                $tbl_cliente->tipoComprador = $row->tipoComprador;
                $tbl_cliente->idUsuario = $row->idUsuario;
                $tbl_cliente->observacion = $row->observacion;
                $tbl_cliente->banderaNew = $row->banderaNew;
                $tbl_cliente->fechaIngreso = $row->fechaIngreso;
                $tbl_cliente->activo = $row->activo;
                $tbl_cliente->idObservacion = $row->idObservacion;
                $tbl_cliente->fechaObsMod = $row->fechaObsMod;
                $tbl_cliente->credito = $row->credito;
                $tbl_cliente->idSector = $row->idSector;
                $arreglo[] = $tbl_cliente;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblCliente($data) {
        $this->validateTblCliente($data);
        return $this->saveTblCliente($data);
    }

    public function putTblCliente($data) {
        $this->validateTblCliente($data);
        return $this->saveTblCliente($data);
    }

    public function validateTblCliente($data) {
        
    }
    
    public function saveTblCliente($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblClienteMapper::save);
        $save_stmt->bindParam(":idCliente", $data->idCliente, PDO::PARAM_STR);
        $save_stmt->bindParam(":rut", $data->rut, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":direccion", $data->direccion, PDO::PARAM_STR);
        $save_stmt->bindParam(":correo", $data->correo, PDO::PARAM_STR);
        $save_stmt->bindParam(":telefono", $data->telefono, PDO::PARAM_STR);
        $save_stmt->bindParam(":giro", $data->giro, PDO::PARAM_STR);
        $save_stmt->bindParam(":comuna", $data->comuna, PDO::PARAM_STR);
        $save_stmt->bindParam(":tipoComprador", $data->tipoComprador, PDO::PARAM_STR);
        $save_stmt->bindParam(":idUsuario", $data->idUsuario, PDO::PARAM_STR);
        $save_stmt->bindParam(":observacion", $data->observacion, PDO::PARAM_STR);
        $save_stmt->bindParam(":banderaNew", $data->banderaNew, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaIngreso", $data->fechaIngreso, PDO::PARAM_STR);
        $save_stmt->bindParam(":activo", $data->activo, PDO::PARAM_STR);
        $save_stmt->bindParam(":idObservacion", $data->idObservacion, PDO::PARAM_STR);
        $save_stmt->bindParam(":fechaObsMod", $data->fechaObsMod, PDO::PARAM_STR);
        $save_stmt->bindParam(":credito", $data->credito, PDO::PARAM_STR);
        $save_stmt->bindParam(":idSector", $data->idSector, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>