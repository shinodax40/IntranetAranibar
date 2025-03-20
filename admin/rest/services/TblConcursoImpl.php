<?php

/*
 * TblConcursoImpl
 */

class TblConcursoImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function tblConcursoLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getTblConcurso($variable);
                } else {
                    $response = $this->getTblConcursos($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postTblConcurso($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putTblConcurso($datos);
                break;
        }
        return $response;
    }

    public function getTblConcurso(int $id) {
        $conn = $this->dbcon->dbConnection();
        $tbl_concurso = array(TblConcursoMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tbl_concurso = new TblConcurso();
                $tbl_concurso->id = $row->id;
                $tbl_concurso->nombres = $row->nombres;
                $tbl_concurso->contacto = $row->contacto;
                $tbl_concurso->email = $row->email;
                $tbl_concurso->alimento = $row->alimento;
                $tbl_concurso->foto = $row->foto;
                $tbl_concurso->calendario = $row->calendario;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $tbl_concurso;
    }

    public function getTblConcursos(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = TblConcursoMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $sql .= " AND a.id = :id";
            }
            if (isset($filters['nombres'])) {
                $sql .= " AND a.nombres = :nombres";
            }
            if (isset($filters['contacto'])) {
                $sql .= " AND a.contacto = :contacto";
            }
            if (isset($filters['email'])) {
                $sql .= " AND a.email = :email";
            }
            if (isset($filters['alimento'])) {
                $sql .= " AND a.alimento = :alimento";
            }
            if (isset($filters['foto'])) {
                $sql .= " AND a.foto = :foto";
            }
            if (isset($filters['calendario'])) {
                $sql .= " AND a.calendario = :calendario";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['id'])) {
                $get_stmt->bindParam(":id", $filters['id'], PDO::PARAM_STR);
            }
            if (isset($filters['nombres'])) {
                $get_stmt->bindParam(":nombres", $filters['nombres'], PDO::PARAM_STR);
            }
            if (isset($filters['contacto'])) {
                $get_stmt->bindParam(":contacto", $filters['contacto'], PDO::PARAM_STR);
            }
            if (isset($filters['email'])) {
                $get_stmt->bindParam(":email", $filters['email'], PDO::PARAM_STR);
            }
            if (isset($filters['alimento'])) {
                $get_stmt->bindParam(":alimento", $filters['alimento'], PDO::PARAM_STR);
            }
            if (isset($filters['foto'])) {
                $get_stmt->bindParam(":foto", $filters['foto'], PDO::PARAM_STR);
            }
            if (isset($filters['calendario'])) {
                $get_stmt->bindParam(":calendario", $filters['calendario'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $tbl_concurso = new TblConcurso();
                $tbl_concurso->id = $row->id;
                $tbl_concurso->nombres = $row->nombres;
                $tbl_concurso->contacto = $row->contacto;
                $tbl_concurso->email = $row->email;
                $tbl_concurso->alimento = $row->alimento;
                $tbl_concurso->foto = $row->foto;
                $tbl_concurso->calendario = $row->calendario;
                $arreglo[] = $tbl_concurso;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postTblConcurso($data) {
        $this->validateTblConcurso($data);
        return $this->saveTblConcurso($data);
    }

    public function putTblConcurso($data) {
        $this->validateTblConcurso($data);
        return $this->saveTblConcurso($data);
    }

    public function validateTblConcurso($data) {
        
    }
    
    public function saveTblConcurso($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TblConcursoMapper::save);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombres", $data->nombres, PDO::PARAM_STR);
        $save_stmt->bindParam(":contacto", $data->contacto, PDO::PARAM_STR);
        $save_stmt->bindParam(":email", $data->email, PDO::PARAM_STR);
        $save_stmt->bindParam(":alimento", $data->alimento, PDO::PARAM_STR);
        $save_stmt->bindParam(":foto", $data->foto, PDO::PARAM_STR);
        $save_stmt->bindParam(":calendario", $data->calendario, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>