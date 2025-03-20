<?php

/*
 * Orden
 */

class Orden {

    public $id;
    public $fecha;
    public $metodoPago;
    public $estado;
    public $nombre;
    public $celular;
    public $correo;
    public $calle;
    public $numero;
    public $idCliente;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int', 'auto' => true),
                array('obj_name' => 'fecha', 'bd_name' => 'fecha', 'type' => 'datetime'),
                array('obj_name' => 'metodoPago', 'bd_name' => 'metodo_pago', 'type' => 'enum'),
                array('obj_name' => 'estado', 'bd_name' => 'estado', 'type' => 'enum'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'celular', 'bd_name' => 'celular', 'type' => 'varchar'),
                array('obj_name' => 'correo', 'bd_name' => 'correo', 'type' => 'varchar'),
                array('obj_name' => 'calle', 'bd_name' => 'calle', 'type' => 'varchar'),
                array('obj_name' => 'numero', 'bd_name' => 'numero', 'type' => 'varchar'),
                array('obj_name' => 'idCliente', 'bd_name' => 'id_cliente', 'type' => 'int')
            ),
            'table' => 'orden'
        ];
    }

}

?>