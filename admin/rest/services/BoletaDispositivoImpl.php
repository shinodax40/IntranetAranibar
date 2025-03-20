<?php

/*
 * BoletaDispositivoImpl
 */

class BoletaDispositivoImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function boletaDispositivoLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getBoletaDispositivo($variable);
                } else {
                    $response = $this->getBoletaDispositivos($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postBoletaDispositivo($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putBoletaDispositivo($datos);
                break;
        }
        return $response;
    }

    public function getBoletaDispositivo(int $id) {
        $conn = $this->dbcon->dbConnection();
        $boleta_dispositivo = array(BoletaDispositivoMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $boleta_dispositivo = new BoletaDispositivo();
                $boleta_dispositivo->id = $row->id;
                $boleta_dispositivo->total = $row->total;
                $boleta_dispositivo->emision = $row->emision;
                $boleta_dispositivo->deviceId = $row->deviceId;
                $boleta_dispositivo->folio = $row->folio;
                $boleta_dispositivo->afecto = $row->afecto;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $boleta_dispositivo;
    }

    public function getBoletaDispositivos(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = BoletaDispositivoMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['total'])) {
                $sql .= " AND a.total = :total";
            }
            if (isset($filters['emision'])) {
                $sql .= " AND a.emision = :emision";
            }
            if (isset($filters['deviceId'])) {
                $sql .= " AND a.deviceId = :deviceId";
            }
            if (isset($filters['folio'])) {
                $sql .= " AND a.folio = :folio";
            }
            if (isset($filters['afecto'])) {
                $sql .= " AND a.afecto = :afecto";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['total'])) {
                $get_stmt->bindParam(":total", $filters['total'], PDO::PARAM_STR);
            }
            if (isset($filters['emision'])) {
                $get_stmt->bindParam(":emision", $filters['emision'], PDO::PARAM_STR);
            }
            if (isset($filters['deviceId'])) {
                $get_stmt->bindParam(":deviceId", $filters['deviceId'], PDO::PARAM_STR);
            }
            if (isset($filters['folio'])) {
                $get_stmt->bindParam(":folio", $filters['folio'], PDO::PARAM_STR);
            }
            if (isset($filters['afecto'])) {
                $get_stmt->bindParam(":afecto", $filters['afecto'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $boleta_dispositivo = new BoletaDispositivo();
                $boleta_dispositivo->id = $row->id;
                $boleta_dispositivo->total = $row->total;
                $boleta_dispositivo->emision = $row->emision;
                $boleta_dispositivo->deviceId = $row->deviceId;
                $boleta_dispositivo->folio = $row->folio;
                $boleta_dispositivo->afecto = $row->afecto;
                $arreglo[] = $boleta_dispositivo;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postBoletaDispositivo($data) {
        $this->validateBoletaDispositivo($data);
        return $this->saveBoletaDispositivo($data);
    }

    public function putBoletaDispositivo($data) {
        $this->validateBoletaDispositivo($data);
        return $this->saveBoletaDispositivo($data);
    }

    public function validateBoletaDispositivo($data) {
        
    }
    
    public function saveBoletaDispositivo($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(BoletaDispositivoMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":total", $data->total, PDO::PARAM_STR);
        $save_stmt->bindParam(":emision", $data->emision, PDO::PARAM_STR);
        $save_stmt->bindParam(":deviceId", $data->deviceId, PDO::PARAM_STR);
        $save_stmt->bindParam(":folio", $data->folio, PDO::PARAM_STR);
        $save_stmt->bindParam(":afecto", $data->afecto, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>