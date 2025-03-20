<?php

/*
 * BoletacabeImpl
 */

class BoletacabeMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoBol AS estadoBol, "
                . "a.id_usuario AS idUsuario "
                . "FROM boletacabe a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoBol AS estadoBol, "
                . "a.id_usuario AS idUsuario "
                . "FROM boletacabe a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO boletacabe ("
                . "id, "
                . "numeroBoleta, "
                . "nombre, "
                . "fecha, "
                . "estadoBol, "
                . "id_usuario "
                . ") VALUES ("
                . ":id, "
                . ":numeroBoleta, "
                . ":nombre, "
                . ":fecha, "
                . ":estadoBol, "
                . ":idUsuario "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "numeroBoleta = :numeroBoleta, "
                . "nombre = :nombre, "
                . "fecha = :fecha, "
                . "estadoBol = :estadoBol, "
                . "id_usuario = :idUsuario "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.numeroBoleta AS numeroBoleta, "
                . "a.nombre AS nombre, "
                . "a.fecha AS fecha, "
                . "a.estadoBol AS estadoBol, "
                . "a.id_usuario AS idUsuario "
                . "FROM boletacabe a "
                . "WHERE 1=1 AND id = :id";

    
}

?>