<?php

/*
 * Categoria
 */

class Categoria {

    public $id;
    public $nombre;
    public $archivo;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'archivo', 'bd_name' => 'archivo', 'type' => 'varchar')
            ),
            'table' => 'tbl_tipo_pagina'
        ];
    }

}

?>