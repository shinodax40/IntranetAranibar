<?php

/*
 * DireccionImpl
 */

class DireccionMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.calle AS calle, "
                . "a.numero AS numero, "
                . "a.tipo AS tipo, "
                . "a.id_cliente AS idCliente "
                . "FROM tienda_direccion a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.calle AS calle, "
                . "a.numero AS numero, "
                . "a.tipo AS tipo, "
                . "a.id_cliente AS idCliente "
                . "FROM tienda_direccion a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tienda_direccion ("
                . "id, "
                . "calle, "
                . "numero, "
                . "tipo, "
                . "id_cliente "
                . ") VALUES ("
                . ":id, "
                . ":calle, "
                . ":numero, "
                . ":tipo, "
                . ":idCliente "
                . ") ON DUPLICATE KEY UPDATE "
                . "calle = :calle, "
                . "numero = :numero, "
                . "tipo = :tipo, "
                . "id_cliente = :idCliente "
                . "";

    const existsById = "SELECT "
                . "IF(COUNT(id) > 0, 1, 0) "
                . "FROM tienda_direccion a "
                . "WHERE 1=1 AND id = :id";

    
}

?>