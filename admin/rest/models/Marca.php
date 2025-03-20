<?php

/*
 * Marca
 */

class Marca {

    public $codMarca;
    public $codCategoria;
    public $nombreMarca;
    public $codProveedor;
    public $activo;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'codMarca', 'bd_name' => 'codMarca', 'type' => 'int'),
                array('obj_name' => 'codCategoria', 'bd_name' => 'codCategoria', 'type' => 'int'),
                array('obj_name' => 'nombreMarca', 'bd_name' => 'nombreMarca', 'type' => 'varchar'),
                array('obj_name' => 'codProveedor', 'bd_name' => 'cod_proveedor', 'type' => 'int'),
                array('obj_name' => 'activo', 'bd_name' => 'activo', 'type' => 'int')
            ),
            'table' => 'marca'
        ];
    }

}

?>