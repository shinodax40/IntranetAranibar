<?php

/*
 * MarcaImpl
 */

class MarcaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function marcaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getMarca($variable);
                } else {
                    $response = $this->getMarcas($parametro);
                }
                break;
            case METODO_POST:
                $response = $this->postMarca($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putMarca($datos);
                break;
        }
        return $response;
    }

    public function getMarca(int $id) {
        $conn = $this->dbcon->dbConnection();
        $marca = array(MarcaMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $marca = new Marca();
                $marca->codMarca = $row->codMarca;
                $marca->codCategoria = $row->codCategoria;
                $marca->nombreMarca = $row->nombreMarca;
                $marca->codProveedor = $row->codProveedor;
                $marca->activo = $row->activo;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $marca;
    }

    public function getMarcas(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = MarcaMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['codMarca'])) {
                $sql .= " AND a.codMarca = :codMarca";
            }
            if (isset($filters['codCategoria'])) {
                $sql .= " AND a.codCategoria = :codCategoria";
            }
            if (isset($filters['nombreMarca'])) {
                $sql .= " AND a.nombreMarca = :nombreMarca";
            }
            if (isset($filters['codProveedor'])) {
                $sql .= " AND a.cod_proveedor = :codProveedor";
            }
            if (isset($filters['activo'])) {
                $sql .= " AND a.activo = :activo";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['codMarca'])) {
                $get_stmt->bindParam(":codMarca", $filters['codMarca'], PDO::PARAM_STR);
            }
            if (isset($filters['codCategoria'])) {
                $get_stmt->bindParam(":codCategoria", $filters['codCategoria'], PDO::PARAM_STR);
            }
            if (isset($filters['nombreMarca'])) {
                $get_stmt->bindParam(":nombreMarca", $filters['nombreMarca'], PDO::PARAM_STR);
            }
            if (isset($filters['codProveedor'])) {
                $get_stmt->bindParam(":codProveedor", $filters['codProveedor'], PDO::PARAM_STR);
            }
            if (isset($filters['activo'])) {
                $get_stmt->bindParam(":activo", $filters['activo'], PDO::PARAM_STR);
            }
        }
        $get_stmt->execute();
        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $marca = new Marca();
                $marca->codMarca = $row->codMarca;
                $marca->codCategoria = $row->codCategoria;
                $marca->nombreMarca = $row->nombreMarca;
                $marca->codProveedor = $row->codProveedor;
                $marca->activo = $row->activo;
                $arreglo[] = $marca;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function postMarca($data) {
        $this->validateMarca($data);
        return $this->saveMarca($data);
    }

    public function putMarca($data) {
        $this->validateMarca($data);
        return $this->saveMarca($data);
    }

    public function validateMarca($data) {
        
    }
    
    public function saveMarca($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(MarcaMapper::save);
        $save_stmt->bindParam(":codMarca", $data->codMarca, PDO::PARAM_STR);
        $save_stmt->bindParam(":codCategoria", $data->codCategoria, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombreMarca", $data->nombreMarca, PDO::PARAM_STR);
        $save_stmt->bindParam(":codProveedor", $data->codProveedor, PDO::PARAM_STR);
        $save_stmt->bindParam(":activo", $data->activo, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>