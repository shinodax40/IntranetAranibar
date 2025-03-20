<?php

/*
 * TblCodigoBarraExtraImpl
 */

class TblCodigoBarraExtraMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.codigo_barra AS codigoBarra "
                . "FROM tbl_codigo_barra_extra a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.codigo_barra AS codigoBarra "
                . "FROM tbl_codigo_barra_extra a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_codigo_barra_extra ("
                . "id, "
                . "id_prod, "
                . "codigo_barra "
                . ") VALUES ("
                . ":id, "
                . ":idProd, "
                . ":codigoBarra "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "id_prod = :idProd, "
                . "codigo_barra = :codigoBarra "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.codigo_barra AS codigoBarra "
                . "FROM tbl_codigo_barra_extra a "
                . "WHERE 1=1 AND id = :id";

    
}

?>