<?php

/*
 * CategoriaImpl
 */

class CategoriaMapper {

    const findAll = "SELECT 
                     p.id,
                     p.nombre
                     FROM tbl_tipo_pagina p";
    
    /*const findById = "SELECT "
                . "a.codCategoria AS codCategoria, "
                . "a.nombreCategoria AS nombreCategoria, "
                . "a.activo AS activo, "
                . "a.grupo AS grupo "
                . "FROM categoria a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO categoria ("
                . "codCategoria, "
                . "nombreCategoria, "
                . "activo, "
                . "grupo "
                . ") VALUES ("
                . ":codCategoria, "
                . ":nombreCategoria, "
                . ":activo, "
                . ":grupo "
                . ") ON DUPLICATE KEY UPDATE "
                . "codCategoria = :codCategoria, "
                . "nombreCategoria = :nombreCategoria, "
                . "activo = :activo, "
                . "grupo = :grupo "
                . "";

    const existsById = "SELECT "
                . "a.codCategoria AS codCategoria, "
                . "a.nombreCategoria AS nombreCategoria, "
                . "a.activo AS activo, "
                . "a.grupo AS grupo "
                . "FROM categoria a "
                . "WHERE 1=1 AND id = :id";*/

    
}







?>