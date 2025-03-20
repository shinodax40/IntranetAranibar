<?php

/*
 * ClienteImpl
 */

class ClienteMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.nombre AS nombre, "
                . "a.celular AS celular, "
                . "a.correo AS correo "
                . "FROM tienda_cliente a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.nombre AS nombre, "
                . "a.celular AS celular, "
                . "a.correo AS correo, "
                . "a.activo AS activo "
                . "FROM tienda_cliente a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tienda_cliente ("
                . "id, "
                . "nombre, "
                . "celular, "
                . "correo "
                . "contrasena "
                . ") VALUES ("
                . ":id, "
                . ":nombre, "
                . ":celular, "
                . ":correo, "
                . ":contrasena "
                . ") ON DUPLICATE KEY UPDATE "
                . "nombre = :nombre, "
                . "celular = :celular, "
                . "correo = :correo, "
                . "contrasena = :contrasena ";

    const existsById = "SELECT "
                . "IF(COUNT(id) > 0, 1, 0) "
                . "FROM tienda_cliente a "
                . "WHERE 1=1 AND id = :id";

}

?>