<?php

/*
 * BoletaDispositivoImpl
 */

class BoletaDispositivoMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.total AS total, "
                . "a.emision AS emision, "
                . "a.deviceId AS deviceId, "
                . "a.folio AS folio, "
                . "a.afecto AS afecto "
                . "FROM boleta_dispositivo a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.total AS total, "
                . "a.emision AS emision, "
                . "a.deviceId AS deviceId, "
                . "a.folio AS folio, "
                . "a.afecto AS afecto "
                . "FROM boleta_dispositivo a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO boleta_dispositivo ("
                . "id, "
                . "total, "
                . "emision, "
                . "deviceId, "
                . "folio, "
                . "afecto "
                . ") VALUES ("
                . ":id, "
                . ":total, "
                . ":emision, "
                . ":deviceId, "
                . ":folio, "
                . ":afecto "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "total = :total, "
                . "emision = :emision, "
                . "deviceId = :deviceId, "
                . "folio = :folio, "
                . "afecto = :afecto "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.total AS total, "
                . "a.emision AS emision, "
                . "a.deviceId AS deviceId, "
                . "a.folio AS folio, "
                . "a.afecto AS afecto "
                . "FROM boleta_dispositivo a "
                . "WHERE 1=1 AND id = :id";

    
}

?>