<?php

/*
 * TiendaCuentaImpl
 */

class TiendaCuentaMapper {

    const authenticate = "SELECT "
            . "a.id AS id, "
            . "a.token AS token, "
            . "a.token_expira AS token_expira "
            . "FROM tienda_cliente a "
            . "WHERE 1=1 "
            . "AND activo = 1 "
            . "AND correo = :correo "
            . "AND contrasena = :contrasena";

    const verificate = "SELECT "
            . "a.id AS id, "
            . "a.token AS token, "
            . "a.token_expira AS token_expira "
            . "FROM tienda_cliente a "
            . "WHERE 1=1 "
            . "AND activo = 1 "
            . "AND token = :token";
    
    const updateToken = "UPDATE tienda_cliente SET "
                . "token = :token, "
                . "token_expira = :token_expira "
                . "WHERE 1=1 AND id = :id";
    
    const updatePass = "UPDATE tienda_cliente SET "
                . "contrasena = :contrasena "
                . "WHERE 1=1 AND id = :id";

}

?>