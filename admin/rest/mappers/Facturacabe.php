<?php

/*
 * FacturacabeImpl
 */

class FacturacabeMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoFac AS estadoFac "
                . "FROM facturacabe a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoFac AS estadoFac "
                . "FROM facturacabe a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO facturacabe ("
                . "id, "
                . "numeroFactura, "
                . "nombre, "
                . "fecha, "
                . "estadoFac "
                . ") VALUES ("
                . ":id, "
                . ":numeroFactura, "
                . ":nombre, "
                . ":fecha, "
                . ":estadoFac "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroFactura = :numeroFactura, "
                . "nombre = :nombre, "
                . "fecha = :fecha, "
                . "estadoFac = :estadoFac "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroFactura AS numeroFactura, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoFac AS estadoFac "
                . "FROM facturacabe a "
                . "WHERE 1=1 AND id = :id";

    
}

?>