<?php

/*
 * TblCodigoBarraExtra
 */

class TblCodigoBarraExtra {

    public $id;
    public $idProd;
    public $codigoBarra;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'idProd', 'bd_name' => 'id_prod', 'type' => 'int'),
                array('obj_name' => 'codigoBarra', 'bd_name' => 'codigo_barra', 'type' => 'varchar')
            ),
            'table' => 'tbl_codigo_barra_extra'
        ];
    }

}

?>