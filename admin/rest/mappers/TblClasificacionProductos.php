<?php

/*
 * TblClasificacionProductosImpl
 */

class TblClasificacionProductosMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.nombre AS nombre "
                . "FROM tbl_clasificacion_productos a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.nombre AS nombre "
                . "FROM tbl_clasificacion_productos a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_clasificacion_productos ("
                . "id, "
                . "nombre "
                . ") VALUES ("
                . ":id, "
                . ":nombre "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "nombre = :nombre "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.nombre AS nombre "
                . "FROM tbl_clasificacion_productos a "
                . "WHERE 1=1 AND id = :id";

    
}

?>