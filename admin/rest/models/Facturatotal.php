<?php

/*
 * Facturatotal
 */

class Facturatotal {

    public $id;
    public $numeroFactura;
    public $totalCantidad;
    public $totalPrecio;
    public $totalFactura;
    public $fechaFactura;
    public $totalDescuento;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'numeroFactura', 'bd_name' => 'numeroFactura', 'type' => 'varchar'),
                array('obj_name' => 'totalCantidad', 'bd_name' => 'totalCantidad', 'type' => 'int'),
                array('obj_name' => 'totalPrecio', 'bd_name' => 'totalPrecio', 'type' => 'varchar'),
                array('obj_name' => 'totalFactura', 'bd_name' => 'totalFactura', 'type' => 'varchar'),
                array('obj_name' => 'fechaFactura', 'bd_name' => 'fechaFactura', 'type' => 'varchar'),
                array('obj_name' => 'totalDescuento', 'bd_name' => 'totalDescuento', 'type' => 'varchar')
            ),
            'table' => 'facturatotal'
        ];
    }

}

?>