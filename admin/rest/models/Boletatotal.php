<?php

/*
 * Boletatotal
 */

class Boletatotal {

    public $id;
    public $numeroBoleta;
    public $totalCantidad;
    public $totalPrecio;
    public $totalBoleta;
    public $fechaBoleta;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'numeroBoleta', 'bd_name' => 'numeroBoleta', 'type' => 'varchar'),
                array('obj_name' => 'totalCantidad', 'bd_name' => 'totalCantidad', 'type' => 'int'),
                array('obj_name' => 'totalPrecio', 'bd_name' => 'totalPrecio', 'type' => 'varchar'),
                array('obj_name' => 'totalBoleta', 'bd_name' => 'totalBoleta', 'type' => 'varchar'),
                array('obj_name' => 'fechaBoleta', 'bd_name' => 'fechaBoleta', 'type' => 'varchar')
            ),
            'table' => 'boletatotal'
        ];
    }

}

?>