<?php

/*
 * TblCanjear
 */

class TblCanjear {

    public $id;
    public $idProd;
    public $fechaIngreso;
    public $cantidad;
    public $idPedido;
    public $devid;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'idProd', 'bd_name' => 'id_prod', 'type' => 'int'),
                array('obj_name' => 'fechaIngreso', 'bd_name' => 'fecha_ingreso', 'type' => 'date'),
                array('obj_name' => 'cantidad', 'bd_name' => 'cantidad', 'type' => 'int'),
                array('obj_name' => 'idPedido', 'bd_name' => 'id_pedido', 'type' => 'int'),
                array('obj_name' => 'devid', 'bd_name' => 'devid', 'type' => 'varchar')
            ),
            'table' => 'tbl_canjear'
        ];
    }

}

?>