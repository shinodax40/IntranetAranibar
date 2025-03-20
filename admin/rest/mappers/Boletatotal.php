<?php

/*
 * BoletatotalImpl
 */

class BoletatotalMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalBoleta AS totalBoleta, "
                . "a.fechaBoleta AS fechaBoleta "
                . "FROM boletatotal a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalBoleta AS totalBoleta, "
                . "a.fechaBoleta AS fechaBoleta "
                . "FROM boletatotal a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO boletatotal ("
                . "id, "
                . "numeroBoleta, "
                . "totalCantidad, "
                . "totalPrecio, "
                . "totalBoleta, "
                . "fechaBoleta "
                . ") VALUES ("
                . ":id, "
                . ":numeroBoleta, "
                . ":totalCantidad, "
                . ":totalPrecio, "
                . ":totalBoleta, "
                . ":fechaBoleta "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroBoleta = :numeroBoleta, "
                . "totalCantidad = :totalCantidad, "
                . "totalPrecio = :totalPrecio, "
                . "totalBoleta = :totalBoleta, "
                . "fechaBoleta = :fechaBoleta "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.totalCantidad AS totalCantidad, "
                . "a.totalPrecio AS totalPrecio, "
                . "a.totalBoleta AS totalBoleta, "
                . "a.fechaBoleta AS fechaBoleta "
                . "FROM boletatotal a "
                . "WHERE 1=1 AND id = :id";

    
}

?>