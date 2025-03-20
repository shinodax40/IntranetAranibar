<?php
require_once('Bean/Talonario.php');
require_once('Bean/Cliente.php');

class bdTalonario{
	
	private $DATABASE_SERVER;
	private $DATABASE_USERNAME;
	private $DATABASE_PASSWORD;
	private $DATABASE_NAME;
	
		
function __construct($host,$username,$password,$db_name){
		$this->DATABASE_SERVER=$host;
		$this->DATABASE_USERNAME=$username;
		$this->DATABASE_PASSWORD=$password;
		$this->DATABASE_NAME=$db_name;
}
	
    
public function listarTalonarios($id_talonario, $desde, $hasta, $id_estado, $numDoc) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
	    $ands="";
    
		if($desde != "" && $hasta != "" ){
			$fechaFilt = "AND t.fecha_cobro  BETWEEN ('".$desde."')  and ('".$hasta."');";      
		}
	
	  	if($id_talonario != ""){
			$estadoTalonario = "AND tr.id = '".$id_talonario."'";      
			$ands =" AND ";
	  	}	
	


		if($id_estado != ""){
			$estadoTal =  /*$ands.*/ "AND t.id_estado = '".$id_estado."'";      
		}
	

	
   
		$query =     
            "SELECT    t.id,
                       t.id_talonario,
                       t.numero_doc,
                       t.id_estado,
                       et.nombre,
                       t.monto,
                       t.fecha_cobro,
                       t.orden_de,
                       t.descripcion,
                       (select GROUP_CONCAT(o.num_factura SEPARATOR ',')
                        from tbl_factura_ingresos o
                        where o.id_talonario = t.id) as facturas
                FROM tbl_talonario t 
                LEFT OUTER JOIN tbl_talonario_reg tr 
                ON tr.id =  t.id_talonario
                LEFT OUTER JOIN tbl_estado_talonario et 
                ON et.id = t.id_estado
                where 
				 t.numero_doc like '%".$numDoc."%'
				
				 "
			          .
			         $estadoTalonario
			          .
			
			     "
				
				
				
			   	 "
			     .
			        $estadoTal
			     .
			
			     "
						
                " .
                   $fechaFilt
                  .
                "
            
                ";                          
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Talonario();
			$tmp->id                   = $row->id;			
			$tmp->id_talonario         = $row->id_talonario;
			$tmp->numero_doc           = $row->numero_doc;
			$tmp->id_estado            = $row->id_estado;
			$tmp->nombre               = $row->nombre;
			$tmp->monto                = $row->monto;            
			$tmp->fecha_cobro          = $row->fecha_cobro;
			$tmp->orden_de             = $row->orden_de;
			$tmp->descripcion          = $row->descripcion;
			$tmp->facturas             = $row->facturas;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}      
       
    
public function listarTalonariosFact($id_talonario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =     
            "SELECT i.num_factura,
                     p.id_proveedor
              FROM tbl_factura_ingresos i 
              LEFT OUTER JOIN tbl_ingresos p 
              ON p.id = i.id_ingresos
              WHERE i.id_talonario='".$id_talonario."';";                            
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Talonario();
			$tmp->num_factura          = $row->num_factura;			
			$tmp->id_proveedor         = $row->id_proveedor;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}         
    
    
    
    
public function consultarFactura($id_talonario, $id_prove) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =     "select d.id_ingresos,
                               d.num_factura
                        from tbl_factura_ingresos d 
                        INNER JOIN tbl_ingresos t 
                        ON t.id = d.id_ingresos
                        LEFT OUTER JOIN tbl_talonario o 
                        ON o.id = d.id_talonario
                        where d.num_factura =  '".$id_talonario."'
                        and t.id_proveedor='".$id_prove."'
                        and o.id_estado = 3;";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
	/*	$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Talonario();
			$tmp->id_ingresos      = $row->id_ingresos;			
			$tmp->num_factura      = $row->num_factura;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);*/
		      return ($total);
}   
    

public function consulFact($id_talonario, $id_prove) {
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");



         $activo = mysqli_num_rows(mysqli_query($mysql, "select d.id_ingresos,
                                                       d.num_factura
                                                from tbl_factura_ingresos d 
                                                LEFT OUTER JOIN tbl_ingresos t 
                                                ON t.id = d.id_ingresos
                                                where d.num_factura = '".$id_talonario."'
                                                and t.id_proveedor='".$id_prove."'"));  

        if($activo == 1) {
                return 1; 
        }else{
            return 0;
        }    
}
    
    
    
    
	 public function guardarTalonario($objFactCabe, $objFactDet, $objFactDetAux) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
         mysqli_query($mysql, "SET NAMES 'utf8'");
        
         
        $query ="update tbl_talonario o 
                        set o.monto='".$objFactCabe[0]->monto."',
                            o.fecha_cobro='".$objFactCabe[0]->fcobro."', 
                            o.orden_de='".$objFactCabe[0]->ordenDe."',
                            o.descripcion='".$objFactCabe[0]->descripcion."',
                            o.id_estado='".$objFactCabe[0]->estado."'
                        where o.id='".$objFactCabe[0]->idTalonario."';"; 
		$result = mysqli_query($mysql, $query);
         
        
           foreach($objFactDetAux as $detalleAux){
                    
                              
                     $query="update tbl_factura_ingresos o 
                        LEFT OUTER JOIN tbl_ingresos p 
                        ON p.id = o.id_ingresos
                        set o.id_talonario=0
                        where o.num_factura='".$detalleAux->numFact."'
                        and p.id_proveedor='".$detalleAux->idProv."'
                        ;";
              
    
                    $resultDeta = mysqli_query($mysql, $query);	
               
               
                   $query2="UPDATE tbl_ingresos p 
                            LEFT OUTER JOIN tbl_factura_ingresos f 
                            ON f.id_ingresos = p.id
                            set p.estado_cobranza='4' , 
                                p.fecha_cobro = ''
                            where p.id_proveedor='".$detalleAux->idProv."'
                            and f.num_factura='".$detalleAux->numFact."'
                            ";
                  $resultDeta = mysqli_query($mysql, $query2);	
               
          }
         
         
         
          foreach($objFactDet as $detalle){
                    
                              
                     $query="update tbl_factura_ingresos o 
                        LEFT OUTER JOIN tbl_ingresos p 
                        ON p.id = o.id_ingresos
                        set o.id_talonario='".$detalle->idTalo."'
                        where o.num_factura='".$detalle->numFact."'
                        and p.id_proveedor='".$detalle->idProv."'
                        ;";
              
    
                    $resultDeta = mysqli_query($mysql, $query);	
              
                   $query2="UPDATE tbl_ingresos p 
                            LEFT OUTER JOIN tbl_factura_ingresos f 
                            ON f.id_ingresos = p.id
                            set p.estado_cobranza='2' , 
                                p.fecha_cobro = '".$objFactCabe[0]->fcobro."'
                            where p.id_proveedor='".$detalle->idProv."'
                            and f.num_factura='".$detalle->numFact."'
                            ";
              $resultDeta = mysqli_query($mysql, $query2);	
              
          }
     }
    
    
    
    public function registroTalonario() {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =     
            "SELECT t.id,
                    t.desde,
                    t.hasta
              FROM tbl_talonario_reg t;";                            
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Talonario();
			$tmp->id              = $row->id;			
			$tmp->name            = $row->desde . " al " . $row->hasta;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}
    
    public function listarPedClientesCobro($idCliente, $idTipoBusqueda, $desde, $hasta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
        $busqTipo = "";
        if($idTipoBusqueda == "2"){
			$busqTipo = "
                p.estado_cobranza in (1,2,3,5,6)
                AND p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."') ";      
		}else if($idTipoBusqueda == "1"){
            $busqTipo = "
                p.estado_cobranza =4"; 
        }
        
		$query =  "SELECT       p.id_pedido as id,
                                f.folio,
                                p.fecha_cobro,
                                p.fecha_entrega,
                                DATEDIFF( NOW(), p.fecha_entrega) as difDia,
                                SUM(d.cantidad * d.precio_vendido) as totalPedido,
                                c.credito,
                                p.estado_pedido as estadoPedido,
                                p.anulada,
                                p.observacion,
                                aa.countRecibo as recibos,
                                aa.n_recibo as idRecibos,
                                aa.fecha_cobro as fecha_recibo,
                                p.obser_transp,
                                p.estado_cobranza,
                                aa.id as idRecibosApli
                                
                        FROM tbl_pedido p
                                LEFT OUTER JOIN tbl_deta_pedido d 
                                ON d.id_pedido = p.id_pedido
                                LEFT OUTER JOIN tbl_cliente c 
                                ON c.id_cliente = p.id_cliente
                                left outer join tbl_nubox_facturas f 
                                on f.id_pedido  = p.id_pedido
                                left OUTER join tbl_estado_pedido e 
                                ON e.id_estado = p.estado_pedido
                                LEFT OUTER JOIN (SELECT COUNT(*) as countRecibo,
                                                 dd.n_pedido,
                                                  cc.n_recibo,
                                                  cc.fecha_cobro,
                                                  cc.id
                                FROM tbl_recibo_deta dd
                                LEFT OUTER JOIN tbl_recibo_cabe cc 
                                ON cc.id = dd.id_recibo_bol
                                GROUP by dd.n_pedido)  aa
                          ON aa.n_pedido = p.id_pedido  
                                WHERE ".
                                        $busqTipo
                                       .
                                      "
                                and p.id_cliente  = '".$idCliente."'
                                and p.anulada='N'
                                GROUP by p.id_pedido,
                                         f.folio,
                                         p.fecha_cobro,
                                         p.fecha_entrega,
                                         c.credito,
                                         p.anulada,
                                         p.observacion,
                                         p.obser_transp,
                                         aa.countRecibo,
                                         aa.id
                                ORDER BY p.id_pedido;";                            
		$result = mysqli_query($mysql, $query);
		//$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id                   = $row->id;			
			$tmp->fecha_cobro          = $row->fecha_cobro;
			$tmp->fecha_entrega        = $row->fecha_entrega;
            $tmp->difDia               = $row->credito - $row->difDia ;
 			$tmp->totalPedido          = round ((($row->totalPedido) * 0.19)+($row->totalPedido));	
 			$tmp->folio                = $row->folio;            
           	$tmp->credito              = $row->credito;
           	$tmp->estadoPedido         = $row->estadoPedido;
           	$tmp->anulada             = $row->anulada;
            $tmp->observacion         = $row->observacion;
           	$tmp->obser_transp        = $row->obser_transp;
           	$tmp->recibos             = $row->recibos; 
           	$tmp->idRecibos           = $row->idRecibos; 
          	$tmp->fecha_recibo        = $row->fecha_recibo; 
           	$tmp->estado_cobranza     = $row->estado_cobranza; 
           	$tmp->idRecibosApli       = $row->idRecibosApli; 

            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}      
 
    
    
    
    
}
?>