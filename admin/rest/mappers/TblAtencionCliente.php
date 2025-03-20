<?php

/*
 * TblAtencionClienteImpl
 */

class TblAtencionClienteMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.tipo_cliente AS tipoCliente, "
                . "a.dias_atencion AS diasAtencion, "
                . "a.dias_alarma AS diasAlarma "
                . "FROM tbl_atencion_cliente a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.tipo_cliente AS tipoCliente, "
                . "a.dias_atencion AS diasAtencion, "
                . "a.dias_alarma AS diasAlarma "
                . "FROM tbl_atencion_cliente a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_atencion_cliente ("
                . "id, "
                . "tipo_cliente, "
                . "dias_atencion, "
                . "dias_alarma "
                . ") VALUES ("
                . ":id, "
                . ":tipoCliente, "
                . ":diasAtencion, "
                . ":diasAlarma "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "tipo_cliente = :tipoCliente, "
                . "dias_atencion = :diasAtencion, "
                . "dias_alarma = :diasAlarma "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.tipo_cliente AS tipoCliente, "
                . "a.dias_atencion AS diasAtencion, "
                . "a.dias_alarma AS diasAlarma "
                . "FROM tbl_atencion_cliente a "
                . "WHERE 1=1 AND id = :id";

    
}

?>