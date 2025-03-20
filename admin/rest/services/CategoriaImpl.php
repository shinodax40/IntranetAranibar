<?php

/*
 * CategoriaImpl
 */

class CategoriaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function categoriaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                  $response = $this->getCategorias($variable);
              /*  if (isset($variable)) {
                    $response = $this->getCategorias($variable);
                } else {
                    $response = $this->getCategoria($parametro);
                }*/
                break;
            case METODO_POST:
                $response = $this->postCategoria($datos);
                break;
            case METODO_PUT:
                $datos->id = $variable;
                $response = $this->putCategoria($datos);
                break;
        }
        return $response;
    }

   /* public function getCategoria(int $id) {
        $conn = $this->dbcon->dbConnection();
        $categoria = array(CategoriaMapper::findById);
        $get_stmt = $conn->prepare();
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $categoria = new Categoria();
                $categoria->codCategoria = $row->codCategoria;
                $categoria->nombreCategoria = $row->nombreCategoria;
                $categoria->activo = $row->activo;
                $categoria->grupo = $row->grupo;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $categoria;
    }*/
    

       public function getCategorias(array $filters = null) {
       $conn = $this->dbcon->dbConnection();
       $sql = CategoriaMapper::findAll;
       $get_stmt = $conn->prepare($sql);   
     
           $get_stmt->execute();
        $arreglo = array();   
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                
                $categoria = new Categoria();
                $categoria->id      = $row->id;
                $categoria->nombre  = $row->nombre;
                $categoria->archivo =  $row->id.".jpg";
                 $arreglo[] = $categoria;
            }
        }

        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }
    
 /*   public function getCategoria(array $filters = null) {
        $arreglo = array();
        
        $categoria = new Categoria();
        $categoria->codCategoria = "1";
        $categoria->nombreCategoria = "Perros";
        $categoria->archivo = "1.jpg";
        $arreglo[] = $categoria;
        
        $categoria = new Categoria();
        $categoria->codCategoria = "2";
        $categoria->nombreCategoria = "Gatos";
        $categoria->archivo = "2.jpg";
        $arreglo[] = $categoria;
        
        $categoria = new Categoria();
        $categoria->codCategoria = "3";
        $categoria->nombreCategoria = "Peces";
        $categoria->archivo = "3.jpg";
        $arreglo[] = $categoria;
        
        $categoria = new Categoria();
        $categoria->codCategoria = "4";
        $categoria->nombreCategoria = "Aves";
        $categoria->archivo = "4.jpg";
        $arreglo[] = $categoria;
        
        $categoria = new Categoria();
        $categoria->codCategoria = "5";
        $categoria->nombreCategoria = "Exóticos";
        $categoria->archivo = "5.jpg";
        $arreglo[] = $categoria;
        
        $categoria = new Categoria();
        $categoria->codCategoria = "6";
        $categoria->nombreCategoria = "Ovinos y bovinos";
        $categoria->archivo = "6.jpg";
        $arreglo[] = $categoria;
        
        return $arreglo;
//        $conn = $this->dbcon->dbConnection();
//        $sql = CategoriaMapper::findAll;
//        if (isset($filters)) {
//            if (isset($filters['codCategoria'])) {
//                $sql .= " AND a.codCategoria = :codCategoria";
//            }
//            if (isset($filters['nombreCategoria'])) {
//                $sql .= " AND a.nombreCategoria = :nombreCategoria";
//            }
//            if (isset($filters['activo'])) {
//                $sql .= " AND a.activo = :activo";
//            }
//            if (isset($filters['grupo'])) {
//                $sql .= " AND a.grupo = :grupo";
//            }
//        }
//        $get_stmt = $conn->prepare($sql);
//        if (isset($filters)) {
//            if (isset($filters['codCategoria'])) {
//                $get_stmt->bindParam(":codCategoria", $filters['codCategoria'], PDO::PARAM_STR);
//            }
//            if (isset($filters['nombreCategoria'])) {
//                $get_stmt->bindParam(":nombreCategoria", $filters['nombreCategoria'], PDO::PARAM_STR);
//            }
//            if (isset($filters['activo'])) {
//                $get_stmt->bindParam(":activo", $filters['activo'], PDO::PARAM_STR);
//            }
//            if (isset($filters['grupo'])) {
//                $get_stmt->bindParam(":grupo", $filters['grupo'], PDO::PARAM_STR);
//            }
//        }
//        $get_stmt->execute();
//        $arreglo = array();
//        if ($get_stmt->rowCount() > 0) {
//            while ($row = $get_stmt->fetchObject()) {
//                $categoria = new Categoria();
//                $categoria->codCategoria = $row->codCategoria;
//                $categoria->nombreCategoria = $row->nombreCategoria;
//                $categoria->activo = $row->activo;
//                $categoria->grupo = $row->grupo;
//                $arreglo[] = $categoria;
//            }
//        }
//        $get_stmt = null;
//        $conn = null;
//        return $arreglo;
    }
*/
    public function postCategoria($data) {
        $this->validateCategoria($data);
        return $this->saveCategoria($data);
    }

    public function putCategoria($data) {
        $this->validateCategoria($data);
        return $this->saveCategoria($data);
    }

    public function validateCategoria($data) {
        
    }
    
    public function saveCategoria($data) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(CategoriaMapper::save);
        $save_stmt->bindParam(":codCategoria", $data->codCategoria, PDO::PARAM_STR);
        $save_stmt->bindParam(":nombreCategoria", $data->nombreCategoria, PDO::PARAM_STR);
        $save_stmt->bindParam(":activo", $data->activo, PDO::PARAM_STR);
        $save_stmt->bindParam(":grupo", $data->grupo, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }
    
}

?>