<?php

/*
 * Cliente
 */

class Cliente {

    public $id;
    public $nombre;
    public $celular;
    public $correo;
    public $activo;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int', 'auto' => true),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'celular', 'bd_name' => 'celular', 'type' => 'varchar'),
                array('obj_name' => 'correo', 'bd_name' => 'correo', 'type' => 'varchar'),
                array('obj_name' => 'activo', 'bd_name' => 'activo', 'type' => 'boolean')
            ),
            'table' => 'cliente'
        ];
    }

}

?>