<?php

/*
 * DetalleImpl
 */

class DetalleMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.id_orden AS idOrden, "
                . "a.id_producto AS idProducto, "
                . "a.precio AS precio, "
                . "a.cantidad AS cantidad, "
                . "a.descuento AS descuento "
                . "FROM tienda_detalle a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.id_orden AS idOrden, "
                . "a.id_producto AS idProducto, "
                . "a.precio AS precio, "
                . "a.cantidad AS cantidad, "
                . "a.descuento AS descuento "
                . "FROM tienda_detalle a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tienda_detalle ("
                . "id, "
                . "id_orden, "
                . "id_producto, "
                . "precio, "
                . "cantidad, "
                . "descuento "
                . ") VALUES ("
                . ":id, "
                . ":idOrden, "
                . ":idProducto, "
                . ":precio, "
                . ":cantidad, "
                . ":descuento "
                . ") ON DUPLICATE KEY UPDATE "
                . "id_orden = :idOrden, "
                . "id_producto = :idProducto, "
                . "precio = :precio, "
                . "cantidad = :cantidad, "
                . "descuento = :descuento "
                . "";

    const existsById = "SELECT "
                . "IF(COUNT(id) > 0, 1, 0) "
                . "FROM tienda_detalle a "
                . "WHERE 1=1 AND id = :id";

    
}

?>