<?php

/*
 * Facturacabe
 */

class Facturacabe {

    public $id;
    public $numeroFactura;
    public $nombre;
    public $fecha;
    public $estadoFac;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'numeroFactura', 'bd_name' => 'numeroFactura', 'type' => 'varchar'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'fecha', 'bd_name' => 'fecha', 'type' => 'varchar'),
                array('obj_name' => 'estadoFac', 'bd_name' => 'estadoFac', 'type' => 'varchar')
            ),
            'table' => 'facturacabe'
        ];
    }

}

?>