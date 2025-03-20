<?php
require_once('Bean/Factura.php');
require_once('Bean/Pedido.php');
class bdGrafico{
	
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
	
     

    
    
public function sumaTotalPorMes(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
            SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 
                AND   pe.id_usuario  NOT IN (3) 
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386) 
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}
    
    
    
    
public function sumaTotalPorMesFrancisca(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 131
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}
        

    
public function sumaTotalPorMesBlanca(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 48
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}
    

    
public function sumaTotalPorMesJuan(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 1
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}
	
	
public function sumaTotalPorMesDiego(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 5
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}	
	

	
public function sumaTotalPorMesAndres(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 207
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}   
    
public function sumaTotalPorMesJorge(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario = 204
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}       
    
    
    
    /*
public function sumaTotalPorMesNico(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
                SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
                AND   pe.id_usuario = 4
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275)
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
}*/
    
    
public function sumaTotalPorMesUsuario($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
              SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 1 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes1,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 2 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes2,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 3 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes3,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 4 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes4,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 5 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes5,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 6 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes6,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 7 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes7,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 8 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes8,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 9 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes9, 
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 10 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes10,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 11 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes11,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 12 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes12
                FROM tbl_pedido pe 
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 				
                AND   pe.id_usuario =".$idUsu."
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386) 
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW());
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$tmp->mes1          = $row->mes1;
			$tmp->mes2          = $row->mes2;
			$tmp->mes3          = $row->mes3;
			$tmp->mes4          = $row->mes4;
			$tmp->mes5          = $row->mes5;
			$tmp->mes6          = $row->mes6;
			$tmp->mes7          = $row->mes7;
			$tmp->mes8          = $row->mes8;
			$tmp->mes9          = $row->mes9;
			$tmp->mes10          = $row->mes10;
			$tmp->mes11          = $row->mes11;
			$tmp->mes12          = $row->mes12;
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
      mysqli_close($mysql);
}    
            
    

public function sumaTotalPorSemanasRango($idUsu, $desde, $hasta){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        $queryMax  = "SELECT MAX(p.id), p.fecha_desde, p.fecha_hasta FROM tbl_fecha_semanas p;";
        $resultMax = mysqli_query($mysql, $queryMax);
        $retMax = array();
		while ($row = mysqli_fetch_object($resultMax)) {
			$tmp =new Pedido();
			$tmp->desde          = $row->fecha_desde;
			$tmp->hasta          = $row->fecha_hasta;
            
			$retMax[] = $tmp;
		}
            
            
        $query = "        
                SELECT 
                (ELT(WEEKDAY(pe.fecha_entrega) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS dia,
                ROUND(SUM(d.cantidad * d.precio_vendido), 0 )  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
                AND   pe.id_usuario = ".$idUsu."
                AND   pe.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."')
                GROUP BY (pe.fecha_entrega)
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->dia            = $row->dia;
            $tmp->total          = $row->total;
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);  
      mysqli_close($mysql);
}    

    
    

public function sumaTotalPorSemRangoGene(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        $queryMax  = "SELECT MAX(p.id), p.fecha_desde, p.fecha_hasta FROM tbl_fecha_semanas p;";
        $resultMax = mysqli_query($mysql, $queryMax);
        $retMax = array();
		while ($row = mysqli_fetch_object($resultMax)) {
			$tmp =new Pedido();
			$tmp->desde            = $row->fecha_desde;
			$tmp->hasta            = $row->fecha_hasta;
            
			$retMax[] = $tmp;
		}
            
            
        $query = "        
                SELECT 
                (ELT(WEEKDAY(pe.fecha_entrega) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS dia,
                ROUND(SUM(d.cantidad * d.precio_vendido), 0 )  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                LEFT OUTER JOIN usuario u 
                on u.id = pe.id_usuario    
                WHERE 
                pe.anulada='N'
                AND   u.id_tipo NOT IN (3,5,4)
                AND   pe.fecha_entrega  BETWEEN ('".$retMax[0]->desde."')  and ('".$retMax[0]->hasta."')
                GROUP BY (pe.fecha_entrega)
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->dia            = $row->dia;
            $tmp->total          = $row->total;
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
    
    
    
public function sumaTotalPorMesCobro(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 


                WHERE 
                pe.anulada='N'
                AND   pe.id_usuario  NOT IN (3) 
                AND   pe.estado_cobranza in (1,2,3)
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386) 
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret); 
      mysqli_close($mysql);
}    
              
		
 /*****************************CANTIDAD GRAFICO*************************************************/	
    
public function cantidadParticular($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        				
				SELECT 
				YEAR(pe.fecha_entrega) ano,
				MONTH(pe.fecha_entrega) mes,
				COUNT(pe.id_pedido)  total
				FROM tbl_pedido pe 
				LEFT OUTER JOIN tbl_cliente c 
				ON c.id_cliente = pe.id_cliente 
				WHERE 
				c.tipo_comprador='Particular'
				AND   pe.estado_pedido = 1
				AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386,131,48,5) 				
				AND   pe.id_usuario  = '".$idUsu."'
				AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
				GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
				ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret); 
      mysqli_close($mysql);
}

public function cantidadMascotero($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        				
				SELECT 
				YEAR(pe.fecha_entrega) ano,
				MONTH(pe.fecha_entrega) mes,
				COUNT(pe.id_pedido)  total
				FROM tbl_pedido pe 
				LEFT OUTER JOIN tbl_cliente c 
				ON c.id_cliente = pe.id_cliente 
				WHERE 
				c.tipo_comprador='Mascotero'
				AND   pe.estado_pedido = 1
				AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386,131,48,5) 				
				AND   pe.id_usuario  = '".$idUsu."'
				AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
				GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
				ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
} 
            	
public function cantidadAlmacen($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        				
				SELECT 
				YEAR(pe.fecha_entrega) ano,
				MONTH(pe.fecha_entrega) mes,
				COUNT(pe.id_pedido)  total
				FROM tbl_pedido pe 
				LEFT OUTER JOIN tbl_cliente c 
				ON c.id_cliente = pe.id_cliente 
				WHERE 
				c.tipo_comprador='Almacen'
				AND   pe.estado_pedido = 1				
				AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386,131,48,5) 				
				AND   pe.id_usuario  = '".$idUsu."'
				AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
				GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
				ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}

public function cantidadVeterinaria($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        				
				SELECT 
				YEAR(pe.fecha_entrega) ano,
				MONTH(pe.fecha_entrega) mes,
				COUNT(pe.id_pedido)  total
				FROM tbl_pedido pe 
				LEFT OUTER JOIN tbl_cliente c 
				ON c.id_cliente = pe.id_cliente 
				WHERE 
				c.tipo_comprador='Veterinaria'
				AND   pe.estado_pedido = 1				
				AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386,131,48,5) 
				AND   pe.id_usuario  = '".$idUsu."'
				AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
				GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
				ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
public function cantidadPeluqueria($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        				
				SELECT 
				YEAR(pe.fecha_entrega) ano,
				MONTH(pe.fecha_entrega) mes,
				COUNT(pe.id_pedido)  total
				FROM tbl_pedido pe 
				LEFT OUTER JOIN tbl_cliente c 
				ON c.id_cliente = pe.id_cliente 
				WHERE 
				c.tipo_comprador='Peluqueria'
				AND   pe.estado_pedido = 1				
				AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386,131,48,5) 
				AND   pe.id_usuario  = '".$idUsu."'
				AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
				GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
				ORDER BY Mes ASC;
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
    /*********DUDA***************/
public function sumaTotalPorMesClienteActual($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
        
       SELECT 
                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 
                AND   pe.id_cliente = '".$idUsu."'
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC

        
        
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
    
public function sumaTotalPorMesClienteAnterior($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT 

                YEAR(pe.fecha_entrega) ano,
                MONTH(pe.fecha_entrega) mes,
                SUM(d.cantidad * d.precio_vendido)  total
                FROM tbl_pedido pe 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 
                AND   pe.id_cliente = '".$idUsu."'
                AND   YEAR(pe.fecha)= YEAR(DATE_SUB(NOW(),INTERVAL 1 YEAR))
                GROUP BY YEAR(pe.fecha_entrega),MONTH(pe.fecha_entrega)
                ORDER BY Mes ASC
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$tmp->mes            = $row->mes;
			$tmp->ano            = $row->ano;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
 public function listarVentaPorTipo($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT MONTH(now()) as mes,
            SUM(CASE WHEN cl.tipo_comprador = 'Particular'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS particular,
            SUM(CASE WHEN cl.tipo_comprador = 'Mascotero'   THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS mascotero,
            SUM(CASE WHEN cl.tipo_comprador = 'Almacen'     THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS almacen,
            SUM(CASE WHEN cl.tipo_comprador = 'Veterinaria' THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS veterinaria,
            SUM(CASE WHEN cl.tipo_comprador = 'Avicola'     THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS agricola,
            SUM(CASE WHEN cl.tipo_comprador = 'Peluqueria'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS peluqueria,
            SUM(CASE WHEN cl.tipo_comprador = 'Otro       ' THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS otro

            FROM tbl_pedido p 
            LEFT OUTER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            LEFT OUTER JOIN productos pr
            ON pr.id = d.id_prod
            LEFT OUTER JOIN marca m 
            ON m.codMarca = pr.marcaProd
            LEFT OUTER JOIN categoria c 
            ON c.codCategoria = pr.categoriaProd
            LEFT OUTER JOIN tbl_proveedores pv 
            ON pv.id_proveedor = m.cod_proveedor
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario

            WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND  p.id_usuario  = '".$idUsu."'
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            GROUP BY us.nombre
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->mes           = $row->mes;
			$tmp->particular    = $row->particular;
			$tmp->mascotero     = $row->mascotero;
			$tmp->almacen       = $row->almacen;
			$tmp->agricola      = $row->agricola;
			$tmp->veterinaria      = $row->veterinaria;
 			$tmp->peluqueria   = $row->peluqueria;
 			$tmp->otro   = $row->otro;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret); 
       mysqli_close($mysql);
}   
    
    
  public function listarVentaVendedores($mes, $ano){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT 
tp.nombre_tipo,
us.nombre,
/*MONTH(date_sub(now(), interval 1 month)) as mes,*/
             MONTH(p.fecha_entrega) as mes,
            SUM(CASE WHEN cl.tipo_comprador = 'Particular'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS particular,
            SUM(CASE WHEN cl.tipo_comprador = 'Mascotero'   THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS mascotero,
            SUM(CASE WHEN cl.tipo_comprador = 'Almacen'     THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS almacen,
            SUM(CASE WHEN cl.tipo_comprador = 'Veterinaria' THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS veterinaria,
            SUM(CASE WHEN cl.tipo_comprador = 'Avicola'    THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS agricola,
            SUM(CASE WHEN cl.tipo_comprador = 'Peluqueria'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS peluqueria,
            SUM(CASE WHEN cl.tipo_comprador = 'Otro'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS Otro


            FROM tbl_pedido p 
            LEFT OUTER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            LEFT OUTER JOIN productos pr
            ON pr.id = d.id_prod
            LEFT OUTER JOIN marca m 
            ON m.codMarca = pr.marcaProd
            LEFT OUTER JOIN categoria c 
            ON c.codCategoria = pr.categoriaProd
            LEFT OUTER JOIN tbl_proveedores pv 
            ON pv.id_proveedor = m.cod_proveedor
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
             LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id = us.id_tipo

            /*WHERE MONTH(p.fecha_entrega) = MONTH(date_sub(now(), interval 1 month))
            AND YEAR(p.fecha_entrega) = YEAR(NOW()) */
            
            WHERE MONTH(p.fecha_entrega) = '".$mes."'
            AND YEAR(p.fecha_entrega) = '".$ano."'
            
            
            
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162)
            AND  us.id  NOT IN (158)             
            AND  p.anulada='N'
            GROUP BY us.nombre,
                 tp.nombre_tipo
                 ORDER BY tp.nombre_tipo ASC
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->mes           = $row->mes;
			$tmp->particular    = $row->particular;
			$tmp->mascotero     = $row->mascotero;
			$tmp->almacen       = $row->almacen;
			$tmp->agricola      = $row->agricola;
			$tmp->veterinaria   = $row->veterinaria;
 			$tmp->peluqueria   = $row->peluqueria;            
			$tmp->nombre        = $row->nombre;
 			$tmp->nombre_tipo   = $row->nombre_tipo;
  			$tmp->otro   = $row->otro;
          
            

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
        mysqli_close($mysql);
}      
    
    
public function sumaCantidadOferta($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "       
                SELECT
                     IFNULL(SUM(dp.cantidad),0) as cantidad
                FROM tbl_pedido p 
                LEFT OUTER JOIN tbl_deta_pedido dp 
                ON dp.id_pedido = p.id_pedido
                LEFT OUTER JOIN usuario us 
                ON us.id = p.id_usuario
                LEFT OUTER JOIN productos pc 
                ON pc.id  =  dp.id_prod
                WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
                AND  YEAR(p.fecha_entrega) = YEAR(NOW())
                AND  p.id_usuario = '".$idUsu."'
                AND  p.estado_pedido = 1
                AND  p.id_cliente  NOT IN (87,162) 
                AND  p.anulada='N'
                AND  pc.prod_venta_act = 1
                
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			  $tmp->cantidad          = $row->cantidad;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret); 
      mysqli_close($mysql);
}
    

    
public function listarVentaActualUsua(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT 
           tp.nombre_tipo,
            us.nombre,
            MONTH(now()) as mes,
            SUM(CASE WHEN cl.tipo_comprador = 'Particular'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS particular,
            SUM(CASE WHEN cl.tipo_comprador = 'Mascotero'   THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS mascotero,
            SUM(CASE WHEN cl.tipo_comprador = 'Almacen'     THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS almacen,
            SUM(CASE WHEN cl.tipo_comprador = 'Veterinaria' THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS veterinaria,
            SUM(CASE WHEN cl.tipo_comprador = 'Avicola'    THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS agricola,
            SUM(CASE WHEN cl.tipo_comprador = 'Peluqueria'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS peluqueria,
              SUM(CASE WHEN cl.tipo_comprador = 'Otro'  THEN d.cantidad * d.precio_vendido ELSE 0 END)  AS otro
          
            FROM tbl_pedido p 
            LEFT OUTER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            LEFT OUTER JOIN productos pr
            ON pr.id = d.id_prod
            LEFT OUTER JOIN marca m 
            ON m.codMarca = pr.marcaProd
            LEFT OUTER JOIN categoria c 
            ON c.codCategoria = pr.categoriaProd
            LEFT OUTER JOIN tbl_proveedores pv 
            ON pv.id_proveedor = m.cod_proveedor
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id  NOT IN (158)
            GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->mes           = $row->mes;
			$tmp->particular    = $row->particular;
			$tmp->mascotero     = $row->mascotero;
			$tmp->almacen       = $row->almacen;
			$tmp->agricola      = $row->agricola;
			$tmp->veterinaria   = $row->veterinaria;
 			$tmp->peluqueria   = $row->peluqueria;           
	        $tmp->nombre        = $row->nombre;
 			$tmp->nombre_tipo   = $row->nombre_tipo;
     			$tmp->otro   = $row->otro;
        
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
    
public function sumaCantidadOfertaTotal(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "       
                SELECT
                     tp.nombre_tipo,
                     us.nombre,
                     us.apellido,
                     IFNULL(SUM(dp.cantidad),0) as cantidad,
                     us.id
                FROM tbl_pedido p 
                LEFT OUTER JOIN tbl_deta_pedido dp 
                ON dp.id_pedido = p.id_pedido
                LEFT OUTER JOIN usuario us 
                ON us.id = p.id_usuario
                LEFT OUTER JOIN productos pc 
                ON pc.id  =  dp.id_prod
                LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
                WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
                AND  YEAR(p.fecha_entrega) = YEAR(NOW())
				AND us.id_tipo in (2,7,1,4,8)
				AND  p.estado_pedido = 1
                AND  p.id_cliente  NOT IN (87,162) 
                AND  p.anulada='N'
                AND  pc.prod_venta_act = 1
                AND  us.id  NOT IN (158)
                group by us.nombre,
                         tp.nombre_tipo,
                         us.apellido
                ORDER BY tp.nombre_tipo ASC";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
             			  $tmp->nombre_tipo       = $row->nombre_tipo;
                          $tmp->nombre            = $row->nombre;
                          $tmp->apellido          = $row->apellido;
			              $tmp->cantidad          = $row->cantidad;
 			              $tmp->id          = $row->id;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}
    
 
public function listarActualPorcentaje(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
          SELECT 
            tp.nombre_tipo,
            us.nombre,
            us.apellido,            
            us.id,
            SUM(d.cantidad * d.precio_vendido)  AS totalActual       
            FROM tbl_pedido p 
            LEFT OUTER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id  NOT IN (158)
            GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->nombre_tipo       = $row->nombre_tipo;
			$tmp->nombre            = $row->nombre;
			$tmp->apellido            = $row->apellido;            
			$tmp->id                = $row->id;
			$tmp->totalActual       = $row->totalActual;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
      mysqli_close($mysql);
}    
    
    
 
public function listarAnteriorPorcentaje(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		
    $query = "        
          SELECT 
            tp.nombre_tipo,
            us.nombre,
            us.apellido,            
            us.id,
            SUM(d.cantidad * d.precio_vendido)  AS totalActual       
            FROM tbl_pedido p 
            INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id  NOT IN (158)
            GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result = mysqli_query($mysql, $query);

    
    
       $query2 = "        
          SELECT 
            tp.nombre_tipo,
            us.nombre,
            us.apellido,
            us.id,
            SUM(d.cantidad * d.precio_vendido)  AS totalAnterior     
            FROM tbl_pedido p 
            INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(date_sub(now(), interval 1 month))
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id  NOT IN (158)
            GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result2 = mysqli_query($mysql, $query2);
    
		$ret = array();
    
     while($row = mysqli_fetch_array($result2)) {
         $num_resultados = mysqli_num_rows($result);
         mysqli_data_seek($result, 0);
 
        for ($i=0; $i<$num_resultados; $i++){
                $row2 = mysqli_fetch_array($result);            
                   if($row['id'] == $row2['id'] ){                         
                                $tmp =new Pedido();
                                $tmp->nombre_tipo       = $row['nombre_tipo'];
                                $tmp->nombre            = $row['nombre'];
                                $tmp->apellido          = $row['apellido'];            
                                $tmp->id                = $row['id'];
                                $tmp->total     = round((((($row2['totalActual']) / $row['totalAnterior'])) - 1  ) * 100);
                                array_push( $ret, $tmp);
                         }             
            }     
        } 
		mysqli_free_result($result);
		      return ($ret); 
      mysqli_close($mysql);
}   
    
    
    
    
    
    
public function listarPorcentajePorUsuario($idUsu){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		
    $query = "        
          SELECT 
            tp.nombre_tipo,
            us.nombre,
            us.apellido,            
            us.id,
            SUM(d.cantidad * d.precio_vendido)  AS totalActual       
            FROM tbl_pedido p 
            INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
            AND YEAR(p.fecha_entrega) =  YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id ='".$idUsu."'
        GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result = mysqli_query($mysql, $query);

    
    
       $query2 = "        
          SELECT 
            tp.nombre_tipo,
            us.nombre,
            us.apellido,
            us.id,
            SUM(d.cantidad * d.precio_vendido)  AS totalAnterior     
            FROM tbl_pedido p 
            INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            WHERE MONTH(p.fecha_entrega) = MONTH(date_sub(now(), interval 1 month))
            AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND p.estado_pedido = 1
            AND us.id_tipo in (2,7,1,4,8)
            AND  p.id_cliente  NOT IN (87,162) 
            AND  p.anulada='N'
            AND  us.id ='".$idUsu."'
            GROUP BY us.nombre
            ORDER BY tp.nombre_tipo ASC
        ";
		$result2 = mysqli_query($mysql, $query2);
    
		$ret = array();
    
     while($row = mysqli_fetch_array($result2)) {
         $num_resultados = mysqli_num_rows($result);
         mysqli_data_seek($result, 0);
 
        for ($i=0; $i<$num_resultados; $i++){
                $row2 = mysqli_fetch_array($result);            
                   if($row['id'] == $row2['id'] ){                         
                                $tmp =new Pedido();
                                $tmp->nombre_tipo       = $row['nombre_tipo'];
                                $tmp->nombre            = $row['nombre'];
                                $tmp->apellido          = $row['apellido'];            
                                $tmp->id                = $row['id'];
                                $tmp->total     = round((((($row2['totalActual']) / $row['totalAnterior'])) - 1  ) * 100);
                                array_push( $ret, $tmp);
                         }             
            }     
        } 
		mysqli_free_result($result);
    		mysqli_free_result($result2);

		      return ($ret);   
      mysqli_close($mysql);
}    
    

    
 public function anualVentaUsuario(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
              SELECT 
                u.id,        
                u.nombre,
                u.apellido,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 1  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes1,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 2  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes2,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 3  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes3,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 4  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes4,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 5  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes5,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 6  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes6,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 7  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes7,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 8  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes8,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 9  THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes9, 
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 10 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes10,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 11 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes11,
                SUM(CASE WHEN MONTH(pe.fecha_entrega) = 12 THEN d.cantidad * d.precio_vendido ELSE 0 END) AS mes12
                FROM tbl_pedido pe 
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido 
                INNER JOIN usuario u 
                ON u.id = pe.id_usuario
                WHERE 
                pe.anulada='N'
				AND   pe.estado_pedido=1 
                AND   u.id_tipo in (2,7,1,4,8)
                AND   pe.id_cliente NOT IN (87,162,116,273,274,275,386) 
                AND   YEAR(pe.fecha_entrega)= YEAR(NOW())
                group by 
                        u.id,
                        u.nombre,
                        u.apellido
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id            = $row->id;
			$tmp->nombre        = $row->nombre;
			$tmp->apellido      = $row->apellido;            
			$tmp->mes1          = $row->mes1;
			$tmp->mes2          = $row->mes2;
			$tmp->mes3          = $row->mes3;
			$tmp->mes4          = $row->mes4;
			$tmp->mes5          = $row->mes5;
			$tmp->mes6          = $row->mes6;
			$tmp->mes7          = $row->mes7;
			$tmp->mes8          = $row->mes8;
			$tmp->mes9          = $row->mes9;
			$tmp->mes10         = $row->mes10;
			$tmp->mes11         = $row->mes11;
			$tmp->mes12         = $row->mes12;
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret); 
       mysqli_close($mysql);
}    
    

    
 public function listProductosFocos(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
              SELECT   pr.id, 
                       pr.nombreProd,
                SUM(CASE WHEN (us.id) = 2 THEN d.cantidad ELSE 0 END)   AS DIEGO,
                SUM(CASE WHEN (us.id) = 131 THEN d.cantidad ELSE 0 END) AS FRANCISCA,
                SUM(CASE WHEN (us.id) = 48 THEN d.cantidad ELSE 0 END)  AS BLANCA,
                SUM(CASE WHEN (us.id) = 204 THEN d.cantidad ELSE 0 END)  AS ELIAS,
                SUM(CASE WHEN (us.id) = 1 THEN d.cantidad ELSE 0 END)  AS JUAN
                SUM(CASE WHEN (us.id) = 1 THEN d.cantidad ELSE 0 END)  AS CRISTIAN


                FROM tbl_pedido p 
                LEFT OUTER JOIN tbl_deta_pedido d 
                ON d.id_pedido = p.id_pedido
                LEFT OUTER JOIN productos pr
                ON pr.id = d.id_prod
                LEFT OUTER JOIN tbl_cliente cl 
                ON cl.id_cliente = p.id_cliente
                LEFT OUTER JOIN usuario us 
                ON us.id = p.id_usuario
                INNER JOIN tbl_productos_foco pf 
                            ON pf.id_prod = d.id_prod
                            AND pf.activo = 1
                WHERE
                            MONTH(p.fecha_entrega) = 7
                            AND YEAR(p.fecha_entrega) = YEAR(NOW())
                            AND us.id_tipo in (2,7,1,4,8)
                            AND  p.id_cliente  NOT IN (87,162) 
                            AND  p.anulada='N'
                            AND p.estado_pedido = 1
                GROUP BY pr.id, pr.nombreProd
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id            = $row->id;
			$tmp->nombre        = $row->nombre;
			$tmp->apellido      = $row->apellido;            
			$tmp->mes1          = $row->mes1;
			$tmp->mes2          = $row->mes2;
			$tmp->mes3          = $row->mes3;
			$tmp->mes4          = $row->mes4;
			$tmp->mes5          = $row->mes5;
			$tmp->mes6          = $row->mes6;
			$tmp->mes7          = $row->mes7;
			$tmp->mes8          = $row->mes8;
			$tmp->mes9          = $row->mes9;
			$tmp->mes10         = $row->mes10;
			$tmp->mes11         = $row->mes11;
			$tmp->mes12         = $row->mes12;
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
        mysqli_close($mysql);
}    
    
 
    
public function listProductosVentas($idUsuario){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
              SELECT        pr.id, 
                            pr.nombreProd,
                            SUM(d.cantidad) as cantidad
                    FROM tbl_pedido p 
                    INNER JOIN tbl_deta_pedido d 
                    ON d.id_pedido = p.id_pedido
                    LEFT OUTER JOIN productos pr
                    ON pr.id = d.id_prod
                    LEFT OUTER JOIN marca m 
                    ON m.codMarca = pr.marcaProd
                    LEFT OUTER JOIN categoria c 
                    ON c.codCategoria = pr.categoriaProd
                    LEFT OUTER JOIN tbl_proveedores pv 
                    ON pv.id_proveedor = m.cod_proveedor
                    LEFT OUTER JOIN tbl_cliente cl 
                    ON cl.id_cliente = p.id_cliente
                    LEFT OUTER JOIN usuario us 
                    ON us.id = p.id_usuario

                    WHERE p.fecha_entrega BETWEEN '2019-06-01' AND '2019-06-30'
                    and pr.prod_venta_act = 1
                    and us.id = 48
                    GROUP BY pr.id, 
                             pr.nombreProd
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id            = $row->id;
			$tmp->nombre        = $row->nombre;
			$tmp->cantidad      = $row->cantidad;            

            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   
    
   mysqli_close($mysql);
    
}    
    

}

?>