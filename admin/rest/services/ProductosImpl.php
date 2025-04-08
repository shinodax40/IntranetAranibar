<?php

/*
 * ProductosImpl
 */

class ProductosImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function productosLogic($metodo, $variable, $parametro, $datos) {
        $response = '';
        switch ($metodo) {
            case METODO_GET:
                if (isset($variable)) {
                    $response = $this->getProductosId($variable);
                } else {
                    $response = array(
                        'total' => $this->getCountProductos($parametro),
                        'productos' => $this->getProductos($parametro)
                    );
                }
                break;
            case METODO_POST:

                break;
            case METODO_PUT:

                break;
        }
        return $response;
    }

    public function getCountProductos(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = ProductosMapper::findCount;
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $sql .= "AND p.id_tipo_pagina = :idTipo ";
            }
            if (isset($filters['idSubTipos']) && $filters['idSubTipos'] !== "") {
                $ids = $filters['idSubTipos'];
                $sql .= "AND p.id_sub_pagina IN ($ids) ";
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $get_stmt->bindParam(":idTipo", $filters['idTipo']);
            }
        }
        $get_stmt->execute();

        $count = 0;
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $count = $row->total;
            }
        }

        $get_stmt = null;
        $conn = null;
        return $count;
    }

    public function getProductos(array $filters = null) {
        $conn = $this->dbcon->dbConnection();
        $sql = isset($filters['cantidad']) ? ProductosMapper::findAllFilter : ProductosMapper::findAll;
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $sql .= "AND p.id_tipo_pagina = :idTipo  ";
            }

            if (isset($filters['idSubTipos']) && $filters['idSubTipos'] !== "") {
                $ids = $filters['idSubTipos'];

                if ($filters['idOrden'] == "1") {

                    $sql .= "AND p.id_sub_pagina IN ($ids) ORDER BY aa.salidadProd DESC ";
                } else if ($filters['idOrden'] == "2") {

                    $sql .= "AND p.id_sub_pagina IN ($ids) ORDER BY p.precioPart ASC ";
                } else if ($filters['idOrden'] == "3") {

                    $sql .= "AND p.id_sub_pagina IN ($ids) ORDER BY p.precioPart DESC ";
                } else if ($filters['idOrden'] == "0" || $filters['idOrden'] == "") {

                    $sql .= "AND p.id_sub_pagina IN ($ids) ORDER BY p.nombreProd ASC ";
                }
            }

            if (isset($filters['cantidad'])) {
                $sql .= "LIMIT :cantidad ";
                if (isset($filters['pagina'])) {
                    $sql .= "OFFSET :skip ";
                }
            }
        }
        $get_stmt = $conn->prepare($sql);
        if (isset($filters)) {
            if (isset($filters['idTipo'])) {
                $get_stmt->bindParam(":idTipo", $filters['idTipo']);
            }
            if (isset($filters['cantidad'])) {
                $get_stmt->bindParam(":cantidad", $filters['cantidad'], PDO::PARAM_INT);
                if (isset($filters['pagina'])) {
                    $skip = ($filters['pagina'] - 1) * $filters['cantidad'];
                    $get_stmt->bindParam(":skip", $skip, PDO::PARAM_INT);
                }
            }
        }
        $get_stmt->execute();

        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $productos = new Productos();
                $productos->id = $row->id;
                $productos->nombreProd = $row->nombreProd;
                $productos->stock = $row->stock;
                $productos->precio = $row->precio;
                $productos->nombreTipo = $row->nombreTipo;
                $productos->nombreSubTipo = $row->nombreSubTipo;
                $productos->idCategorias = $row->idCategorias;
                $productos->idSubCategorias = $row->idSubCategorias;
                $productos->archivo = "https://aranibar.cl/barrosaranas/Table/img/" . $row->id . ".png";
                $arreglo[] = $productos;
            }
        }

        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

    public function getProductosId(int $id) {
        $conn = $this->dbcon->dbConnection();
        $sql = ProductosMapper::findId;
        $get_stmt = $conn->prepare($sql);
        $get_stmt->bindParam(":id", $id);
        $get_stmt->execute();

        $arreglo = array();
        if ($get_stmt->rowCount() > 0) {
            while ($row = $get_stmt->fetchObject()) {
                $productos = new Productos();
                $productos->id = $row->id;
                $productos->nombreProd = $row->nombreProd;
                $productos->stock = $row->stock;
                $productos->precio = $row->precio;
                $productos->nombreTipo = $row->nombreTipo;
                $productos->nombreSubTipo = $row->nombreSubTipo;
                $productos->idCategorias = $row->idCategorias;
                $productos->idSubCategorias = $row->idSubCategorias;
                $productos->archivo = "https://aranibar.cl/barrosaranas/Table/img/" . $row->id . ".png";

                $arreglo[] = $productos;
            }
        }

        $get_stmt = null;
        $conn = null;
        return $arreglo;
    }

}

?>