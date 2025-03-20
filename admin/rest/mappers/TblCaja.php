<?php

/*
 * TblCajaImpl
 */

class TblCajaMapper {

    const findAll = "SELECT "
                . "a.id_caja AS idCaja, "
                . "a.fecha_caja_inicio AS fechaCajaInicio, "
                . "a.hora_caja_inicio AS horaCajaInicio, "
                . "a.fecha_caja_fin AS fechaCajaFin, "
                . "a.hora_caja_fin AS horaCajaFin, "
                . "a.id_usuario AS idUsuario "
                . "FROM tbl_caja a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id_caja AS idCaja, "
                . "a.fecha_caja_inicio AS fechaCajaInicio, "
                . "a.hora_caja_inicio AS horaCajaInicio, "
                . "a.fecha_caja_fin AS fechaCajaFin, "
                . "a.hora_caja_fin AS horaCajaFin, "
                . "a.id_usuario AS idUsuario "
                . "FROM tbl_caja a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_caja ("
                . "id_caja, "
                . "fecha_caja_inicio, "
                . "hora_caja_inicio, "
                . "fecha_caja_fin, "
                . "hora_caja_fin, "
                . "id_usuario "
                . ") VALUES ("
                . ":idCaja, "
                . ":fechaCajaInicio, "
                . ":horaCajaInicio, "
                . ":fechaCajaFin, "
                . ":horaCajaFin, "
                . ":idUsuario "
                . ") ON DUPLICATE KEY UPDATE "
                . "id_caja = :idCaja, "
                . "fecha_caja_inicio = :fechaCajaInicio, "
                . "hora_caja_inicio = :horaCajaInicio, "
                . "fecha_caja_fin = :fechaCajaFin, "
                . "hora_caja_fin = :horaCajaFin, "
                . "id_usuario = :idUsuario "
                . "";

    const existsById = "SELECT "
                . "a.id_caja AS idCaja, "
                . "a.fecha_caja_inicio AS fechaCajaInicio, "
                . "a.hora_caja_inicio AS horaCajaInicio, "
                . "a.fecha_caja_fin AS fechaCajaFin, "
                . "a.hora_caja_fin AS horaCajaFin, "
                . "a.id_usuario AS idUsuario "
                . "FROM tbl_caja a "
                . "WHERE 1=1 AND id = :id";

    
}

?>