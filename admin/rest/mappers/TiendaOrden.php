<?php

/*
 * OrdenImpl
 */

class OrdenMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.fecha AS fecha, "
                . "a.metodo_pago AS metodoPago, "
                . "a.estado AS estado, "
                . "a.nombre AS nombre, "
                . "a.celular AS celular, "
                . "a.correo AS correo, "
                . "a.calle AS calle, "
                . "a.numero AS numero, "
                . "a.id_cliente AS idCliente "
                . "FROM tienda_orden a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.fecha AS fecha, "
                . "a.metodo_pago AS metodoPago, "
                . "a.estado AS estado, "
                . "a.nombre AS nombre, "
                . "a.celular AS celular, "
                . "a.correo AS correo, "
                . "a.calle AS calle, "
                . "a.numero AS numero, "
                . "a.id_cliente AS idCliente "
                . "FROM tienda_orden a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tienda_orden ("
                . "id, "
                . "fecha, "
                . "metodo_pago, "
                . "estado, "
                . "nombre, "
                . "celular, "
                . "correo, "
                . "calle, "
                . "numero, "
                . "id_cliente "
                . ") VALUES ("
                . ":id, "
                . ":fecha, "
                . ":metodoPago, "
                . ":estado, "
                . ":nombre, "
                . ":celular, "
                . ":correo, "
                . ":calle, "
                . ":numero, "
                . ":idCliente "
                . ") ON DUPLICATE KEY UPDATE "
                . "fecha = :fecha, "
                . "metodo_pago = :metodoPago, "
                . "estado = :estado, "
                . "nombre = :nombre, "
                . "celular = :celular, "
                . "correo = :correo, "
                . "calle = :calle, "
                . "numero = :numero, "
                . "id_cliente = :idCliente "
                . "";

    const existsById = "SELECT "
                . "IF(COUNT(id) > 0, 1, 0) "
                . "FROM tienda_orden a "
                . "WHERE 1=1 AND id = :id";

    
}

?>