<?php

/*
 * TblCaja
 */

class TblCaja {

    public $idCaja;
    public $fechaCajaInicio;
    public $horaCajaInicio;
    public $fechaCajaFin;
    public $horaCajaFin;
    public $idUsuario;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'idCaja', 'bd_name' => 'id_caja', 'type' => 'int'),
                array('obj_name' => 'fechaCajaInicio', 'bd_name' => 'fecha_caja_inicio', 'type' => 'date'),
                array('obj_name' => 'horaCajaInicio', 'bd_name' => 'hora_caja_inicio', 'type' => 'time'),
                array('obj_name' => 'fechaCajaFin', 'bd_name' => 'fecha_caja_fin', 'type' => 'date'),
                array('obj_name' => 'horaCajaFin', 'bd_name' => 'hora_caja_fin', 'type' => 'time'),
                array('obj_name' => 'idUsuario', 'bd_name' => 'id_usuario', 'type' => 'int')
            ),
            'table' => 'tbl_caja'
        ];
    }

}

?>