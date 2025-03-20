<?php

/*
 * CategoriaImpl
 */

class SubCategoriaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function subCategoriaLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getSubCategoriaId($variable);
                } else {
                    $response = $this->getSubCategorias($parametro);
                }
                break;
            case METODO_POST:

                break;
            case METODO_PUT:

                break;
        }
        return $response;
    }

    public function getSubCategorias(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = SubCategoriaMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $sql .= "AND p.id_tipo_pagina = :idTipo ORDER BY p.orden ASC ";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $get_stmt->bindParam(":idTipo", $filters['idTipo']);
            }
        }
        $get_stmt->execute();

        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {

                $categoria = new Categoria();
                $categoria->id = $row->id;
                $categoria->nombre = $row->nombre;
                $arreglo[] = $categoria;
            }
        }

        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function getSubCategoriaId(int $id) {
        $conn = $this->dbcon->dbConnection();
        $sql = SubCategoriaMapper::findById;
        $get_stmt = $conn->prepare($sql);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();

        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $categoria = new Categoria();
                $categoria->id = $row->id;
                $categoria->nombre = $row->nombre;
                $arreglo[] = $categoria;
            }
        }
        $get_stmt = null;
        $conn = null;
        return $categoria;
    }

}

?>