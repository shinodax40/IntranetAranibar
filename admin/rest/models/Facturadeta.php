<?php

/*
 * Facturadeta
 */

class Facturadeta {

    public $id;
    public $numeroFactura;
    public $codProducto;
    public $tipo;
    public $marca;
    public $nombreProd;
    public $detalle;
    public $precioCosto;
    public $stock;
    public $cantidad;
    public $precio;
    public $total;
    public $descuento;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'numeroFactura', 'bd_name' => 'numeroFactura', 'type' => 'varchar'),
                array('obj_name' => 'codProducto', 'bd_name' => 'codProducto', 'type' => 'varchar'),
                array('obj_name' => 'tipo', 'bd_name' => 'tipo', 'type' => 'varchar'),
                array('obj_name' => 'marca', 'bd_name' => 'marca', 'type' => 'varchar'),
                array('obj_name' => 'nombreProd', 'bd_name' => 'nombreProd', 'type' => 'varchar'),
                array('obj_name' => 'detalle', 'bd_name' => 'detalle', 'type' => 'varchar'),
                array('obj_name' => 'precioCosto', 'bd_name' => 'precioCosto', 'type' => 'varchar'),
                array('obj_name' => 'stock', 'bd_name' => 'stock', 'type' => 'int'),
                array('obj_name' => 'cantidad', 'bd_name' => 'cantidad', 'type' => 'int'),
                array('obj_name' => 'precio', 'bd_name' => 'precio', 'type' => 'varchar'),
                array('obj_name' => 'total', 'bd_name' => 'total', 'type' => 'varchar'),
                array('obj_name' => 'descuento', 'bd_name' => 'descuento', 'type' => 'varchar')
            ),
            'table' => 'facturadeta'
        ];
    }

}

?>