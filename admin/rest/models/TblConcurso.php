<?php

/*
 * TblConcurso
 */

class TblConcurso {

    public $id;
    public $nombres;
    public $contacto;
    public $email;
    public $alimento;
    public $foto;
    public $calendario;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'nombres', 'bd_name' => 'nombres', 'type' => 'varchar'),
                array('obj_name' => 'contacto', 'bd_name' => 'contacto', 'type' => 'varchar'),
                array('obj_name' => 'email', 'bd_name' => 'email', 'type' => 'varchar'),
                array('obj_name' => 'alimento', 'bd_name' => 'alimento', 'type' => 'varchar'),
                array('obj_name' => 'foto', 'bd_name' => 'foto', 'type' => 'longtext'),
                array('obj_name' => 'calendario', 'bd_name' => 'calendario', 'type' => 'int')
            ),
            'table' => 'tbl_concurso'
        ];
    }

}

?>