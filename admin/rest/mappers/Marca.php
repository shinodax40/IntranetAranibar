<?php

/*
 * MarcaImpl
 */

class MarcaMapper {

    const findAll = "SELECT "
                . "a.codMarca AS codMarca, "
                . "a.codCategoria AS codCategoria, "
                . "a.nombreMarca AS nombreMarca, "
                . "a.cod_proveedor AS codProveedor, "
                . "a.activo AS activo "
                . "FROM marca a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.codMarca AS codMarca, "
                . "a.codCategoria AS codCategoria, "
                . "a.nombreMarca AS nombreMarca, "
                . "a.cod_proveedor AS codProveedor, "
                . "a.activo AS activo "
                . "FROM marca a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO marca ("
                . "codMarca, "
                . "codCategoria, "
                . "nombreMarca, "
                . "cod_proveedor, "
                . "activo "
                . ") VALUES ("
                . ":codMarca, "
                . ":codCategoria, "
                . ":nombreMarca, "
                . ":codProveedor, "
                . ":activo "
                . ") ON DUPLICATE KEY UPDATE "
                . "codMarca = :codMarca, "
                . "codCategoria = :codCategoria, "
                . "nombreMarca = :nombreMarca, "
                . "cod_proveedor = :codProveedor, "
                . "activo = :activo "
                . "";

    const existsById = "SELECT "
                . "a.codMarca AS codMarca, "
                . "a.codCategoria AS codCategoria, "
                . "a.nombreMarca AS nombreMarca, "
                . "a.cod_proveedor AS codProveedor, "
                . "a.activo AS activo "
                . "FROM marca a "
                . "WHERE 1=1 AND id = :id";

    
}

?>