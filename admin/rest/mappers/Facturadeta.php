<?php

/*
 * FacturadetaImpl
 */

class FacturadetaMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precio AS precio, "
                . "a.total AS total, "
                . "a.descuento AS descuento "
                . "FROM facturadeta a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precio AS precio, "
                . "a.total AS total, "
                . "a.descuento AS descuento "
                . "FROM facturadeta a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO facturadeta ("
                . "id, "
                . "numeroFactura, "
                . "codProducto, "
                . "tipo, "
                . "marca, "
                . "nombreProd, "
                . "detalle, "
                . "precioCosto, "
                . "stock, "
                . "cantidad, "
                . "precio, "
                . "total, "
                . "descuento "
                . ") VALUES ("
                . ":id, "
                . ":numeroFactura, "
                . ":codProducto, "
                . ":tipo, "
                . ":marca, "
                . ":nombreProd, "
                . ":detalle, "
                . ":precioCosto, "
                . ":stock, "
                . ":cantidad, "
                . ":precio, "
                . ":total, "
                . ":descuento "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroFactura = :numeroFactura, "
                . "codProducto = :codProducto, "
                . "tipo = :tipo, "
                . "marca = :marca, "
                . "nombreProd = :nombreProd, "
                . "detalle = :detalle, "
                . "precioCosto = :precioCosto, "
                . "stock = :stock, "
                . "cantidad = :cantidad, "
                . "precio = :precio, "
                . "total = :total, "
                . "descuento = :descuento "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precio AS precio, "
                . "a.total AS total, "
                . "a.descuento AS descuento "
                . "FROM facturadeta a "
                . "WHERE 1=1 AND id = :id";

    
}

?>