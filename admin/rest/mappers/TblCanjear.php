<?php

/*
 * TblCanjearImpl
 */

class TblCanjearMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.fecha_ingreso AS fechaIngreso, "
                . "a.cantidad AS cantidad, "
                . "a.id_pedido AS idPedido, "
                . "a.devid AS devid "
                . "FROM tbl_canjear a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.fecha_ingreso AS fechaIngreso, "
                . "a.cantidad AS cantidad, "
                . "a.id_pedido AS idPedido, "
                . "a.devid AS devid "
                . "FROM tbl_canjear a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_canjear ("
                . "id, "
                . "id_prod, "
                . "fecha_ingreso, "
                . "cantidad, "
                . "id_pedido, "
                . "devid "
                . ") VALUES ("
                . ":id, "
                . ":idProd, "
                . ":fechaIngreso, "
                . ":cantidad, "
                . ":idPedido, "
                . ":devid "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "id_prod = :idProd, "
                . "fecha_ingreso = :fechaIngreso, "
                . "cantidad = :cantidad, "
                . "id_pedido = :idPedido, "
                . "devid = :devid "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.id_prod AS idProd, "
                . "a.fecha_ingreso AS fechaIngreso, "
                . "a.cantidad AS cantidad, "
                . "a.id_pedido AS idPedido, "
                . "a.devid AS devid "
                . "FROM tbl_canjear a "
                . "WHERE 1=1 AND id = :id";

    
}

?>