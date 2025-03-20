<?php

/*
 * BoletadetaImpl
 */

class BoletadetaMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precioCostoActual AS precioCostoActual, "
                . "a.total AS total "
                . "FROM boletadeta a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precioCostoActual AS precioCostoActual, "
                . "a.total AS total "
                . "FROM boletadeta a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO boletadeta ("
                . "id, "
                . "numeroBoleta, "
                . "codProducto, "
                . "tipo, "
                . "marca, "
                . "nombreProd, "
                . "detalle, "
                . "precioCosto, "
                . "stock, "
                . "cantidad, "
                . "precioCostoActual, "
                . "total "
                . ") VALUES ("
                . ":id, "
                . ":numeroBoleta, "
                . ":codProducto, "
                . ":tipo, "
                . ":marca, "
                . ":nombreProd, "
                . ":detalle, "
                . ":precioCosto, "
                . ":stock, "
                . ":cantidad, "
                . ":precioCostoActual, "
                . ":total "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroBoleta = :numeroBoleta, "
                . "codProducto = :codProducto, "
                . "tipo = :tipo, "
                . "marca = :marca, "
                . "nombreProd = :nombreProd, "
                . "detalle = :detalle, "
                . "precioCosto = :precioCosto, "
                . "stock = :stock, "
                . "cantidad = :cantidad, "
                . "precioCostoActual = :precioCostoActual, "
                . "total = :total "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.codProducto AS codProducto, "
                . "a.tipo AS tipo, "
                . "a.marca AS marca, "
                . "a.nombreProd AS nombreProd, "
                . "a.detalle AS detalle, "
                . "a.precioCosto AS precioCosto, "
                . "a.stock AS stock, "
                . "a.cantidad AS cantidad, "
                . "a.precioCostoActual AS precioCostoActual, "
                . "a.total AS total "
                . "FROM boletadeta a "
                . "WHERE 1=1 AND id = :id";

    
}

?>