<?php

/*
 * Productos
 */

class Productos {

    public $id;
    public $nombreProd;
    public $stock;
    public $precio;
    public $nombreTipo;
    public $nombreSubTipo;
    public $idCategorias;
    public $idSubCategorias;
    public $archivo;


    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'nombreProd', 'bd_name' => 'nombreProd', 'type' => 'varchar'),
                array('obj_name' => 'stock', 'bd_name' => 'stock', 'type' => 'int'),
                array('obj_name' => 'precio', 'bd_name' => 'precio', 'type' => 'int'),
                array('obj_name' => 'nombreTipo', 'bd_name' => 'nombreTipo', 'type' => 'varchar'),
                array('obj_name' => 'nombreSubTipo', 'bd_name' => 'nombreSubTipo', 'type' => 'varchar'),
                array('obj_name' => 'idCategorias', 'bd_name' => 'idCategorias', 'type' => 'int'),
                array('obj_name' => 'idSubCategorias', 'bd_name' => 'idSubCategorias', 'type' => 'int'),
                array('obj_name' => 'archivo', 'bd_name' => 'archivo', 'type' => 'varchar')
                
            ),
            'table' => 'productos'
        ];
    }

}

?>