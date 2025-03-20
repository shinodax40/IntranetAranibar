<?php

/*
 * TblAtencionCliente
 */

class TblAtencionCliente {

    public $id;
    public $tipoCliente;
    public $diasAtencion;
    public $diasAlarma;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'tipoCliente', 'bd_name' => 'tipo_cliente', 'type' => 'varchar'),
                array('obj_name' => 'diasAtencion', 'bd_name' => 'dias_atencion', 'type' => 'int'),
                array('obj_name' => 'diasAlarma', 'bd_name' => 'dias_alarma', 'type' => 'int')
            ),
            'table' => 'tbl_atencion_cliente'
        ];
    }

}

?>