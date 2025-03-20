<?php

/*
 * Direccion
 */

class Direccion {

    public $id;
    public $calle;
    public $numero;
    public $tipo;
    public $idCliente;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int', 'auto' => true),
                array('obj_name' => 'calle', 'bd_name' => 'calle', 'type' => 'varchar'),
                array('obj_name' => 'numero', 'bd_name' => 'numero', 'type' => 'varchar'),
                array('obj_name' => 'tipo', 'bd_name' => 'tipo', 'type' => 'varchar'),
                array('obj_name' => 'idCliente', 'bd_name' => 'id_cliente', 'type' => 'int')
            ),
            'table' => 'direccion'
        ];
    }

}

?>