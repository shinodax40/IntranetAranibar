<?php

/*
 * Boletacabe
 */

class Boletacabe {

    public $id;
    public $numeroBoleta;
    public $nombre;
    public $fecha;
    public $estadoBol;
    public $idUsuario;

    public function azsListMappers() {
        return [
            'columns' => array(
                array('obj_name' => 'id', 'bd_name' => 'id', 'type' => 'int'),
                array('obj_name' => 'numeroBoleta', 'bd_name' => 'numeroBoleta', 'type' => 'varchar'),
                array('obj_name' => 'nombre', 'bd_name' => 'nombre', 'type' => 'varchar'),
                array('obj_name' => 'fecha', 'bd_name' => 'fecha', 'type' => 'varchar'),
                array('obj_name' => 'estadoBol', 'bd_name' => 'estadoBol', 'type' => 'varchar'),
                array('obj_name' => 'idUsuario', 'bd_name' => 'id_usuario', 'type' => 'int')
            ),
            'table' => 'boletacabe'
        ];
    }

}

?>