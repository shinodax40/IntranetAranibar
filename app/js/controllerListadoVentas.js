angularRoutingApp.controller('controllerListadoVentas', ['$scope', '$http',
    function ($scope, $http) {   
             $scope.idPedido              = '';
          $scope.idFolio              = '';
   
             $scope.detPedido             = []; 
             $scope.listadoVentas         = []; 
             $scope.tipUsuarioCaja        = false;
             $scope.detalleVentas         = [];
             $scope.pedidoDescuento       = 0;
             $scope.customCajaValue       = false;
             $scope.customCajaValue2      = false;
             $scope.customRetiroValue     = false;
             $scope.customVentaValue      = true;

             $scope.tipoBusqueda          = "1";
             $scope.customCajaFin         = false;
             $scope.customCajaInicio      = false;
             $scope.id_caja               = "";
             $scope.fecha_caja_inicio     = "";
             $scope.hora_caja_inicio      = "";
             $scope.fecha_caja_fin        = "";
             $scope.hora_caja_fin         = "";
             $scope.estadoCaja            = false;
             $scope.lisProdOfert    = [];     
        
             $scope.cantidadListar        = 0;
             $scope.caja_chica             = 0;
        
            var mydate=new Date();
            var year=mydate.getYear();
            if (year < 1000)
            year+=1900;
            var day=mydate.getDay();
            var month=mydate.getMonth()+1;
            if (month<10)
            month="0"+month;
            var daym=mydate.getDate();
            if (daym<10)
            daym="0"+daym;    
        
            $scope.idPedido="";
            $scope.observacionVendAnular="";
            
            $scope.fechaActualVenta = year+"-"+month+"-"+daym;

            $scope.descuentos = [];
            $scope.descuentosCierre = [];
            $scope.pedidosCierre    = [];
        
        
$scope.init = function () {
    $scope.listarProductoOfertaDesc();
           $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
 $scope.test = {};   
    
     document.getElementById('divPedidoListado').style.display      = 'block';   
     document.getElementById('divPedido').style.display             = 'none'; 
    
    
     $scope.listarInicioCaja();    
    
    
     
    if($scope.tipoUsuarioLogin == 'Supervisor Caja'){
        $scope.tipUsuarioCaja = true;   
    }
    
     if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }
    
     var desde =  document.getElementById('desdeb').value= year+"-"+month+"-"+daym;
     var hasta =  document.getElementById('hastab').value= year+"-"+month+"-"+daym;       
    $scope.buscarPedidoDatos();
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentasGeneradas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({desde:desde, hasta:hasta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);       
                 $scope.listadoVentas = data;
                 setTimeout($.unblockUI, 1000);  
          }).error(function(error){
                        console.log(error);
                        setTimeout($.unblockUI, 1000);  
    });   

};
        
        
        
        
        
$scope.verTransfAdj= function(idPedidoSel){
   $scope.idPedido     =  idPedidoSel.id_pedido; 
   $scope.estImgTranf  =  idPedidoSel.estadoFotoTransf;  
   $scope.nombPedido   =  idPedidoSel.nombre;  

}
        
        
$scope.recorrerListado = function(pedido, tipo, identificador){
    var ti="";
    if(tipo == "33"){
       ti="Fac";
    }else{
       ti="Bol"; 
    }
    
    
     for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == $scope.idPedido ){	
                
                $scope.listPedidos[i].folio      = pedido.replace(/\s/g, "");
                $scope.listPedidos[i].tipo_folio = ti;
                $scope.listPedidos[i].identificacion = identificador; 
			}		
          }
 }        
        
        
 $scope.generarBoleta = function () {
    
//if($scope.conultarNuboxFolio() == true){    
    
    
    var mydate=new Date();
    var year=mydate.getYear();
    if (year < 1000)
    year+=1900;
    var day=mydate.getDay();
    var month=mydate.getMonth()+1;
    if (month<10)
    month="0"+month;
    var daym=mydate.getDate();
    if (daym<10)
    daym="0"+daym;
    
    
    var rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    var TpoDTE        = "39";
    var FchEmision    = daym+"-"+month+"-"+year;
    var Rut           = "55555555-5";/*res+'-'+digito;*/
    var RznSoc        = "Persona Natural"; //$scope.listPedidosIndex.nombre;
    var Giro          = $scope.listPedidosIndex.giro.substr(0,35);
    var Comuna        = "ARICA";
    var Direccion     = "Direccion Persona Natural";
    var Email         = "";
    var IndTraslado   = "";
    var ComunaDestino = "";
    var Vendedor      = "";/*$scope.listPedidosIndex.nombreUsuario; */    
    
   /* var FormaPago = 0;
    var TerminosPagos = 1;
    
    if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
         FormaPago      = 1; 
    }else{
         FormaPago      = 2; 
         TerminosPagos  = $scope.listPedidosIndex.credito; 
    }*/
    
    
    var arrayDeta     = [];
    

 //$.blockUI({ message: "Generando Factura Electronica..." });  

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    

   var iva = 0.19;
   var total = 0;

    
    

   for (var i = 0; i <    $scope.detPedido.length; i++) {   
    
    var objDeta= new Object();
    total = 0;  
    total =  $scope.detPedido[i].precio_vendido;    
    var totalNeto = (Math.round(total));
    total = (Math.round(total) * iva) + Math.round(totalNeto);              
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.detPedido[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.detPedido[i].cantidad;
            objDeta.Precio      = Math.round(total);
            objDeta.Codigo      =  $scope.detPedido[i].codProducto;
            arrayDeta.push(objDeta);      
   }
    
   var objDetalles= JSON.stringify(arrayDeta);    
   
  
	
		
	      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:'Persona Natural', 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:'', 
                                        FormaPago:'',
                                        TerminosPagos:'',
										Detalles:objDetalles,                                        
                                        idPedido:$scope.listPedidosIndex.id_pedido,
                                        TipoServicio:'3'
										
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
              
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta!=""){
                    
                  alertify.alert(RznSoc+" Factura Generada!");
                  alertify.set({ delay: 10000 });
             //     $scope.buscarPedido('');      
                                        $scope.buscarPedidoDatos();

                  $scope.verDetallePedidoSalir();
               //   $scope.detPedido           = [];     
              //    $scope.listPedidosIndex    = [];     
                    
                    
                  var arrayNubox = data.split(';;;');
                    
                  var folio               = arrayNubox[0];
                  var tipo                = arrayNubox[1];
                  var identificador       = arrayNubox[2];
    
                  $scope.recorrerListado(folio, tipo);
                  $scope.mostBotBol = false;    
                    
                    
                }else{
                    
                    var arrayNubox = data.split(';;;');
                    alertify.error(arrayNubox[3]);
                }
          
               setTimeout($.unblockUI, 1000);   
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });
    
//}else{
    
  //  alertify.alert("Pedido ya se encuentra Boleteado");
//}
    
};   
        
            
        

$scope.conultarNuboxFolioBoleta = function(){     
    $scope.estadoNuboxConsultar = false;
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarNuboxPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                     alertify.alert("Pedido ya se encuentra numero folio, favor contactar con el administrador.");
                    //$scope.estadoNuboxConsultar = true;
                }else{
                    $scope.confirmarBoleta();
                 //   $scope.generarBoleta();
                }          
              
          }).error(function(error){
                        console.log(error);
    
    });  
       
   // return $scope.estadoNuboxConsultar;   
        
};             
        
        
$scope.confirmarBoleta = function () { 
  if($scope.detPedido.length > 20){        
     alertify.alert("Para poder generar Boleta Electronica, maximo 20 productos.");
  }else{
       
       alertify.confirm("Esta seguro que desea generar Boleta Electronica?", function (e) {
        if (e) {
           // $scope.generarBoleta();
            $scope.generarBoletaListadoPedidos();
             $("#myModalModificarPedido").modal("hide");
           }
         }      
        );     
   }
    
};       
     
     
$scope.generarBoletaListadoPedidos =function (){
    
            $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=generarBoletaDesdeListadoPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);

        
        
                if(data  != 0){
                    
                  alertify.alert(RznSoc+" Boleta Emitida Con Exito!");
                  alertify.set({ delay: 10000 });
             //     $scope.buscarPedido('');         
                  $scope.verDetallePedidoSalir();
               //   $scope.detPedido           = [];     
              //    $scope.listPedidosIndex    = [];     
                    
                    
 
                    
                  $scope.recorrerListado(data, 'Bol');
                  $scope.mostBotBol = false;    
                    
                    
                }else{
                    
                        alertify.error("Error al Emitir.");

                    alertify.alert(data);
                }
        
        
        
        
        
        
          }).error(function(error){
                        console.log(error);
    
      });
    
}        
        

$scope.getTotal = function(){
var length = $scope.detalleVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
      objPro = $scope.detalleVentas;
      total += parseInt($scope.detalleVentas[i].cantidad  * $scope.detalleVentas[i].precio_vendido);
    }     
        
return parseInt(total-$scope.pedidoDescuento);
};    

        
$scope.buscar = function () {
    var desde  = angular.element(document.querySelector("#desdeb")).val();
    var hasta  = angular.element(document.querySelector("#hastab")).val();
    
    $http({
                        method : 'POST',
                        data:  $.param({desde:desde, hasta:hasta}),
                        url : 'FunctionIntranet.php?act=listarVentasGeneradas&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       
            $scope.listadoVentas = data;

          }).error(function(error){
                        console.log(error);
    
    });   

};  
        
        
$scope.anularPedido2  = function (index){
    $scope.idPedido = index.id_pedido;
    $scope.idFolio = index.folio;
    $("#myModalObserPedidoAnular").modal();
}       


$scope.anularPedidoRetiroEntienda = function (){
    $("#myModalObserPedidoAnularDespacho").modal();
}   
        
$scope.confirmarAnularPedido = function () { 

    if($scope.observacionVendAnular.length > 10 ){
    alertify.confirm("Esta seguro que desea anular venta?", function (e) {
        if (e) {
            $scope.anularPedido();
            $("#myModalModificarPedido").modal("hide");
            
        } 
    });    
        
        }else{
            alertify.alert("Para poder anular debe ingresar una observacion mayor a 10 caracteres.");
        }
};  
        
        /*******AQUI VAMOS hoy********/

$scope.anularPedido = function () {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=anularPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idPedido,
                             oberv:$scope.observacionVendAnular,
                             folio:$scope.idFolio,
                             idUsuario:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                 var respuesta = data.charAt(data.length-1);
                
                if(respuesta==1){                
                    alertify.success("Venta anulada.");
                    $scope.observacionVendAnular="";
                    $("#myModalObserPedidoAnular").modal("hide");
                    $("#myModalObserPedidoAnularDespacho").modal("hide");
            
                    
                     $scope.buscarPedidoDatos();
                    $scope.listarVentas();


                }else{
                    alertify.error("Error al anular venta, favor contactar con el Administrador del Sistema.");
                }
        
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};
        
        
$scope.listarVentas = function(){
     var desde =  document.getElementById('desdeb').value= year+"-"+month+"-"+daym;
     var hasta =  document.getElementById('hastab').value= year+"-"+month+"-"+daym;     
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentasGeneradas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({desde:desde, hasta:hasta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       
            $scope.listadoVentas = data;

          }).error(function(error){
                        console.log(error);
    
    });   
}
        
        
$scope.getTotalEfectivo = function(){
var length = $scope.listadoVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
       if($scope.listadoVentas[i].estado_cobranza ==1  && $scope.listadoVentas[i].anulada == "N"){
              total += parseInt($scope.listadoVentas[i].total);
       }
   }     
        
return parseInt(total);
};    
       
$scope.getTotalDebito = function(){
var length = $scope.listadoVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
       if($scope.listadoVentas[i].estado_cobranza ==5 && $scope.listadoVentas[i].anulada == "N"){
              total += parseInt($scope.listadoVentas[i].total);
       }
   }     
        
return parseInt(total);
};   
        
        
$scope.getTotalTransferencia = function(){
var length = $scope.listadoVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
       if($scope.listadoVentas[i].estado_cobranza ==6 && $scope.listadoVentas[i].anulada == "N"){
              total += parseInt($scope.listadoVentas[i].total);
       }
   }     
        
return parseInt(total);
};           
        
        
$scope.getTotalVentasDiarias = function(){
var length = $scope.listadoVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
       if($scope.listadoVentas[i].anulada == "N"){
              total += parseInt($scope.listadoVentas[i].total);
       }
   }     
        
return parseInt(total);
};   
        
        
$scope.getTotalDescuentos = function(){
var length = $scope.listadoVentas.length;
var total = 0;
   for(var i = 0; i < length; i++) {
       if($scope.listadoVentas[i].anulada == "N"){
              total += parseInt($scope.listadoVentas[i].totalDescuento);
       }
   }     
        
return parseInt(total);
};         
        
        
    $scope.cargarDetalleRecibo = function (idIndex) {
    $scope.idPedido          = idIndex.id_pedido;
    $scope.nombreClie          = idIndex.nombre;
            
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarRecibosClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:idIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               
               $scope.detPedidosRecibo = data;
               $scope.observacionRecibo = data[0].observacion;
        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    

};        
        
        
$scope.verDetalle = function (prod) {
    
    $scope.idVenta =  prod.id_pedido;
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listaDetalleVentas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idVenta:prod.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       
            $scope.detalleVentas = data.detalles;
            $scope.pedidoDescuento = data.pedidoDescuento;
          }).error(function(error){
                        console.log(error);
    
    });   

};        
           
        
        
$scope.confirmarImprimirVenta= function (prod) { 
 $scope.verDetalle(prod);
    alertify.confirm("Esta seguro que desea imprimir venta?", function (e) {
        if (e) {
           
          $scope.imprimirBoletaElect(prod);            
        } 
    });            
       
};          
        
  
$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;
var length = $scope.detalleVentas.length;    

for(var j = 0; j < length; j++){
        var product = $scope.detalleVentas[j];
        total += Math.round(product.cantidad * product.precio_vendido);
}

return Math.round(total);
}; 
        
  function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}       

        
/*                                 url : '//192.168.8.102:80/adminSantaMaria/FunctionIntranet.php?act=imprimirTermicaBoletaVentas&tienda='+$scope.tiendaSeleccionada,        */            

        
$scope.imprimirBoletaElect = function(numbPed){
   var arrayDeta     = []; 
   var iva = 0.19;
   var total = 0;

    
          for (var i = 0; i <    $scope.detalleVentas.length; i++) {          
                  var objDeta= new Object();           
                   objDeta.pedido         = '';
                        objDeta.nombreProd     =  $scope.detalleVentas[i].nombreProd;
                        objDeta.cantidad       =  $scope.detalleVentas[i].cantidad;
                        objDeta.folio          =  numbPed.id_pedido;
                        total               = 0;  
                        total               =  Math.round($scope.detalleVentas[i].precio_vendido * $scope.detalleVentas[i].cantidad);    
                        var totalNeto       = (Math.round(total));
                     /*   total               = Math.round((Math.round(total) * iva) + Math.round(totalNeto));    */   
                        objDeta.Precio      = formatNumero((total.toString()));
                        objDeta.TotalBol    = formatNumero($scope.getTotalPedido().toString());
                      

                        arrayDeta.push(objDeta);
                     }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

                  $http({
                         method : 'POST',
                     //    url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaBoletaVentas&tienda='+$scope.tiendaSeleccionada,       
                         url : 'FunctionIntranet.php?act=imprimirTermicaBoletaVentas&tienda='+$scope.tiendaSeleccionada,                    

                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });


    
 }



$scope.confirmarInicioCaja = function () { 

    
    alertify.confirm("Esta seguro que desea iniciar caja?", function (e) {
        if (e) {
            $scope.insertarInicioCaja();            
        } 
    });    
        

};  

        
$scope.insertarInicioCaja = function () {    
    $http({
                        method : 'POST',
                        data:  $.param({idUsua:$scope.idUsuarioLogin}),
                        url : 'FunctionIntranet.php?act=insertarInicioCaja&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
            $scope.imprimirInicioCaja(data); 
            $scope.listarInicioCaja();

          }).error(function(error){
                        console.log(error);
    
    });   

};  
        
        
        
$scope.listarInicioCaja = function () {    
    
    $scope.obserCaja ="";
    $http({
                        method : 'POST',
                        data:  $.param({idUsua:$scope.idUsuarioLogin}),
                        url : 'FunctionIntranet.php?act=listarInicioCaja&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                        console.log(data);
       
        
        
        if(data != ""){
              if(data[0].hora_caja_fin !='00:00:00' ){
            
                $scope.id_caja            = data[0].id_caja;
                  
            
                $scope.fecha_caja_fin     = data[0].fecha_caja_fin;
                $scope.hora_caja_fin      = data[0].hora_caja_fin;

                $scope.customCajaFin      = false;
                $scope.customCajaInicio   = false;
                  
                $scope.customCajaValue      = false ;                 
                $scope.customCajaValue2     = true;  
                  
            }else{
                
                $scope.id_caja            = data[0].id_caja;                  
                $scope.fecha_caja_inicio  = data[0].fecha_caja_inicio;
                $scope.hora_caja_inicio   = data[0].hora_caja_inicio;
            

                
                
                $scope.customCajaFin      = true;
                $scope.customCajaInicio   = false;
           
                $scope.customCajaValue      = true ;                 
                $scope.customCajaValue2   = false;
            }
                  
           }else{
                $scope.customCajaFin = false;
                $scope.customCajaInicio = true;
               
           }
        
       

          }).error(function(error){
                        console.log(error);
    
    });   

};  
        
        //url : '//192.168.8.102:8080/adminSantaMaria/FunctionIntranet.php?act=imprimirTermicaInicioCaja&tienda='+$scope.tiendaSeleccionada,
$scope.imprimirInicioCaja = function (data) {    
   $http({
                          method : 'POST',
      // url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaInicioCaja&tienda='+$scope.tiendaSeleccionada,
              url : 'FunctionIntranet.php?act=imprimirTermicaInicioCaja&tienda='+$scope.tiendaSeleccionada,

                        data:  $.param({idCaja:data}),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });        
}; 
        
        
$scope.confirmarFinCaja = function () { 

    alertify.confirm("Esta seguro que desea cerrar caja?", function (e) {
        if (e) {
            $scope.insertarFinCaja();            
        } 
    });    
        

};      
        
        
$scope.isAnythingSelected = function () {
    var pedido="";
    var pedidoNota="";

    for(var i = 0; i < $scope.listadoVentas.length; i++){    
            pedido += ','+$scope.listadoVentas[i].id_pedido;
            pedidoNota += ',  '+$scope.listadoVentas[i].id_pedido;
        
    }
    
    $scope.pedidoSelecNota= pedidoNota;
        
        

	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		               $scope.unirPedidos = data;
                   $("#myModalJoinPedido").modal();
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 
  
}
        
        
$scope.insertarFinCaja = function () {    
    
    $scope.listarVentas(); 
    $scope.buscarPedidoDatos();
    /****ASQUI****/
    $http({
                        method : 'POST',
                        data:  $.param({idUsua:$scope.idUsuarioLogin}),
                        url : 'FunctionIntranet.php?act=updateCajaFin&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);

             $scope.listarInicioCaja();
             $scope.imprimirFinCaja();
             $scope.imprimirTermicaListadoProductosSalida();
        
          }).error(function(error){
                        console.log(error);
    
    });   

};       
        
        
                 //    url : '//192.168.8.102:8080/adminSantaMaria/FunctionIntranet.php?act=imprimirTermicaFinCaja&tienda='+$scope.tiendaSeleccionada,   
$scope.imprimirFinCaja = function () {   
  var arrayDeta = [];    

               for (var i = 0; i <    $scope.listadoVentas.length; i++) {          
                  var objDeta= new Object();   
                  var obSelect="";   
                        objDeta.id_pedido   =  $scope.listadoVentas[i].id_pedido;
                        objDeta.folio       =  $scope.listadoVentas[i].folio;
                        objDeta.total       =  formatNumero($scope.listadoVentas[i].total.toString());
                        objDeta.anulada     =  $scope.listadoVentas[i].anulada;
                        objDeta.estado_cobranza=  $scope.listadoVentas[i].estado_cobranza;
                         objDeta.id_usuario=  $scope.idUsuarioLogin;

                        switch(objDeta.estado_cobranza) {
                             case '6'://Transferencia
                                obSelect = 'Transf';
                                break;
                             case '1'://Efectivo
                                obSelect = 'Efect';
                                break;
                             case '5'://transBank
                                obSelect = 'Transb';
                                break;
                            default:
                                  ''
                          }
                   
                   objDeta.nombre_cobro=  obSelect;
                   
                        arrayDeta.push(objDeta);
                   
                        
                   }

                var objDeta    = JSON.stringify(arrayDeta);

                                    var    efectivo       = parseInt($scope.getTotalEfectivo() - $scope.getTotalDescuentosCierre());
                                    var    debito         = $scope.getTotalDebito();
                                    var    transferencia  = $scope.getTotalTransferencia();
                                    var    descuento      = $scope.getTotalDescuentos();
                                    var    totalVenta     = parseInt($scope.getTotalVentasDiarias() - $scope.getTotalDescuentosCierre());
                                    var    gastoLocal     = $scope.getTotalDescuentosCierre();
                                      
    
    
    
   $http({
                          method : 'POST',
                              //   url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaFinCaja&tienda='+$scope.tiendaSeleccionada,
                                 url : 'FunctionIntranet.php?act=imprimirTermicaFinCaja&tienda='+$scope.tiendaSeleccionada,                                             

                        data:  $.param({idCaja: $scope.id_caja ,
                                       efectivo:formatNumero(efectivo.toString()),
                                       debito:formatNumero(debito.toString()),                                       
                                       transferencia:formatNumero(transferencia.toString()),     
                                       descuento:formatNumero(descuento.toString()),
                                       totalVenta:formatNumero(totalVenta.toString()),
                                        gastoLocal:formatNumero(gastoLocal.toString()),
                                       detalle:objDeta
                                       
                                       }),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });        
};
        
  /*      
$scope.imprimirComprobante= function(dataPed){
        alertify.confirm("¿ Esta seguro que desea imprimir boleta ?", function (e) {
        if (e) {
         $scope.imprimirBoletaElect(dataPed);  
        }            
       });        
}  
*/
   
$scope.generarReportePDF = function (tipoNotaPedido) {
    var mydate=new Date();
    var year=mydate.getYear();
    if (year < 1000)
    year+=1900;
    var day=mydate.getDay();
    var month=mydate.getMonth()+1;
    if (month<10)
    month="0"+month;
    var daym=mydate.getDate();
    if (daym<10)
    daym="0"+daym;
    
    var hora    = mydate.getHours() 
    var minuto  = mydate.getMinutes() 
    var segundo = mydate.getSeconds() 
    var horaImprimible = hora + " : " + minuto;

var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (var i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
            objDeta.codProducto    =  $scope.unirPedidos[i].id_prod;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.fecha          =  daym+"/"+month+"/"+year+ "------Inicio:"+horaImprimible+"------Fin:";
            objDeta.pedidos        =  $scope.pedidoSelecNota.substr(1);
            objDeta.bodega         =  $scope.unirPedidos[i].bodega;
            objDeta.pesoProd       =  Math.round($scope.pesoProd);
           objDeta.nombre     =  $scope.unirPedidos[i].nombre;


   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=gerexpPDFTienda&tienda='+$scope.tiendaSeleccionada,
                data: $.param(objectToSerialize),
                method: 'POST',
                responseType: 'arraybuffer',
                 headers: {
                 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                 }
            }
            
            
        $http(config).then(function successCallback(response) {              
        var linkElement = document.createElement('a');
        try {
            var blob = new Blob([response.data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var filename ="bodega_"+fechaActual+"_"+cad+".pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
            
            if($scope.tipoUsuarioLogin == 'Administrador'){                
               if(tipoNotaPedido =='1'){
                                 $scope.updateDespEst();
                  }                    
             }
            
            
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
        } catch (ex) {
            console.log(ex);
        }
            
        }, function errorCallback(response) {
            console.log("error");
        });    
};
        
         
        
        
$scope.confirmarPedidoSalida = function () { 
    alertify.confirm("Esta seguro que desea generar nota pedido?", function (e) {
        if (e) {
                    
            $scope.generarReportePDF('1');
        }
    });    
};        
        
        
$scope.obtenerPDF = function (dataPed) {

         $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
   var config = {
                url: 'FunctionIntranet.php?act=getPDF&tienda='+$scope.tiendaSeleccionada,
                data:  $.param({identificador:dataPed.identificacion, 
                                        folio:dataPed.folio 
                                       }),
                method: 'POST',
                responseType: 'arraybuffer',
                 headers: {
                 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                 }
            }
            
            
        $http(config).then(function successCallback(response) {              
        var linkElement = document.createElement('a');
        try {
            var blob = new Blob([response.data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var filename ="factura_"+dataPed.folio+".pdf";          

            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
         
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
              setTimeout($.unblockUI, 1000);   
        } catch (ex) {
            console.log(ex);
        }
            
        }, function errorCallback(response) {
            console.log("error");
        });  
};
        

$scope.imprimirComprobante= function(dataPed){
    
    if(dataPed.tipo_folio == "Fac"){
       
        alertify.confirm("Esta seguro que desea descargar factura, luego imprimir ?", function (e) {
        if (e) {
         $scope.obtenerPDF(dataPed);  
        }
         }); 
        
    }else{
       
        alertify.confirm("Esta seguro que desea imprimir boleta ?", function (e) {
        if (e) {
      $scope.imprimirBoletaElect(dataPed);  
            
                           // $scope.imprimirBoletaDeVentas(dataPed);  

            
        }
            
       }); 
       
    }
   
}




        

 
$scope.imprimirBoletaElect = function(pedSel){
   var arrayDeta     = []; 
   var iva           = 0.19;
   var total         = 0;
   var obSelect      = "";
   var banderaOferta = false;
      
  $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.detPedido = data;
                $scope.dgProductos = data;
      
      
            /**********VALIDAR PRODUCTO OFERTAR PARA DESCUENTO **************/
       
             for(var t=0; t < $scope.lisProdOfert.length; t++){
                for (var p=0; p < $scope.detPedido.length; p++){
                    if($scope.lisProdOfert[t].id_prod == $scope.detPedido[p].id_prod ){
                           banderaOferta = true;
                        break;
                       }
                }
             }
       
             /******************************/    
      
      
                  for (var i = 0; i <    $scope.detPedido.length; i++) {          
                        var objDeta= new Object();  
                        objDeta.banderaOferta  =  banderaOferta;
                        objDeta.pedido         = '';
                        objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
                        objDeta.cantidad       =  $scope.detPedido[i].cantidad;
                        objDeta.idPedido       =  pedSel.id_pedido;
                      
                      
                       if(pedSel.folio!=null){
                            objDeta.folio      =  pedSel.folio;
                        }else{
                            objDeta.folio      =  pedSel.id_pedido;
                        }
                      
                      
       
                      
                        total               = 0;  
                        total               =  Math.round($scope.detPedido[i].precio_vendido * $scope.detPedido[i].cantidad);    
                        var totalNeto       = (Math.round(total));
                        total               = Math.round((Math.round(total) * iva) + Math.round(totalNeto));       
                        objDeta.Precio      = formatNumero((total.toString()));
                        objDeta.TotalBol    = formatNumero($scope.getTotalPedido().toString());
                      
                     
                        
                          
                          switch(pedSel.estado_cobranza) {
                             case '6'://Transferencia
                                obSelect = 'Transferencia';
                                break;
                             case '1'://Efectivo
                                obSelect = 'Efectivo';
                                break;
                             case '5'://transBank
                                obSelect = 'Transbank';
                                break;
                            default:
                                  ''
                          }
                          
                        objDeta.FormaPago    = obSelect;
                      
                      
                        arrayDeta.push(objDeta);
                     }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                       //  url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaBoletaElect&tienda='+$scope.tiendaSeleccionada,
                          url : 'FunctionIntranet.php?act=imprimirTermicaBoletaElect&tienda='+$scope.tiendaSeleccionada,                                             
    
                         data:  $.param(objectToSerialize),
                                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });


        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
       
}        
          
  $scope.selTipoEnt = function(value){
    $scope.test.entregaPed     = value;   
    $scope.tipoEntregaMod      = value;  
  };
             
        
 $scope.imprimirBolBodega= function(dataPed){    
       
        alertify.confirm("Esta seguro que desea imprimir tickets retiro bodega ?", function (e) {
        if (e) {
         $scope.imprimirBoletaBodega(dataPed);  
        }        
     });    
}
 
 
 
 
 
  $scope.selTipoComprador = function (rdo) {   
 
   if(rdo=="Caja"){         
      $scope.tipoBusqueda = "1";
             $scope.customRetiroValue    = false;
             $scope.customVentaValue     = true;
            // $scope.buscarPedidoDatos();
              $scope.listarVentas();
              $scope.buscarPedidoDatos();
         
    }else if(rdo=="Retiro" ){
         $scope.tipoBusqueda          = "2"; 
         $scope.customRetiroValue     = true;
         $scope.customVentaValue      = false;         
         $scope.buscarPedidoDatos();
    }
 
};
 
 
 
 
 
 
 
$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.dgProductos.length; j++){
        var product = $scope.dgProductos[j];
        total += Math.round(product.cantidad * product.precio_vendido);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);
return Math.round(total);
};  
 
        
$scope.imprimirTermicaBodega = function(pedSel){
   var arrayDeta     = [];   
           var iva = 0.19;
   var total = 0;
  $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.dgProductos = data;
              
      for (var i = 0; i <    $scope.dgProductos.length; i++) {          
                  var objDeta= new Object();           
                   objDeta.pedido         = '';
                        objDeta.nombreProd     =  $scope.dgProductos[i].nombreProd;
                        objDeta.cantidad       =  $scope.dgProductos[i].cantidad;
                        objDeta.folio          =  pedSel.id_pedido;
                        total               = 0;  
                        total               =  Math.round($scope.dgProductos[i].precio_vendido * $scope.dgProductos[i].cantidad);    
                        var totalNeto       = (Math.round(total));
                        total               = Math.round((Math.round(total) * iva) + Math.round(totalNeto));      
                        objDeta.Precio      = formatNumero((total.toString()));
                        objDeta.TotalBol    = formatNumero($scope.getTotalPedido().toString());
                        arrayDeta.push(objDeta);
                     }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

                  $http({
                         method : 'POST',
                     //    url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaBoletaVentas&tienda='+$scope.tiendaSeleccionada,   
                         url : 'FunctionIntranet.php?act=imprimirTermicaBoletaVentas&tienda='+$scope.tiendaSeleccionada,                                             
                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 
        $scope.dgProductos    = [];     
                     $scope.tipoDePago = {
                          availableOptions: [
                            {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                            {id: 1, name: 'EFECTIVO'},
                            {id: 5, name: 'DEBITO'},
                          ],
                          selectedOption: {id: 0} 
                     }
                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });

          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
       
}


$scope.cargarDespObsVendedor = function(pedidoIndex){
    
$scope.test.currentValJornada="";
$scope.test.entregaPed="";
    
var mydate=new Date();
var year=mydate.getYear();
if (year < 1000)
year+=1900;
var day=mydate.getDay();
var month=mydate.getMonth()+1;
if (month<10)
month="0"+month;
var daym=mydate.getDate();
if (daym<10)
daym="0"+daym;
    
var arrayfecha = pedidoIndex.fecha_entrega.split('-');     

var arrayfechaIngreso = pedidoIndex.fecha.split('-');  
    
var diaStri = parseInt(arrayfechaIngreso[2]) + 2;    
    
var diaStri = parseInt(arrayfechaIngreso[2]) + 30;
 
    

    
    
    
      if(diaStri < 10){
          diaStri = "0"+diaStri;
      }
        
    
    
    if(pedidoIndex.fecha_entrega == null || pedidoIndex.fecha_entrega =="" ){              
      document.getElementById('fechaDespacho').value= year+"-"+month+"-"+daym;       
    }else{       
         var arrayfecha = pedidoIndex.fecha_entrega.split('-');      
         document.getElementById('fechaDespacho').value = arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];     
         document.getElementById('fechaDespacho').min   = arrayfechaIngreso[0]+"-"+arrayfechaIngreso[1]+"-"+arrayfechaIngreso[2];       
         document.getElementById('fechaDespacho').max   = arrayfechaIngreso[0]+"-"+arrayfechaIngreso[1]+"-"+diaStri;       
    }    
        
 
$scope.idPedido                  =  pedidoIndex.id_pedido;   
$scope.nombreCliente             =  pedidoIndex.nombre;      
$scope.observacionPedido         =  pedidoIndex.observacion;
$scope.observacionVend           =  pedidoIndex.obser_vend+""+pedidoIndex.observacion;   
$scope.test.currentValJornada    =  pedidoIndex.jornada;
$scope.jornadaMod	             =  pedidoIndex.jornada;
    
$scope.test.entregaPed           =  pedidoIndex.entregaPed;
$scope.tipoEntregaMod	         =  pedidoIndex.entregaPed;

$scope.tipPedGenerar             =  pedidoIndex.id_tipo_ped;
    
};        
        
  


        
$scope.buscarPedidoDatos = function () {

          $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({tipoPagos:'', 
                                        nombre:'', 
                                        desde:'', 
                                        hasta:'',
                                        idPedido:'',
                                        tipBusq:'Retiro en tienda',
                                        idTransp:'',
                                        busqRap:"",
                                        tipoUsuario:'',
                                        idUsuario:''}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
         setTimeout($.unblockUI, 1000); 
          
                $scope.listPedidos = data; 
 
                $scope.cantidadListar = $scope.listPedidos.length;
         
          }).error(function(error){
            setTimeout($.unblockUI, 1000);   
                        console.log(error);
    
    });
    
    

};
        
        
$scope.listarEstadosPedido= function (pedidoIndex) {
    $scope.rutModificar  =  pedidoIndex.rut;
    $scope.direccionMod  =  pedidoIndex.direccion;
    $scope.idPedido      =  pedidoIndex.id_pedido;   
    $scope.codTipoCobro  =  pedidoIndex.estado_cobranza;       
    $scope.cod_documento =  pedidoIndex.cod_documento;
    $scope.razonMod      =  pedidoIndex.nombre;
    $scope.observacion   =  pedidoIndex.obser_transp;
    $scope.codEstadoPed  =  pedidoIndex.id_estado;       
    $scope.creditoClie   =  pedidoIndex.credito;  
    
    
        $http.get('FunctionIntranet.php?act=listarEstadoPedido&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);                
                $scope.estadoTipoPedido = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error listarCobros: "+data);
}); 
    
    
$scope.estadoPedido = {
  availableOptions: [
    {id: '0', name: '-- Estado Pedido --'},  
    {id: '1', name: 'Entregado'},
    {id: '2', name: 'Con Observacion'},
    {id: '3', name: 'Pendiente'},
    {id: '4', name: 'En Despacho'},
    {id: '5', name: 'En Bodega'},
    {id: '6', name: 'Rechazado'}        
      
  ],
  selectedOption: {id: $scope.codEstadoPed} //This sets the default value of the select in the ui
}
    
  
    
    
};  
        
        
  $scope.cargarPedidoCobro= function (pedidoIndex) {
   var rutAux                 = pedidoIndex.rut;    
    var digVirif               = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido           = []; 
    $scope.listPedidosIndex    = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
$scope.correo = pedidoIndex.correo;    
    
        if(pedidoIndex.id_transp.toString() == "1"){
                    $scope.idTransporteRuta = "Transporte AZUL";
                }else if (pedidoIndex.id_transp.toString() == "2"){
                    $scope.idTransporteRuta = "Transporte ROJO";
                }else{
                    $scope.idTransporteRuta = "Transporte VERDE";
        }
    
    
 $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarLogPedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedidoLog = data;

        
          }).error(function(error){
            console.log("error Listar Log Pedido: "+data);    
    });       
    
    
 $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedido = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    });     
    
if(pedidoIndex.anulada != 'S' ){
    
document.getElementById('fechaCobro').value="00000-00-00";    
    
var mydate=new Date();
var year=mydate.getYear();
if (year < 1000)
year+=1900;
var day=mydate.getDay();
var month=mydate.getMonth()+1;
if (month<10)
month="0"+month;
var daym=mydate.getDate();
if (daym<10)
daym="0"+daym;

var arrayDeta = [];
var fechaActual= daym+"/"+month+"/"+year;
    
 
    $scope.rutModificar  =  pedidoIndex.rut;
    $scope.direccionMod  =  pedidoIndex.direccion;
    $scope.idPedido      =  pedidoIndex.id_pedido;   
    $scope.codTipoCobro  =  pedidoIndex.estado_cobranza;       
    $scope.cod_documento =  pedidoIndex.cod_documento;
    $scope.razonMod      =  pedidoIndex.nombre;
    $scope.observacion   =  pedidoIndex.obser_transp;
    $scope.codEstadoPed  =  pedidoIndex.id_estado;       
    $scope.creditoClie   =  pedidoIndex.credito;      
    
    
    
    $scope.tipPedGenerar = pedidoIndex.id_tipo_ped;
    
     
    
    $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1"){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    
   /* var arrayfecha = pedidoIndex.fecha_cobro.split('-');*/ 
    
 if(pedidoIndex.fecha_cobro == null || pedidoIndex.fecha_cobro =="" ){              
      document.getElementById('fechaCobro').value= year+"-"+month+"-"+daym;       
   }else{       
       var arrayfecha = pedidoIndex.fecha_cobro.split('-');      
       document.getElementById('fechaCobro').value= arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];       
   }       
    $scope.listarEstadosCobros();
  //  $scope.listarEstadosPedido();
    
}else{
    
   alertify.alert("Pedido Anulado");
    
}
    
    
    
};      
      
        

 $scope.modificarPedidoCobro = function(){
    var arrCobro = [];
    
    var objCobro= new Object();            
            objCobro.cobros          = $scope.cobros.selectedOption.id;    
            objCobro.pedido          = $scope.idPedido;
            objCobro.cod_documento   = $scope.cod_documento;
            objCobro.fechaCobro      = angular.element(document.querySelector("#fechaCobro")).val();
            objCobro.observacion     = $scope.observacion;
            objCobro.idUsuario       = $scope.idUsuarioLogin;
    arrCobro.push(objCobro);
    
 var objCob= JSON.stringify(arrCobro);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedidoCobro&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrCliente:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Cobro guardado con exito!");
                    $scope.idPedido="";
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
          
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });    

};                
        
$scope.listarEstadosCobros= function () {
    
$http.get('FunctionIntranet.php?act=listarCobros&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);                
                $scope.Tiposcobros = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error listarCobros: "+data);
}); 
   
$scope.cobros = {
  availableOptions: [
    {id: '0', name: '-- Tipo de Cobro --'},  
    {id: '1', name: 'Efectivo'},
  /*  {id: '2', name: 'Documento a Fecha'},*/
    {id: '3', name: 'Documento'},
    {id: '4', name: 'Pendiente'} ,
    {id: '5', name: 'TransBank'}  ,
    {id: '6', name: 'Transferencia'}  

  ],
  selectedOption: {id: $scope.codTipoCobro} //This sets the default value of the select in the ui
}
};
        
        
        
        
  $scope.modificarPedidoEstado = function(){
    var arrCobro = [];
    
    var objCobro= new Object();            
            objCobro.observacion     = $scope.observacion;
            objCobro.estadoPedido    = $scope.estadoPedido.selectedOption.id;    
            objCobro.pedido          = $scope.idPedido;
            objCobro.idUsuario       = $scope.idUsuarioLogin;

    arrCobro.push(objCobro);
    
 var objCob= JSON.stringify(arrCobro);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedidoEstado&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrCliente:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Estado guardado con exito!");
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
          
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });    

};               
        
        
$scope.generarFactura = function () {    
   
    var mydate=new Date();
    var year=mydate.getYear();
    if (year < 1000)
    year+=1900;
    var day=mydate.getDay();
    var month=mydate.getMonth()+1;
    if (month<10)
    month="0"+month;
    var daym=mydate.getDate();
    if (daym<10)
    daym="0"+daym;
    
    
    var rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    var TpoDTE        = "33";
    var FchEmision    = daym+"-"+month+"-"+year;
    var Rut           = res+'-'+digito;
    var RznSoc        = $scope.listPedidosIndex.nombre;
    var Giro          = $scope.listPedidosIndex.giro.substr(0,35);
    var Comuna        = "ARICA";
    var Direccion     = $scope.listPedidosIndex.direccion.replace("-"," ");
    var Email         = "";
    var IndTraslado   = "";
    var ComunaDestino = "";
    var Vendedor      = $scope.listPedidosIndex.nombreUsuario;     
    
     var FormaPago = 0;
    var TerminosPagos = 1;
    
    if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
         FormaPago      = 1; 
    }else{
         FormaPago      = 2; 
         TerminosPagos  = $scope.listPedidosIndex.credito; 
    }
    
    
    var arrayDeta     = [];
    

 //$.blockUI({ message: "Generando Factura Electronica..." });  

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

   for (i = 0; i <    $scope.detPedido.length; i++) {   
       
   var objDeta= new Object();
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.detPedido[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.detPedido[i].cantidad;
            objDeta.Precio      =  $scope.detPedido[i].precio_vendido;
            objDeta.Codigo      =  $scope.detPedido[i].codProducto;

   arrayDeta.push(objDeta);
       
   }
    
 var objDetalles= JSON.stringify(arrayDeta);    
   
  
	
		
	      $http({method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:Giro, 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:Vendedor, 
                                        FormaPago:FormaPago,
                                        TerminosPagos:TerminosPagos,
										Detalles:objDetalles,                                        
                                        idPedido:$scope.listPedidosIndex.id_pedido,
                                        TipoServicio:''
										
                                       }),                                
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);

          
              
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta!=""){
                  alertify.alert(RznSoc+" Factura Generada!");
                    
                alertify.set({ delay: 10000 });
                 
                // $scope.buscarPedido('');         
                 $scope.verDetallePedidoSalir();
                    $scope.buscarPedidoDatos();
               //  $scope.detPedido           = [];     
             //    $scope.listPedidosIndex    = [];     
                 
                  var arrayNubox = data.split(';;;');
                    
                  var folio      = arrayNubox[0];
                  var tipo       = arrayNubox[1];
                  var identificacion       = arrayNubox[2];
  
                  $scope.recorrerListado(folio, tipo, identificacion);
                  $scope.mostBotFact = false;
                    
                }else{
                    var arrayNubox = data.split(';;;');
                    alertify.error(arrayNubox[3]);
                }
          
          setTimeout($.unblockUI, 1000);   
              
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });
	
 
    
    
    
};
           
        
 $scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block';   
      $scope.mostBotFact  = true;
}       
 
 
    
        
  $scope.conultarNuboxFolioFactura = function(){     
    $scope.estadoNuboxConsultar = false;
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarNuboxPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
        //  var respuesta = data.charAt(data.length-1);
               var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                    
                    alertify.alert("Pedido ya se encuentra con numero Folio, favor contactar con el administrador.");
                   
                }else{
                   // $scope.generarFactura();   
                    
                    $scope.confirmarFactura();
                }     
          }).error(function(error){
                        console.log(error);
    
    });  
       
   // return $scope.estadoNuboxConsultar;   
        
};         
      
        
        
        
        
        
        
        
        
        
        
        
$scope.confirmarFactura = function () { 
  if($scope.detPedido.length > 20){        
     alertify.alert("Para poder generar Factura Electronica, maximo 20 productos.");
  }else{
       
       alertify.confirm("Esta seguro que desea generar Factura Electronica?", function (e) {
        if (e) {
          //  $scope.conultarNuboxFolioFactura();
             $scope.generarFactura();
             $("#myModalModificarPedido").modal("hide");
           }
         }      
        );     
   }
    
};
        
                
        
        
$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += Math.round(product.cantidad * product.precio_vendido);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);
return Math.round(total);
};           
        
        
$scope.getNeto= function(){
    var total = 0;
    for(var i = 0; i < $scope.detPedido.length; i++){
        var product = $scope.detPedido[i];
        total += Math.round(product.cantidad * product.precio_vendido);
    }
    return Math.round(total);    
};    

        
$scope.getIva= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += Math.round(product.cantidad * product.precio_vendido);
    }
    total =Math.round(total) * (iva);
    return Math.round(total);
};            
        
        /*
$scope.confirmarFactura = function () { 
  if($scope.detPedido.length > 20){        
     alertify.alert("Para poder generar Factura Electronica, maximo 20 productos.");
  }else{
       
       alertify.confirm("¿Esta seguro que desea generar Factura Electronica?", function (e) {
        if (e) {
            $scope.conultarNuboxFolioFactura();
            
             $("#myModalModificarPedido").modal("hide");
           }
         }      );     
   }
    
};*/
                
        
        
$scope.confirmarAnularPedidoDespacho = function () { 

    if($scope.observacionVendAnular.length > 10 ){
    alertify.confirm(" Esta seguro que desea anular pedido ?", function (e) {
        if (e) {
            $scope.anularPedido();
            $("#myModalObserPedidoAnularDespacho").modal("hide");
            
        } 
    });    
        
        }else{
            alertify.alert("Para poder anular debe ingresar una observacion mayor a 10 caracteres.");
        }
};
        
        
     
       
$scope.verDetallePedido = function(pedidoIndex){
    
$scope.btnGenerarFactura = false;    
    //$scope.limpiarProdList();
    
    $scope.idProducto="";
    
    if(pedidoIndex.folio!=null){ 
       $scope.btnGenerarFactura = true;     
    }
    if($scope.tipoUsuarioLogin == 'Vendedor'){
       $scope.btnGenerarFactura = true;     
    }  
    
    
    $scope.stadoPed = false;
    $scope.stadoPedEntreg = false;
    
    if(pedidoIndex.estado_pedido == "4" || pedidoIndex.estado_pedido == "2" || pedidoIndex.estado_pedido == "1" || pedidoIndex.estado_pedido == "5"){       
        $scope.stadoPed = true;
    }
    
    if(pedidoIndex.estado_pedido == "4"){       
        $scope.stadoPedEntreg = true;
    }
    
    
    
    
    document.getElementById('divPedidoListado').style.display      = 'none';   
    document.getElementById('divPedido').style.display             = 'block';    
    
    var rutAux                 = pedidoIndex.rut;    
    var digVirif               = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido           = []; 
    $scope.listPedidosIndex    = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
    $scope.rutModificar =  pedidoIndex.rut;
    $scope.razonMod     =  pedidoIndex.nombre;
    $scope.giroMod      =  pedidoIndex.giro;
    $scope.direccionMod =  pedidoIndex.direccion;
    $scope.idPedido     =  pedidoIndex.id_pedido;    
    

    $scope.tipPedGenerar             =  pedidoIndex.id_tipo_ped;
    
    
    $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1"){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    

    
    
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:pedidoIndex.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
   			  
                $scope.detPedido = data;
        
          }).error(function(error){
                        console.log("error Listar Detalle Pedido: "+error);
    
      });
    

}        
    



$scope.consultarEstadoPedido = function(prodIndex){    
    $scope.estPedListar     =  prodIndex.estado_pedido;    
    
    
    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consultarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:prodIndex.id_pedido.toString()}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta!="0"){
                
                          if(prodIndex.estado_pedido == respuesta){
                              $scope.verDetallePedido(prodIndex);
                          }else{
                              alertify.alert("Favor volver a listar los pedidos.");
                          }
                    
                }else{
                    alertify.error("Error al ver el detalle del pedido, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   


};        
        
$scope.modDespachoObser = function(){ 
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=modObsDesp&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({obs:$scope.observacionVend, 
                                        fecha:angular.element(document.querySelector("#fechaDespacho")).val(),
                                        id:$scope.idPedido,
										jornada:$scope.jornadaMod,
                                        tipoEntrega:$scope.tipoEntregaMod

                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                         var respuesta = data.charAt(data.length-1);

              if(respuesta=="1"){
                    alertify.success("GUARDADO CON EXITO!");
                    $scope.buscarPedidoDatos();
                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });
    
};
        
        
        
$scope.generarReportePDFTienda = function (tipoNotaPedido) {
    var mydate=new Date();
    var year=mydate.getYear();
    if (year < 1000)
    year+=1900;
    var day=mydate.getDay();
    var month=mydate.getMonth()+1;
    if (month<10)
    month="0"+month;
    var daym=mydate.getDate();
    if (daym<10)
    daym="0"+daym;
    
    var hora    = mydate.getHours() 
    var minuto  = mydate.getMinutes() 
    var segundo = mydate.getSeconds() 
    var horaImprimible = hora + " : " + minuto;

var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (var i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
            objDeta.codProducto    =  $scope.unirPedidos[i].id_prod;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.fecha          =  daym+"/"+month+"/"+year+ "------Inicio:"+horaImprimible+"------Fin:";
            objDeta.pedidos        =  $scope.pedidoSelecNota.substr(1);
            objDeta.bodega         =  $scope.unirPedidos[i].bodega;
            objDeta.pesoProd       =  Math.round($scope.pesoProd);
           objDeta.nombre          =  $scope.unirPedidos[i].nombre;


   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=gerexpPDFTienda&tienda='+$scope.tiendaSeleccionada,
                data: $.param(objectToSerialize),
                method: 'POST',
                responseType: 'arraybuffer',
                 headers: {
                 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                 }
            }
            
            
        $http(config).then(function successCallback(response) {              
        var linkElement = document.createElement('a');
        try {
            var blob = new Blob([response.data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var filename ="bodega_"+fechaActual+"_"+cad+".pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
            
            if($scope.tipoUsuarioLogin == 'Administrador'){                
               if(tipoNotaPedido =='1'){
                                 $scope.updateDespEst();
                  }                    
             }
            
            
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
        } catch (ex) {
            console.log(ex);
        }
            
        }, function errorCallback(response) {
            console.log("error");
        });    
};        
        
        

$scope.imprimirTermicaListadoProductosSalida = function(){
  var arrayDeta     = [];   
  var pedido="";
  var pedidoNota="";

    for(var i = 0; i < $scope.listadoVentas.length; i++){    
            pedido += ','+$scope.listadoVentas[i].id_pedido;
            pedidoNota += ',  '+$scope.listadoVentas[i].id_pedido;
        
    }
    
    $scope.pedidoSelecNota= pedidoNota;
        
        

	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		               $scope.unirPedidosImprimir = data;
            
            
            
             for (var i = 0; i <    $scope.unirPedidosImprimir.length; i++) {          
                  var objDeta= new Object();           
                        objDeta.id_prod        =  $scope.unirPedidosImprimir[i].id_prod;
                        objDeta.cantidad       =  $scope.unirPedidosImprimir[i].cantidad;
                        objDeta.nombreProd     =  $scope.unirPedidosImprimir[i].nombreProd;
                        arrayDeta.push(objDeta);
                   }

                var jsonData=angular.toJson(arrayDeta);    
                var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         //url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaCajaListadoProductos&tienda='+$scope.tiendaSeleccionada,   
                         url : 'FunctionIntranet.php?act=imprimirTermicaCajaListadoProductos&tienda='+$scope.tiendaSeleccionada,                    
                         data:  $.param(objectToSerialize),
                                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });
                

        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 

       
}        
            

$scope.listarProductoOfertaDesc = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarOfertasProductosDescuento&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          $scope.lisProdOfert    = []; 
       	  $scope.lisProdOfert    = data;		 

          }).error(function(error){
                        console.log(error);
    }); 
};      
        
      /*  
$scope.imprimirBoletaDeVentas = function () {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=boletaTiendaVentasPDF&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idVenta , imprimir:'Tienda'}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};  */
        
        
        
        
$scope.imprimirBoletaDeVentas = function (dataPed) {
    
     $http({
             method : 'POST',
           //  url : 'FunctionIntranet.php?act=boletaTiendaVentasPDF',
              url : '//192.168.1.128/Tienda/FunctionIntranet.php?act=boletaTiendaVentasPDF&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idVenta, imprimir:'Tienda'}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
             
        
        
        
        
        
        
        
        
     
        
$scope.imprimirBoletaBodega = function (dataPed) {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=ticketsRetiroBodega&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:dataPed.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
             
        
$scope.imprimirResumenSalidaTienda = function (dataPed) {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=imprimirTermicaCajaListadoProductos&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idUsuario:$scope.idUsuarioLogin,
                            fInicio:'',
                            fFin:''}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
                

$scope.buscarDescuentos = function(){
   
    $http({
        method : 'GET',
        url : 'FunctionIntranet.php?act=listarDescuentos&tienda='+$scope.tiendaSeleccionada,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
    }).success(function(data){
        $scope.descuentos    = data;

    }).error(function(error){
        console.log(error);
    });   
}

$scope.agregaDescuentoCierre = function(){
    $scope.descuentosCierre.push({descuento: '', monto: 0});
}
        
$scope.mostrarCierreCaja = function(){
    $('#divCierreCaja').show();

    $scope.descuentosCierre = [{descuento: '', monto: 0}];
    $scope.pedidosCierre = [];

    $scope.listarVentas();
    $scope.buscarPedidoDatos();
    $scope.buscarDescuentos();
    $('html, body').animate({
        scrollTop: $("#divCierreCaja").offset().top
    }, 1000);
}
    
$scope.getTotalDescuentosCierre = function(){
    return $scope.descuentosCierre.reduce((acc, curr) => acc + parseFloat(curr.monto), 0);
}

$scope.eliminaDescuento = function (desc) {
    const pos = $scope.descuentosCierre.indexOf(desc);
    $scope.descuentosCierre.splice(pos, 1);
}

$scope.agregaPedidoCierre = function (ped) {
    $scope.pedidosCierre.push({
        id_pedido: ped.id_pedido, 
        pedido: ped, 
        efectivo: ped.estado_cobranza == 1? ped.totalPedido : 0,
        debito: ped.estado_cobranza == 5? ped.totalPedido : 0,
        transferencia: ped.estado_cobranza == 6? ped.totalPedido : 0
    }); 
}

$scope.eliminaRetiroCierre = function (ped) {
    const pos = $scope.pedidosCierre.findIndex(p=> p.id_pedido == ped.id_pedido);
    $scope.pedidosCierre.splice(pos, 1);
}

$scope.isPedidoInCierre = function (id_pedido) {
    const pos = $scope.pedidosCierre.findIndex(p=> p.id_pedido == id_pedido);
    return pos > -1;
}

$scope.getTotalRetiroEfectivo = function () {
    return $scope.pedidosCierre.reduce((acc, curr) => acc + parseFloat(curr.efectivo), 0);
}

$scope.getTotalRetiroDebito = function () {
    return $scope.pedidosCierre.reduce((acc, curr) => acc + parseFloat(curr.debito), 0);
}

$scope.getTotalRetiroTransferencia = function () {
    return $scope.pedidosCierre.reduce((acc, curr) => acc + parseFloat(curr.transferencia), 0);
}

$scope.getTotalFinal = function () {
    return $scope.caja_chica + $scope.getTotalEfectivo() + $scope.getTotalRetiroEfectivo() - $scope.getTotalDescuentosCierre();
}

$scope.guardaCierreCaja = function () {

    alertify.confirm("Esta seguro que desea cerrar caja?", function (e) {
        if (e) {

            const listPedidoData = [];
            $scope.pedidosCierre.forEach(ped => {
                listPedidoData.push({
                    id_pedido: ped.pedido.id_pedido,
                    id_caja: $scope.id_caja,
                    nombre: ped.pedido.id_pedido + " - " + ped.pedido.nombre,
                    total_efectivo: ped.efectivo,
                    total_debito: ped.debito,
                    total_transferencia: ped.transferencia
                });
            });

            const listDescuentos = [];
            $scope.descuentosCierre.forEach(desc => {
                if(desc.descuento){
                    const d = $scope.descuentos.find(d=> d.id == desc.descuento);
                    listDescuentos.push({
                        id_descuento: desc.descuento,
                        monto: desc.monto,
                        nombre: d? d.nombre: '-'
                    });   
                }
            })

            let data = {
                caja_chica:{efectivo: $scope.caja_chica, debito: 0, transferencia: 0},
                total_ventas: {efectivo: $scope.getTotalEfectivo(), debito: $scope.getTotalDebito(), transferencia: $scope.getTotalTransferencia()},
                descuentos: listDescuentos,
                retiros: listPedidoData,
                total_final: $scope.getTotalFinal(),
                id_caja: $scope.id_caja
            }
            $scope.listarVentas(); 
            $scope.buscarPedidoDatos();
            /****ASQUI****/
            $http({
                                method : 'POST',
                                data:  $.param({idUsua:$scope.idUsuarioLogin, data: data, obserCaja:$scope.obserCaja}),
                                url : 'FunctionIntranet.php?act=updateCajaFin&tienda='+$scope.tiendaSeleccionada,
                                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

                }).success(function(data){
                    

                    $scope.listarInicioCaja();
                    $scope.imprimirFinCaja();
                    $scope.imprimirTermicaListadoProductosSalida();
                    $scope.syncCierreCaja(data);

                $('#divCierreCaja').hide();
                
                }).error(function(error){
                                console.log(error);
            
            });   

        } 
    }); 
}

$scope.syncCierreCaja = function (data) {
    $http({
        method : 'POST',
        data:  $.param({data: data}),
        url : 'FunctionIntranet.php?act=syncCierreCaja&tienda='+$scope.tiendaSeleccionada,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

    }).success(function(data){
        alertify.success("Cierre de Caja Sincronizado");
    }).error(function(error){
        alertify.success("Se produjo un error al sincronizar el cierre de caja. Intente nuevamente desde Resumen.");
    });  
}
        
        
        
}]);