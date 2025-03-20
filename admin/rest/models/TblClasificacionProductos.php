<?php

/*
 * TblClasificacionProductos
 */

class TblClasificacionProductos {

    public $id;
    public $nombre;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar')
            ),
            'table' => 'tbl_clasificacion_productos'
        ];
    }

}

?>