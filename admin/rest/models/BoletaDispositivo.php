<?php

/*
 * BoletaDispositivo
 */

class BoletaDispositivo {

    public $id;
    public $total;
    public $emision;
    public $deviceId;
    public $folio;
    public $afecto;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'total', 'bd_name' => 'total', 'type' => 'int'),
                array('obj_name' => 'emision', 'bd_name' => 'emision', 'type' => 'datetime'),
                array('obj_name' => 'deviceId', 'bd_name' => 'deviceId', 'type' => 'varchar'),
                array('obj_name' => 'folio', 'bd_name' => 'folio', 'type' => 'int'),
                array('obj_name' => 'afecto', 'bd_name' => 'afecto', 'type' => 'tinyint')
            ),
            'table' => 'boleta_dispositivo'
        ];
    }

}

?>