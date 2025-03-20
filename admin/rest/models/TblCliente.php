<?php

/*
 * TblCliente
 */

class TblCliente {

    public $idCliente;
    public $rut;
    public $nombre;
    public $direccion;
    public $correo;
    public $telefono;
    public $giro;
    public $comuna;
    public $tipoComprador;
    public $idUsuario;
    public $observacion;
    public $banderaNew;
    public $fechaIngreso;
    public $activo;
    public $idObservacion;
    public $fechaObsMod;
    public $credito;
    public $idSector;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'idCliente', 'bd_name' => 'id_cliente', 'type' => 'int'),
                array('obj_name' => 'rut', 'bd_name' => 'rut', 'type' => 'varchar'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'direccion', 'bd_name' => 'direccion', 'type' => 'varchar'),
                array('obj_name' => 'correo', 'bd_name' => 'correo', 'type' => 'varchar'),
                array('obj_name' => 'telefono', 'bd_name' => 'telefono', 'type' => 'varchar'),
                array('obj_name' => 'giro', 'bd_name' => 'giro', 'type' => 'varchar'),
                array('obj_name' => 'comuna', 'bd_name' => 'comuna', 'type' => 'varchar'),
                array('obj_name' => 'tipoComprador', 'bd_name' => 'tipo_comprador', 'type' => 'varchar'),
                array('obj_name' => 'idUsuario', 'bd_name' => 'id_usuario', 'type' => 'int'),
                array('obj_name' => 'observacion', 'bd_name' => 'observacion', 'type' => 'varchar'),
                array('obj_name' => 'banderaNew', 'bd_name' => 'bandera_new', 'type' => 'int'),
                array('obj_name' => 'fechaIngreso', 'bd_name' => 'fecha_ingreso', 'type' => 'date'),
                array('obj_name' => 'activo', 'bd_name' => 'activo', 'type' => 'int'),
                array('obj_name' => 'idObservacion', 'bd_name' => 'id_observacion', 'type' => 'int'),
                array('obj_name' => 'fechaObsMod', 'bd_name' => 'fecha_obs_mod', 'type' => 'date'),
                array('obj_name' => 'credito', 'bd_name' => 'credito', 'type' => 'int'),
                array('obj_name' => 'idSector', 'bd_name' => 'id_sector', 'type' => 'int')
            ),
            'table' => 'tbl_cliente'
        ];
    }

}

?>