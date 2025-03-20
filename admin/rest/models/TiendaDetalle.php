<?php

/*
 * Detalle
 */

class Detalle {

    public $id;
    public $idOrden;
    public $idProducto;
    public $precio;
    public $cantidad;
    public $descuento;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'idOrden', 'bd_name' => 'id_orden', 'type' => 'int'),
                array('obj_name' => 'idProducto', 'bd_name' => 'id_producto', 'type' => 'int'),
                array('obj_name' => 'precio', 'bd_name' => 'precio', 'type' => 'int'),
                array('obj_name' => 'cantidad', 'bd_name' => 'cantidad', 'type' => 'int'),
                array('obj_name' => 'descuento', 'bd_name' => 'descuento', 'type' => 'int')
            ),
            'table' => 'detalle'
        ];
    }

}

?>