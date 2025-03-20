<?php

/*
 * TblConcursoImpl
 */

class TblConcursoMapper {

    const findAll = "SELECT "
                . "a.id AS id, "
                . "a.nombres AS nombres, "
                . "a.contacto AS contacto, "
                . "a.email AS email, "
                . "a.alimento AS alimento, "
                . "a.foto AS foto, "
                . "a.calendario AS calendario "
                . "FROM tbl_concurso a "
                . "WHERE 1=1";
    
    const findById = "SELECT "
                . "a.id AS id, "
                . "a.nombres AS nombres, "
                . "a.contacto AS contacto, "
                . "a.email AS email, "
                . "a.alimento AS alimento, "
                . "a.foto AS foto, "
                . "a.calendario AS calendario "
                . "FROM tbl_concurso a "
                . "WHERE 1=1 AND id = :id";

    const save = "INSERT INTO tbl_concurso ("
                . "id, "
                . "nombres, "
                . "contacto, "
                . "email, "
                . "alimento, "
                . "foto, "
                . "calendario "
                . ") VALUES ("
                . ":id, "
                . ":nombres, "
                . ":contacto, "
                . ":email, "
                . ":alimento, "
                . ":foto, "
                . ":calendario "
                . ") ON DUPLICATE KEY UPDATE "
                . "id = :id, "
                . "nombres = :nombres, "
                . "contacto = :contacto, "
                . "email = :email, "
                . "alimento = :alimento, "
                . "foto = :foto, "
                . "calendario = :calendario "
                . "";

    const existsById = "SELECT "
                . "a.id AS id, "
                . "a.nombres AS nombres, "
                . "a.contacto AS contacto, "
                . "a.email AS email, "
                . "a.alimento AS alimento, "
                . "a.foto AS foto, "
                . "a.calendario AS calendario "
                . "FROM tbl_concurso a "
                . "WHERE 1=1 AND id = :id";

    
}

?>