<?php

/*
 * ClienteImpl
 */

class ClienteImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function clienteLogic($metodo, $variable, $parametro, $datos, $cuenta) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getCliente($variable);
                } else {
                    $response = $this->getClientes($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postCliente($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putCliente($datos);
                break;
        }
        return $response;
    }

    public function getCliente(int $id) {
        $conn = $this->dbcon->dbConnection();
        $cliente = array();
        $get_stmt = $conn->prepare(ClienteMapper::findById);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $cliente = new Cliente();
                $cliente->id = $row->id;
                $cliente->nombre = $row->nombre;
                $cliente->celular = $row->celular;
                $cliente->correo = $row->correo;
                $cliente->activo = $row->activo;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $cliente;
    }

    public function getClientes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = ClienteMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['nombre'])) {
                $sql .= " AND a.nombre = :nombre";
            }
            if (isset($filters['correo'])) {
                $sql .= " AND a.correo = :correo";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['nombre'])) {
                $get_stmt->bindParam(":nombre", $filters['nombre'], PDO::PARAM_STR);
            }
            if (isset($filters['correo'])) {
                $get_stmt->bindParam(":correo", $filters['correo'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $cliente = new Cliente();
                $cliente->id = $row->id;
                $cliente->nombre = $row->nombre;
                $cliente->celular = $row->celular;
                $cliente->correo = $row->correo;
                $arreglo[] = $cliente;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postCliente($data) {
        $this->validateCliente($data);
        return $this->saveCliente($data);
    }

    public function putCliente($data) {
        $this->validateCliente($data);
        return $this->saveCliente($data);
    }

    public function validateCliente($data) {
        
    }
    
    public function saveCliente($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(ClienteMapper::save);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":contrasena", $data->contrasena, PDO::PARAM_STR);
        $save_stmt->bindParam(":celular", $data->celular, PDO::PARAM_STR);
        $save_stmt->bindParam(":correo", $data->correo, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>