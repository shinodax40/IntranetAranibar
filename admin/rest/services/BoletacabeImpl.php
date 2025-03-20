<?php

/*
 * BoletacabeImpl
 */

class BoletacabeImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function boletacabeLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getBoletacabe($variable);
                } else {
                    $response = $this->getBoletacabes($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postBoletacabe($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putBoletacabe($datos);
                break;
        }
        return $response;
    }

    public function getBoletacabe(int $id) {
        $conn = $this->dbcon->dbConnection();
        $boletacabe = array(BoletacabeMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $boletacabe = new Boletacabe();
                $boletacabe->id = $row->id;
                $boletacabe->numeroBoleta = $row->numeroBoleta;
                $boletacabe->nombre = $row->nombre;
                $boletacabe->fecha = $row->fecha;
                $boletacabe->estadoBol = $row->estadoBol;
                $boletacabe->idUsuario = $row->idUsuario;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $boletacabe;
    }

    public function getBoletacabes(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = BoletacabeMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['numeroBoleta'])) {
                $sql .= " AND a.numeroBoleta = :numeroBoleta";
            }
            if (isset($filters['nombre'])) {
                $sql .= " AND a.nombre = :nombre";
            }
            if (isset($filters['fecha'])) {
                $sql .= " AND a.fecha = :fecha";
            }
            if (isset($filters['estadoBol'])) {
                $sql .= " AND a.estadoBol = :estadoBol";
            }
            if (isset($filters['idUsuario'])) {
                $sql .= " AND a.id_usuario = :idUsuario";
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
            if (isset($filters['nombre'])) {
                $get_stmt->bindParam(":nombre", $filters['nombre'], PDO::PARAM_STR);
            }
            if (isset($filters['fecha'])) {
                $get_stmt->bindParam(":fecha", $filters['fecha'], PDO::PARAM_STR);
            }
            if (isset($filters['estadoBol'])) {
                $get_stmt->bindParam(":estadoBol", $filters['estadoBol'], PDO::PARAM_STR);
            }
            if (isset($filters['idUsuario'])) {
                $get_stmt->bindParam(":idUsuario", $filters['idUsuario'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $boletacabe = new Boletacabe();
                $boletacabe->id = $row->id;
                $boletacabe->numeroBoleta = $row->numeroBoleta;
                $boletacabe->nombre = $row->nombre;
                $boletacabe->fecha = $row->fecha;
                $boletacabe->estadoBol = $row->estadoBol;
                $boletacabe->idUsuario = $row->idUsuario;
                $arreglo[] = $boletacabe;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postBoletacabe($data) {
        $this->validateBoletacabe($data);
        return $this->saveBoletacabe($data);
    }

    public function putBoletacabe($data) {
        $this->validateBoletacabe($data);
        return $this->saveBoletacabe($data);
    }

    public function validateBoletacabe($data) {
        
    }
    
    public function saveBoletacabe($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(BoletacabeMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":numeroBoleta", $data->numeroBoleta, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombre", $data->nombre, PDO::PARAM_STR);
        $save_stmt->bindParam(":fecha", $data->fecha, PDO::PARAM_STR);
        $save_stmt->bindParam(":estadoBol", $data->estadoBol, PDO::PARAM_STR);
        $save_stmt->bindParam(":idUsuario", $data->idUsuario, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>