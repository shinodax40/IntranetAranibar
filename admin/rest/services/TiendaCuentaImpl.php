<?php

/*
 * TiendaCuentaImpl
 */

class TiendaCuentaImpl {

    private $dbcon;

    function __construct() {
        $this->dbcon = new Database();
    }

    public function authenticate($data) {
        $conn = $this->dbcon->dbConnection();
        $tienda_cuenta = array();
        $get_stmt = $conn->prepare(TiendaCuentaMapper::authenticate);
        $get_stmt->bindParam(":correo", $data->correo);
        $get_stmt->bindParam(":contrasena", hash('sha256', $data->contrasena));
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $expira = new DateTime();
                $expira->modify('+1 day');
                $token = hash("sha256", md5($row->id . Time()));

                $this->updateToken($row->id, $token, $expira->format('Y-m-d H:i:s'));

                $tienda_cuenta = $this->getTiendaCuenta($row->id);
                $tienda_cuenta->token = $token;
                $tienda_cuenta->tokenExpira = $expira->format('Y-m-d H:i:s');
            }
        } else {
            $get_stmt = null;
            $conn = null;
            throw new AppException(AppCode::CREDENTIALS_ERROR);
        }
        $get_stmt = null;
        $conn = null;
        return $tienda_cuenta;
    }

    public function verificate($token) {
        $conn = $this->dbcon->dbConnection();
        $tienda_cuenta = array();
        $get_stmt = $conn->prepare(TiendaCuentaMapper::verificate);
        $get_stmt->bindParam(":token", $token);
        $get_stmt->execute();
        if ($get_stmt->rowCount() > 0) {
            if ($row = $get_stmt->fetchObject()) {
                $tienda_cuenta = $this->getTiendaCuenta($row->id);
                $tienda_cuenta->token = $row->token;
                $tienda_cuenta->tokenExpira = $row->token_expira;
            }
        } else {
            $get_stmt = null;
            $conn = null;
            throw new AppException(AppCode::CREDENTIALS_ERROR);
        }
        $get_stmt = null;
        $conn = null;
        return $tienda_cuenta;
    }

    public function getTiendaCuenta(int $id) {
        $tienda_cuenta = new TiendaCuenta();
        $tienda_cuenta->id = $id;
        $servicecliente = new ClienteImpl();
        $tienda_cuenta->cliente = $servicecliente->getCliente($id);
        $serviceDireccion = new DireccionImpl();
        $tienda_cuenta->direcciones = $serviceDireccion->getDirecciones(array('idCliente' => $id));
        $serviceOrden = new OrdenImpl();
        $tienda_cuenta->ordenes = $serviceOrden->getOrdenes(array('idCliente' => $id));
        return $tienda_cuenta;
    }

    public function updateToken(int $id, $token, $expira) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TiendaCuentaMapper::updateToken);
        $save_stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $save_stmt->bindParam(":token_expira", $expira, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }

    public function updatePass($pass) {
        $conn = $this->dbcon->dbConnection();
        $save_stmt = $conn->prepare(TiendaCuentaMapper::updatePass);
        $save_stmt->bindParam(":contrasena", $pass, PDO::PARAM_STR);
        $save_stmt->bindParam(":id", $data->id, PDO::PARAM_INT);
        $save_stmt->execute();
        $save_stmt = null;
        $conn = null;
        return "Se guardo correctamente";
    }

}

?>