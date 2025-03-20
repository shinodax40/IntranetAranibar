<?php

/*
 * FacturatotalImpl
 */

class FacturatotalMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalFactura AS totalFactura, "
                . "a.fechaFactura AS fechaFactura, "
                . "a.totalDescuento AS totalDescuento "
                . "FROM facturatotal a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalFactura AS totalFactura, "
                . "a.fechaFactura AS fechaFactura, "
                . "a.totalDescuento AS totalDescuento "
                . "FROM facturatotal a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO facturatotal ("
                . "id, "
                . "numeroFactura, "
                . "totalCantidad, "
                . "totalPrecio, "
                . "totalFactura, "
                . "fechaFactura, "
                . "totalDescuento "
                . ") VALUES ("
                . ":id, "
                . ":numeroFactura, "
                . ":totalCantidad, "
                . ":totalPrecio, "
                . ":totalFactura, "
                . ":fechaFactura, "
                . ":totalDescuento "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroFactura = :numeroFactura, "
                . "totalCantidad = :totalCantidad, "
                . "totalPrecio = :totalPrecio, "
                . "totalFactura = :totalFactura, "
                . "fechaFactura = :fechaFactura, "
                . "totalDescuento = :totalDescuento "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalFactura AS totalFactura, "
                . "a.fechaFactura AS fechaFactura, "
                . "a.totalDescuento AS totalDescuento "
                . "FROM facturatotal a "
                . "WHERE 1=1 AND id = :id";

    
}

?>