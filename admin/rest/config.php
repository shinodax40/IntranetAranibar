<?php

date_default_timezone_set('America/Santiago');

require_once ('services/BoletacabeImpl.php');
require_once ('services/BoletadetaImpl.php');
require_once ('services/BoletatotalImpl.php');
require_once ('services/BoletaDispositivoImpl.php');
require_once ('services/CategoriaImpl.php');
require_once ('services/FacturacabeImpl.php');
require_once ('services/FacturadetaImpl.php');
require_once ('services/FacturatotalImpl.php');
require_once ('services/MarcaImpl.php');
require_once ('services/ProductosImpl.php');
require_once ('services/TblAtencionClienteImpl.php');
require_once ('services/TblCajaImpl.php');
require_once ('services/TblCanjearImpl.php');
require_once ('services/TblClasificacionProductosImpl.php');
require_once ('services/TblClienteImpl.php');
require_once ('services/TblCodigoBarraExtraImpl.php');
require_once ('services/TblConcursoImpl.php');
require_once ('services/SubCategoriaImpl.php');
require_once ('services/TiendaCuentaImpl.php');
require_once ('services/TiendaClienteImpl.php');
require_once ('services/TiendaDetalleImpl.php');
require_once ('services/TiendaDireccionImpl.php');
require_once ('services/TiendaOrdenImpl.php');

require_once ('models/Boletacabe.php');
require_once ('models/Boletadeta.php');
require_once ('models/Boletatotal.php');
require_once ('models/BoletaDispositivo.php');
require_once ('models/Categoria.php');
require_once ('models/Facturacabe.php');
require_once ('models/Facturadeta.php');
require_once ('models/Facturatotal.php');
require_once ('models/Marca.php');
require_once ('models/Productos.php');
require_once ('models/TblAtencionCliente.php');
require_once ('models/TblCaja.php');
require_once ('models/TblCanjear.php');
require_once ('models/TblClasificacionProductos.php');
require_once ('models/TblCliente.php');
require_once ('models/TblCodigoBarraExtra.php');
require_once ('models/TblConcurso.php');
require_once ('models/TiendaCuenta.php');
require_once ('models/TiendaCliente.php');
require_once ('models/TiendaDetalle.php');
require_once ('models/TiendaDireccion.php');
require_once ('models/TiendaOrden.php');

require_once ('mappers/Boletacabe.php');
require_once ('mappers/Boletadeta.php');
require_once ('mappers/Boletatotal.php');
require_once ('mappers/BoletaDispositivo.php');
require_once ('mappers/Categoria.php');
require_once ('mappers/Facturacabe.php');
require_once ('mappers/Facturadeta.php');
require_once ('mappers/Facturatotal.php');
require_once ('mappers/Marca.php');
require_once ('mappers/Productos.php');
require_once ('mappers/TblAtencionCliente.php');
require_once ('mappers/TblCaja.php');
require_once ('mappers/TblCanjear.php');
require_once ('mappers/TblClasificacionProductos.php');
require_once ('mappers/TblCliente.php');
require_once ('mappers/TblCodigoBarraExtra.php');
require_once ('mappers/TblConcurso.php');
require_once ('mappers/SubCategoria.php');
require_once ('mappers/TiendaCuenta.php');
require_once ('mappers/TiendaCliente.php');
require_once ('mappers/TiendaDetalle.php');
require_once ('mappers/TiendaDireccion.php');
require_once ('mappers/TiendaOrden.php');

// Mapeo
define("MAPPING_BOLETACABE", "boletacabe");
define("MAPPING_BOLETADETA", "boletadeta");
define("MAPPING_BOLETATOTAL", "boletatotal");
define("MAPPING_BOLETADISPOSITIVO", "boleta-dispositivo");
define("MAPPING_CATEGORIA", "categorias");
define("MAPPING_FACTURACABE", "facturacabe");
define("MAPPING_FACTURADETA", "facturadeta");
define("MAPPING_FACTURATOTAL", "facturatotal");
define("MAPPING_MARCA", "marca");
define("MAPPING_PRODUCTOS", "productos");
define("MAPPING_TBLATENCIONCLIENTE", "tbl-atencion-cliente");
define("MAPPING_TBLCAJA", "tbl-caja");
define("MAPPING_TBLCANJEAR", "tbl-canjear");
define("MAPPING_TBLCLASIFICACIONPRODUCTOS", "tbl-clasificacion-productos");
define("MAPPING_TBLCLIENTE", "tbl-cliente");
define("MAPPING_TBLCODIGOBARRAEXTRA", "tbl-codigo-barra-extra");
define("MAPPING_TBLCONCURSO", "tbl-concurso");

define("MAPPING_SUBCATEGORIA", "subcategorias");

// Mapeo para tienda
define("MAPPING_LOGIN", "autenticar");
define("MAPPING_TDACUENTA", "cuentas");
define("MAPPING_TDACLIENTE", "clientes");
define("MAPPING_TDADETALLE", "detalles");
define("MAPPING_TDADIRECCION", "direcciones");
define("MAPPING_TDAORDEN", "ordenes");

// Parametros
define("METODO_GET", 'GET');
define("METODO_POST", 'POST');
define("METODO_PUT", 'PUT');
define("METODO_PATCH", 'PATCH');
define("METODO_DELETE", 'DELETE');

/*
 * AppCode
 */

class AppCode {

    const OK = array('code' => 0, 'message' => 'OK');
    const UNKNOWN_ERROR = array('code' => 1, 'message' => 'Error desconocido');
    const DATA_VALIDATION_ERROR = array('code' => 2, 'message' => 'Validación de datos');
    const PARSE_JSON_ERROR = array('code' => 3, 'message' => 'Error al leer JSON mal formado');
    const CREDENTIALS_ERROR = array('code' => 4, 'message' => 'Credenciales incorrectas');
    const DATABASE_ERROR = array('code' => 5, 'message' => 'Error conexion a la base de datos');
    const COMPONENT_ERROR = array('code' => 6, 'message' => 'Error en la definicion de parametros');
    const SERVICE_NOT_AVAILABLE = array('code' => 7, 'message' => 'Error servicio no disponible');

}

/*
 * AppException
 */

class AppException extends Exception {

    public function __construct(array $tipo = null) {
        if (isset($tipo) && isset($tipo['code']) && isset($tipo['message'])) {
            parent::__construct($tipo['message'], $tipo['code']);
        } else {
            parent::__construct("Error");
        }
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

/*
 * AppResponse
 */

class AppResponse {

    public $code;
    public $data;
    public $description;

    public function __construct($code = null, $data = null, $description = null) {
        $this->code = $code;
        $this->data = $data;
        $this->description = $description;
    }

    public function imprimir() {
        echo json_encode($this, JSON_NUMERIC_CHECK, JSON_INVALID_UTF8_IGNORE);
    }

}

/*
 * AzsConverter
 */

class AzsConverter {

    public static function toObject(object $obj, object $object) {
        $vars = get_object_vars($obj);
        foreach ($vars as $key => $val) {
            if (is_array($val) || is_object($val)) {
                
            } elseif (property_exists($object, $key)) {
                $object->$key = utf8_encode($val);
            }
        }
        return $object;
    }

    public static function toObjectPrefix(string $prefix = 'a', object $obj, object $object) {
        $vars = get_object_vars($obj);
        $auxClass = new stdClass();
        foreach ($vars as $key => $val) {
            $prefix_replace = $prefix . '_';
            $keyObj = substr($key, strlen($prefix_replace));
            if (property_exists($object, $keyObj)) {
                $auxClass->$keyObj = $val;
            }
        }
        AzsConverter::toObject($auxClass, $object);
        return $object;
    }

}

/*
 * Database
 */

class Database {
    /*
      $dataBaseServer="aranibar.cl";
      $dataBaseUsername="aranibar";
      $dataBaseUserPassword="zi2Rs6L[#Vl82M";
      $dataBaseName="aranibar_aranibar"; */

    private $db_host = 'intranet.aranibar.cl';
    private $db_username = 'aranibar';
    private $db_password = 'zi2Rs6L[#Vl82M';
    private $db_name = 'aranibar_aranibar';

    public function dbConnection() {
        try {
            $conn = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_username, $this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            throw new AppException(AppCode::DATABASE_ERROR);
        }
    }

}

/*
 * 
 */

class ApiRest {

    public function getMetodo() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParamatro() {
        $result = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $result);
        return $result;
    }

    public function getDatos() {
        return json_decode(file_get_contents("php://input"));
    }

    public function getMapeo() {
        $result = [];
        if (isset($_REQUEST['mapping'])) {
            $result = explode('/', trim($_REQUEST['mapping']));
        }
        return $result;
    }

    function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

}

?>