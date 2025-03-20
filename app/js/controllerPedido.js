'use strict';
angular.module('progress.bar', [])
	.directive('ngProgressbar', function($timeout) {
		return {

			restrict : 'E',
			template : "<div class='progress'><div class='progress-bar progress-bar-{{type}}' role='progressbar' style='width:{{percent_complete}}%;'>{{current}} / {{max}} Kg</div></div>",
			replace : true,
			scope: {
                count:'@',
            	max: '@',
            	start: '@',
            	type: '@',
            	onStop: '&'
        	},

        	controller : ['$scope', '$timeout', function($scope, $timeout) {
        		$scope.onTimeout = function() {
                    $scope.percent_complete =0;
					$scope.startAt = $scope.count;
					$scope.current = $scope.startAt;
					$scope.mytimeout = $timeout($scope.onTimeout,1000);
					if($scope.startAt >= $scope.max) {
						$scope.stop();
						$scope.onStop();
					}
					$scope.percent_complete = Number(100 * $scope.current / $scope.max);
				}

				$scope.stop = function() {
					$timeout.cancel($scope.mytimeout);
				}
        	}],

			link : function (scope, elem, attrs, ctrl) {

				attrs.$observe('start', function(value) {
					console.log('observe start change');
					if(value === 'true') {
						scope.startAt = 0;
						scope.mytimeout = $timeout(scope.onTimeout,1000);
					}else if(value ==='false') {
						scope.stop();
					}	
				});
			}
		}
	});


angularRoutingApp.controller('controllerPedido', ['$scope', '$http','$cookies','$cookieStore', '$log','$timeout',
   
function ($scope, $http, $cookies, $cookieStore, $log, $timeout) {
        $scope.bloquearDesc         = false;
        $scope.bloquearNotaBole     = true;

        $scope.testDespachoCobro= "1";  
        $scope.cantidadkg       = 0;
        $scope.jornada          = "";
        $scope.entregaPed       = "";
        $scope.despachoPed      = "0";
        $scope.tipoComprador    = "Cliente";        
        $scope.tipos            = [];
        $scope.marcas           = [];
        $scope.descuent         = 0;
        $scope.aumento          = 0;
        $scope.codProd          = "";
        $scope.auxTipo = [];
        $scope.nombProd         = "";        
        $scope.dgVentas         = [];
        $scope.loadingProd      = false;
        $scope.loadingProdVacio = false;
        $scope.auxSelProd       = [];
        $scope.custom           = true; 
        $scope.customCookies    = false; 
        $scope.listDescVentas   = [];
        $scope.customVendedorTienda    = false; 
        $scope.auxSeldireccion = "";
        $scope.auxSelNombDirec = "";
        $scope.listDirecciones  = [];  
    


        $scope.comunas          = [{ "comuna" : "Arica"},
                                  { "comuna" : "Camarones" },
                                  { "comuna" : "General Lagos" },
                                  { "comuna" : "Putre" }];
                             
                             
    
   
        
        
        
   $scope.tipoDePago = {
      availableOptions: [
          
        {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
        {id: 1, name: 'EFECTIVO'},
        {id: 5, name: 'DEBITO'}/*,
        {id: 6, name: 'TRANSFERENCIA'}  */

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    }
        

       $scope.idCliente              = "";
       $scope.tipoCompradorPed       = "Cliente";  
       $scope.habilitarSelec         = true;
       
       $scope.customSelectCliente    = true;
       $scope.customSelectParticular = false;
       $scope.customSelectNuevo      = false;
    //   $scope.selPorcentaje          = ""; 
       $scope.customDespacho         = false;
    
        

    
    
    
        $scope.customlstDireccion     = true;
       $scope.testEntregaPed         = "";
       $scope.sectoresLists = [];
       $scope.conexionBaseDeDatos=1;
    
        
        $scope.tipoClientePed ="1";
        $scope.tipoClientePedNota ="1";
        
       $scope.observacionPedido = "";  
    
    
        var weekdays = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
       

        var mydate=new Date();
        var weekday = weekdays[mydate.getDay()];
        
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
    
    
        $scope.loading=false; 

    
    

  $scope.eliminarCookieSesion = function() {
    $cookieStore.remove('detalleProductoCookies');
       $scope.customCookies        = false;
  }
  
  
    $scope.mostrarCookieSesion = function() {
         var getmycookiesback = $cookieStore.get('detalleProductoCookies');        
        $log.info(getmycookiesback); 
        $scope.dgVentas=getmycookiesback;
        $scope.customCookies        = false;
        $cookieStore.remove('detalleProductoCookies');
        
  }
  
$scope.ventaPrecioDifPart = function(){
    
if($scope.auxSelProd.precioDifPr == "" || $scope.auxSelProd.precioDifPr ==0 ){    
       
    alertify.alert("Debe seleccionar un producto para poder realizar esta accion.");
    
} else {
    
     alertify.confirm("Desea aumentar el precio particular a la venta?", function (e) {
        if (e) {
            $scope.aumento = $scope.auxSelProd.precioDifPr;
            document.getElementById("aumento").value = $scope.auxSelProd.precioDifPr ;
        } 
    }); 
}   
}    




function TriggerAlertClose() {
    window.setTimeout(function () {
        $(".alertx").fadeTo(2000, 0).slideUp(2000, function () {
            $(this).remove();
        });
    }, 5000);
}



$scope.init = function () {
    
    
    
    
    
      document.getElementById('divSeleccionarCliente').style.display               = 'none';      
      document.getElementById('divSeleccionarProductos').style.display             = 'block';   
    
    
 if($scope.tipoIdLogin == '2'){
             $scope.bloquearDesc = true;
 }
    
    
  //  $scope.cantidadkg =100;
    
      $scope.timer_running = false;
          $scope.max_count = 400;

          $scope.startProgress = function() {
            $scope.timer_running = true;
          }

          $scope.stopProgress = function() {
            $scope.timer_running = false;
          }
/********************************
 0- Desarrollo
 1- Barros Arana
*****************/
    
    
$scope.conexionBaseDeDatos  = '0';
/*************************************************/     
if($scope.tipoIdLogin == '4' || $scope.tipoIdLogin == '8'){
    $scope.customVendedorTienda    = true; 
    $scope.conexionBaseDeDatos  = '1'   
}else if($scope.tipoIdLogin == '1'){        
     $scope.conexionBaseDeDatos  = '0';    
}
    
TriggerAlertClose();



if($cookieStore.get('detalleProductoCookies') != null){
      
     $scope.customCookies        = true;

}
    $scope.listarSectores();
    
    var diaDesp  = 5;
    var diaXDesp = parseInt(daym) + diaDesp;
    
    
      if(diaXDesp < 10){
          diaXDesp = "0"+diaXDesp;
      }
    
       

    $scope.testDespachoCobro="1";  
    
    document.getElementById('fechaEntrega').value= year+"-"+month+"-"+daym;    
    document.getElementById('fechaEntrega').max  = year+"-"+month+"-"+diaXDesp;
    document.getElementById('fechaEntrega').min  = year+"-"+month+"-"+daym;
    
    
    document.getElementById('fechaEntregaTienda').value= year+"-"+month+"-"+daym;    
    document.getElementById('fechaEntregaTienda').max  = year+"-"+month+"-"+diaXDesp;
    document.getElementById('fechaEntregaTienda').min  = year+"-"+month+"-"+daym;
   
    if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }
    
    
    $http.get('FunctionIntranet.php?act=tipoProducto&tienda='+$scope.tiendaSeleccionada).success(function(data) {
        console.log(data);

   $scope.tipos  = [];
   $scope.tipos2 = [];
   $scope.tipos  = data;
   $scope.tipos2 = data;
     


    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
    
    
};

        
      /*  
 $scope.selTipoPedidoGenerar(){
     $scope.tipoClientePedNota = tipPed;
 }      */    
        
$scope.change = function () {

    
            $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id:$scope.tipo.codCategoria}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                  $scope.marcas = [];                
                $scope.marcas = data;
          }).error(function(error){
                        console.log(error);
    
           });
    
    
    
};   
        
             
$scope.change2 = function () {
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id:$scope.tipo2.codCategoria}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                   $scope.marcas2 = [];                
                $scope.marcas2 = data;
          }).error(function(error){
                        console.log(error);
    
           });
    
    
    
             
};
        
        
$scope.listarProductos = function () {        
             var marcaProd     = ($scope.marca == undefined ? "": $scope.marca.codMarca);
             var categoriaProd = ($scope.tipo  == undefined ? "": $scope.tipo.codCategoria);
        
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarProductos&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({codProducto:$scope.codProd,
                             nombreProd:$scope.nombProd,
                             marcaProd:marcaProd,
                            categoriaProd:categoriaProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.productos = [];                
                $scope.productos = data;
          }).error(function(error){
                        console.log(error);
    
    });
};
        
        
        
$scope.limpiar = function () {
    $scope.codProd   = "";
    $scope.nombProd  = "";
    $scope.productos = "";
};
        
        
        
$scope.muestraPro = function (obj) {
    $scope.auxProd = obj;
};
        
		
$scope.limpiar = function () {
    $scope.codProd = "";
    $scope.nombProd="";
    $scope.productos ="";
};

                
$scope.selTipo = function () {
   $scope.loading=true; 

    $scope.auxTipo = $scope.tipo;
   /*  $http.get('FunctionIntranet.php?act=getDataMarca&id='+$scope.auxTipo.codCategoria).success(function(data) {
        console.log(data);
        $scope.marcas = [];
        $scope.marcas = data;
        $scope.loading=false; 

    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });*/
    
          $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id:$scope.auxTipo.codCategoria}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.marcas = [];
                $scope.marcas = data;
                $scope.loading=false; 
          }).error(function(error){
                        console.log(error);
    
    });
           
    
            
};
        
          
$scope.selMarca = function () {
  $scope.loading2=true; 
    $scope.listProd         = [];
    $scope.loadingProd      = true;
    $scope.loadingProdVacio = false;
    $scope.auxMarca         = $scope.marca;         

    var marcaProd           = ($scope.auxMarca == undefined ? "": $scope.auxMarca.codMarca);
    var categoriaProd       = ($scope.auxTipo == undefined ? "": $scope.auxTipo.codCategoria);
            
$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarProductos&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({codProducto:$scope.codProd,
                     nombreProd:$scope.nombProd,
                     marcaProd:marcaProd,
                    categoriaProd:categoriaProd}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
      $scope.loading2=false; 
            $scope.listProd = data;
            if( $scope.listProd.length <= 0){
                 $scope.loadingProdVacio = true;
            }
          $scope.loadingProd = false;
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
    
    
    
    
    
    


};
 
$scope.precioParticular = function () {    
//  var  precioParticular = $scope.auxSelProd.precioPart - Math.round($scope.auxSelProd.precioPart * ($scope.selPorcentaje/100));
  var  precioParticular = $scope.auxSelProd.precioPart ;
   return precioParticular;
}
        
 
        
$scope.montoIva = function () {    
  var  precioNetoMasIva = 0;
   if( angular.element(document.querySelector("#usua")).val() != ""){        
    /*     precioNetoMasIva =  Math.round(  ((($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento) * 0.19) + Math.round($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento))   -   ((($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento) * 0.19) + Math.round($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento)) * ($scope.selPorcentaje/100)       ) ; */
       precioNetoMasIva =  Math.round(  ((($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento) * 0.19) + Math.round($scope.auxSelProd.precioVenta - $scope.descuent + $scope.aumento))) ;
     }
   return precioNetoMasIva;
}
        
$scope.eliminarProduto = function (productIndex) {     
    $scope.dgVentas.splice(productIndex, 1);  
    $cookieStore.put('detalleProductoCookies',  $scope.dgVentas);
     $scope.cantidadkg= $scope.getPesosProductos();
                $scope.startProgress();

}
   

$scope.agregarSelProductos = function () {  

var banderaCodigo   = false;
var banderaCantidad = false;
var selectPrecio    = "";
    
for(var i=0; i < $scope.listDescVentas.length; i++){
     if($scope.codDesc == $scope.listDescVentas[i].codDesc ){
       banderaCodigo = true;           
       if($scope.cantidadProd >= $scope.listDescVentas[i].cantidad_minima ){           
           banderaCantidad = true;
           selectPrecio = $scope.listDescVentas[i].valor_neto; 
          }                   
     }      
}    
    
if($scope.codDesc   == null ||  $scope.codDesc   == 0){
     banderaCodigo   = true;
     banderaCantidad = true;
} 
    
if(banderaCodigo == true){
   
   
   if(banderaCantidad == true ){
       

           var length = $scope.dgVentas.length;
           var bandera=false;
           var objPro = [];
   
          for(var i = 0; i < length; i++) {
                 objPro = $scope.dgVentas;
                if(objPro[i].id == $scope.auxSelProd.id){
                    bandera = true;
                    break;
                }             
            } 
           
               if(document.prodSeleccion.producto.value==""){
                   alertify.alert("Tiene que seleccionar un producto para agregar a la lista."); 
                   return 0;
                }else if(angular.element(document.querySelector("#precio")).val()==null ){
                   alertify.alert("Producto sin precio.");             
                   return 0;
                }else if(angular.element(document.querySelector("#stock")).val()==null){
                   alertify.alert("Producto sin Stock.");             
                   return 0;
                }else if ($scope.cantidadProd == null || $scope.cantidadProd == ''){ 
                    alertify.alert("Tiene que ingresar la cantidad para agregar a la lista."); 
                    return 0; 
                }else if(parseInt($scope.cantidadProd) > parseInt($scope.auxSelProd.stockProd  - $scope.auxSelProd.salidasProd)){
                         if(parseInt($scope.cantidadProd) > 0){
                                 alertify.alert("Cantidad ingresada es mayor al stock."); 
                                 return 0;            
                          }else{
                                      alertify.alert("Cantidad ingresada es  menor a 0."); 
                                 return 0;            
                              
                          }
                              
                }else if(bandera==true){
                    alertify.alert("El producto ya existe en la lista."); 
                    return 0 ;
                }
             
    
      
               
               if($scope.descuent==null){
                 $scope.descuent=0;
               }
               if($scope.aumento==null){
                 $scope.aumento=0;
               }
         
         
              if(selectPrecio == ""){
                 
                  $scope.auxSelProd.precioVenta     =( Math.round($scope.auxSelProd.precioVenta) - Math.round($scope.descuent) +   Math.round($scope.aumento));
                  $scope.auxSelProd.totalProd       = Math.round($scope.cantidadProd) *  Math.round($scope.auxSelProd.precioVenta); 
               }else{
                 
                 $scope.auxSelProd.precioVenta     = selectPrecio;//con condicion
                 $scope.auxSelProd.totalProd       = Math.round($scope.cantidadProd) *  Math.round(selectPrecio); 
                 
                }
    
              
       
       /*- ( Math.round($scope.auxSelProd.precioVenta) - Math.round($scope.descuent) +   Math.round($scope.aumento)) * ($scope.selPorcentaje/100)*/;
              
       
       
       
              
       
       
               $scope.auxSelProd.descuento       = Math.round($scope.descuent);
               $scope.auxSelProd.aumento         = Math.round($scope.aumento);
             
               $scope.auxSelProd.cantidadProd    = $scope.cantidadProd;
               $scope.auxSelProd.idPrecioFactura = $scope.auxSelProd.idPrecioFactura;

    
               $scope.dgVentas.push($scope.auxSelProd);
       
               banderaCodigo   = false;
               banderaCantidad = false;
               selectPrecio    = "";
               $scope.codDesc ="";
               $scope.limpiarSelProd();
       
       
              $cookieStore.put('detalleProductoCookies',  $scope.dgVentas);
    
                $scope.cantidadkg= $scope.getPesosProductos();
                $scope.startProgress();
    
    
    
    
    
//$cookieStore['detalleProductoCookies']=  $scope.dgVentas;

               $scope.auxSelProd ="";
               $scope.cantidadProd ="";
               $scope.descuent=0;
               $scope.aumento=0;
               $scope.auxMarca = [];
               $scope.listProd = [];
               $scope.auxSelProd = [];
               $scope.selMarca();
}else{
    
    alertify.alert("Cantidad ingresada no cumple con la exigencia minima.");
    
}
    
    
    
}else{
    
    alertify.alert("Codigo de descuento no existe, favor volver a intentar.");
    
    
}
    
    
    
};
            
  
        $scope.limpiarSelProd = function(){
              $scope.listDescVentas = [];
               $scope.cantidadProd ="";
               $scope.descuent=0;
               $scope.aumento=0;
               $scope.auxSelProd ="";
            document.getElementById("aumento").value = 0;
               $scope.codDesc="";

        }
        
        
$scope.getPesosProductos = function (){
    
    var length = $scope.dgVentas.length;
    
    var objPro = [];
    var total = 0; 
    
        for(var i = 0; i < length; i++) {
                var pesos = 0;
                 objPro = $scope.dgVentas;
                    pesos = parseInt(objPro[i].cantidadProd) * parseInt(objPro[i].pesosProd);
                    total = total + pesos;
                             
         }
    
   
    return total;
    
}        
      
        
$scope.insertarProducto = function () {
var marcaProd     = ($scope.marca == undefined ? "": $scope.marca.codMarca);
var categoriaProd = ($scope.tipo == undefined ? "": $scope.tipo.codCategoria);
             
$http.get('FunctionIntranet.php?act=insertarProducto&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.codProd+'&codProducto='+$scope.nombProd+'&nombreProd='+marcaProd+'&marcaProd='+categoriaProd+'&categoriaProd='+categoriaProd+'&precioCosto='+categoriaProd+'&stockProd='+categoriaProd+'&accionProducto='+categoriaProd+'&ObservacionProd='+categoriaProd+'&precioVenta='+categoriaProd).success(function(data) {
                console.log(data);                
                $scope.productos = [];                
                $scope.productos = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};
        
        
$scope.getTotalNeto = function(){
    var total = 0;
    for(var i = 0; i < $scope.dgVentas.length; i++){
        var product = $scope.dgVentas[i];
        total += (product.cantidadProd * product.precioVenta);
    }
    
 //   total -= $scope.rcAbono;
    return total;
};    
        
        
$scope.getTotalIva= function(){
     var total = 0;
     var iva   = 0.19;
   for(var j = 0; j < $scope.dgVentas.length; j++){
        var product = $scope.dgVentas[j];
            total  += Math.round(product.cantidadProd * product.precioVenta);
    }
    var totalNeto = (Math.round(total));
    total =(Math.round(total) * iva) + Math.round(totalNeto);
    return total;
};    

        
$scope.getIva= function(){
    var total = 0;
    var iva   = 0.19;
    for(var j = 0; j < $scope.dgVentas.length; j++){
        var product = $scope.dgVentas[j];
        total += Math.round(product.cantidadProd * product.precioVenta);
    }
    total =Math.round(total) *  iva;
    return total;
};            
    
    
    
    
    
$scope.listDetaCobraClie = function(id_cliente){
    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarCobranzaDetaCliente&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idCliente:id_cliente}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
            

            $scope.cobranza_act = data[0].cobranza_act;
            $scope.limitado_act = data[0].limitado_act;
            $scope.compNetReal  = data[0].saldoPendiente;
            $scope.limtCompraNet= data[0].limite_compra;
            $scope.obserCobra   = data[0].obs_cobranza;
            $scope.creditoClienteCobra   = data[0].credito;

           
  }).error(function(error){
        console.log(error);

});  
    
    
}    
    
    
$scope.validarCobranzaCliente = function () {
 
    var bandera = "true" ;
    
    if($scope.cobranza_act != "1"){ // Estado Bloquero por cobranza ?

        if($scope.creditoClienteCobra > 0){ // Cliente tiene credito ?

            if($scope.limitado_act == "1"){ // Cliente tiene activado limite de compras ?

                             
                    var totaPedidoNeto = $scope.getTotalNeto();
                        totaPedidoNeto = parseInt(totaPedidoNeto) + parseInt($scope.compNetReal);
                 
                 
                 if(totaPedidoNeto > $scope.limtCompraNet){ 

                     alertify.alert("Cliente supera el limite de compras. Cualquier informacion con encargado de Cobranza."); 
                     bandera =  "false";
                     
                 }
                
               
             }
            
            
        }else{

            if( $scope.compNetReal > 0){ //Cliente sin Credito presenta deuda pendientes.
                alertify.alert("Cliente tiene asociados pendiente de pagos. Cualquier informacion con encargado de Cobranza."); 
                     bandera =  "false";
            }
           
        }
        
       
    }else{

        alertify.alert("Cliente bloqueado por pendiente pago. Cualquier informacion con encargado de Cobranza."); 
                     bandera =  "false";

    }
    
    if(bandera == "true"){
       $scope.validarCliente();
     }
    
    
    
}
    
    
    
        

$scope.validarCliente = function () {
      
 if($scope.tipoCompradorPed == "Cliente"){
     
      if(document.formCliente.rut.value==""){
            alertify.alert("Ingresar rut"); 
            document.formCliente.rut.focus();
                   return 0;
      }else if(document.formCliente.nombre.value==""){
           alertify.alert("Ingresar nombre"); 
            document.formCliente.nombre.focus();
                  return 0;
      /*}else if(document.formCliente.direccion.value==""){
           alertify.alert("Ingresar direccion"); 
            document.formCliente.direccion.focus();
                   return 0;*/
       /* }else if(document.formCliente.comuna.value==""){
           alertify.alert("Ingresar comuna"); 
            document.formCliente.comuna.focus();
                   return 0;*/
      }else if($scope.auxSeldireccion == ""){
             alertify.alert("Confirmar direccion."); 
                   return 0;
                    
          
      }else if($scope.jornada==""){
           alertify.alert("Seleccionar Jornada de despacho"); 
                   return 0;
      }else if($scope.entregaPed==""){
           alertify.alert("Seleccionar tipo de entrega"); 
                   return 0;
      /*}else if( $scope.sectoresLists.selectedOption.id=="0"    || $scope.sectoresLists.selectedOption.id=="" ){
           alertify.alert("Seleccionar sector"); 
                   return 0;*/
      }if($scope.tipoClientePedNota == "3" || $scope.tipoClientePedNota == "4" ){
          if($scope.observacionPedido == ""){
              if($scope.tipoClientePedNota == "3" ){
                alertify.alert("Favor ingresar observación señalando la factura o nota de pedido y a que se debe la bonificación. "); 
              }else if($scope.tipoClientePedNota == "4" ){
                alertify.alert("Favor ingresar observación señalando la factura o nota de pedido al cual corresponde el cambio de producto."); 
  
              }
              
              
              return 0;
             }
      }
     
   
         
        
     
     
 }else if($scope.tipoCompradorPed == "Nuevo"){
      if(document.formCliente.nombreClienteNuevo.value==""){
           alertify.alert("Ingresar nombre"); 
            document.formCliente.nombreClienteNuevo.focus();
                  return 0;
      }else if(document.formCliente.direccion.value==""){
           alertify.alert("Ingresar direccion"); 
            document.formCliente.direccion.focus();
                   return 0;
      }else if($scope.jornada==""){
           alertify.alert("Seleccionar Jornada de despacho"); 
                   return 0;
      }else if($scope.entregaPed==""){
           alertify.alert("Seleccionar tipo de entrega"); 
                   return 0;
      }else if( $scope.tipoDePago.selectedOption.id=="0"    || $scope.tipoDePago.selectedOption.id==""){
           alertify.alert("Seleccionar modo de pago"); 
                   return 0;
      }else if( $scope.sectoresLists.selectedOption.id=="0"    || $scope.sectoresLists.selectedOption.id=="" ){
           alertify.alert("Seleccionar sector"); 
                   return 0;
      }else if($scope.testDespachoCobro == ""){
               
                  alertify.alert("Seleccionar forma de pago por despacho."); 
                   return 0;
               
     }
     

    
     
 }else if($scope.tipoCompradorPed == "Particular"){
      if(document.formCliente.nombClieParticular.value==""){
           alertify.alert("Ingresar nombre"); 
            document.formCliente.nombClieParticular.focus();
                  return 0;
      }/*else if(document.formCliente.direccion.value==""){
           alertify.alert("Ingresar direccion"); 
            document.formCliente.direccion.focus();
                   return 0;*/
     
      else if($scope.auxSeldireccion == ""){
             alertify.alert("Confirmar direccion."); 
                   return 0;
                    
          
      
      }else if($scope.jornada==""){
           alertify.alert("Seleccionar Jornada de despacho"); 
                   return 0;
      }else if($scope.entregaPed==""){
           alertify.alert("Seleccionar tipo de entrega"); 
                   return 0;
      }else if( $scope.tipoDePago.selectedOption.id=="0"    || $scope.tipoDePago.selectedOption.id==""){
           alertify.alert("Seleccionar modo de pago"); 
                   return 0;
      }/*else if( $scope.sectoresLists.selectedOption.id=="0"    || $scope.sectoresLists.selectedOption.id=="" ){
           alertify.alert("Seleccionar sector"); 
                   return 0;
      }*/else if($scope.testDespachoCobro == ""){
               
                  alertify.alert("Seleccionar forma de pago por despacho."); 
                   return 0;
               
     }
     
     
     
 }
    
       $scope.confirmarPedido();
      
}


$scope.validarClienteTienda = function () {      
     
      if($scope.jornada==""){
           alertify.alert("Seleccionar Jornada de despacho"); 
                   return 0;
      }else if($scope.entregaPed==""){
           alertify.alert("Seleccionar tipo de entrega"); 
                   return 0;
      }    
       $scope.confirmarPedido();      
}
                                                                
        
$scope.generarPedido = function () {
         var f = new Date();
         var objPed = new Object();
         var arrayPro     = [];
         var arrayClie    = [];
         var arrayProd    = [];
        
         objPed.idUsuario = $scope.idUsuarioLogin;
         objPed.idCliente = "2";
         arrayPro.push(objPed);
       
         var objClie= new Object();
    
    
         if($scope.tipoCompradorPed == "Cliente"){
                objClie.id            = document.formCliente.idCliente.value;
                objClie.rut           = document.formCliente.rut.value;
                objClie.nombre        = document.formCliente.nombre.value;
             
             
              //  objClie.direccion     = document.formCliente.direccion.value;
   
                  
                objClie.direccion     = $scope.auxSelNombDirec;
                objClie.idSector      =$scope.idSectorCliente;
                objClie.tipoComprador = "Cliente";
                objClie.comuna        = "Arica";
             //   objClie.giro          = document.formCliente.giro.value;
                objClie.celular       = document.formCliente.celular.value;
                objClie.cobroDespacho = "0";    
                objClie.tipoDePago    = "4";
                objClie.entregaPed    = $scope.entregaPed.toString();
                objClie.idDireccion   = $scope.auxSeldireccion;
             
             
             
             
         }else if($scope.tipoCompradorPed == "Particular"){
                objClie.id            = document.formCliente.idCliente.value;
                objClie.rut           = "";
                objClie.nombre        = document.formCliente.nombClieParticular.value;
                objClie.direccion     = document.formCliente.direccion.value;
                objClie.tipoComprador = "Particular";
                objClie.comuna        = "";
               // objClie.giro          = "";
                objClie.celular       = document.formCliente.celular.value; 
                objClie.cobroDespacho = $scope.despachoPed.toString();     
                objClie.tipoDePago    = $scope.tipoDePago.selectedOption.id;
                objClie.entregaPed    = $scope.entregaPed.toString();
                objClie.direccion     = $scope.auxSelNombDirec;
                objClie.idSector      =$scope.idSectorCliente;
                objClie.idDireccion   = $scope.auxSeldireccion;


         }else if($scope.tipoCompradorPed == "Nuevo"){
                objClie.id            = "";
                objClie.rut           = "";
                objClie.nombre        = document.formCliente.nombreClienteNuevo.value;
                objClie.direccion     = document.formCliente.direccion.value;
                objClie.tipoComprador = "Nuevo";
                objClie.comuna        = "";

                //objClie.giro          = "";
                objClie.celular       = document.formCliente.celular.value; 
                objClie.cobroDespacho = $scope.despachoPed.toString();      
                objClie.tipoDePago    = $scope.tipoDePago.selectedOption.id;
                objClie.entregaPed    = $scope.entregaPed.toString();
                objClie.idSector      = $scope.sectoresLists.selectedOption.id;
                objClie.idDireccion   = "";


         }
                objClie.despacho      = angular.element(document.querySelector("#fechaEntrega")).val();
                objClie.jornada       = $scope.jornada.toString();
                objClie.observacioPed = $scope.observacionPedido.toString();
              
               
                objClie.tipoPedido    = $scope.tipoClientePedNota;


             
   
    
          arrayClie.push(objClie);
       
    
          for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.id           = $scope.dgVentas[i].id;
                     objDeta.precioVenta  = $scope.dgVentas[i].precioVenta;
                     objDeta.cantidadProd = $scope.dgVentas[i].cantidadProd;
                     objDeta.descuento    = $scope.dgVentas[i].descuento;
                     objDeta.aumento            = $scope.dgVentas[i].aumento;
                     objDeta.totalProd          = $scope.dgVentas[i].totalProd;
                     objDeta.idPrecioFactura    = $scope.dgVentas[i].idPrecioFactura;

                     arrayProd.push(objDeta);
           }
         
            var objCabe    = JSON.stringify(arrayPro);
            var objDeta    = JSON.stringify(arrayProd);
            var objCliente = JSON.stringify(arrayClie);
            
       
    
    
          
   


    
    
        $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=insertarPedido&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({objCabe:objCabe,
                         objDeta:objDeta,
                         objCliente:objCliente}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
             
      var respuesta = data;
                
                if(respuesta != "3"){
                    
                    
                    if($scope.tipoCompradorPed == "Particular" ){//Particular
                       
                       
                         if($scope.entregaPed == "2"){//Tienda
                                                 

                                var tipPag =  $scope.tipoDePago.selectedOption.id;
                                          if( tipPag != "5"){ //Distinto a transbank
                                                    //if($scope.creditoClie == '0' || $scope.creditoClie == '1' ){
                                                        $scope.generarBoleta(data);
                                                        
                                                 /* }else{
                                                       
                                                       setTimeout($.unblockUI, 1000); 
                                                    }   */                            
                                                    
                                              
                                            }else{
                                                
                                                setTimeout($.unblockUI, 1000); 
                                            
                                            }
                            }else{
                                 setTimeout($.unblockUI, 1000); 
                        }
                    }else{
                        setTimeout($.unblockUI, 1000); 
                    }
                    
                    $scope.testDespachoCobro="";
                    $scope.customDespacho         = false;
                    $scope.direccionCliente = "";
                    $scope.nombreCliente    = "";
                    $scope.rutCliente       = "";
                    $scope.giroCliente      = "";
                    $scope.comunaCliente    = "";    
                    $scope.telefonoCliente  = "";  
                    $scope.observacionPedido  = "";        
                    $scope.dgVentas      = [];
                    $scope.productos     = [];
                    $scope.auxSelProd    = [];
                    $scope.descuent      = 0;
                    $scope.auxClientes   = [];
                    $scope.listProd      = [];
                    $scope.tipoComprador = "Cliente";   
                    $scope.custom        = true;
                    $scope.nombreCliente ="";
                    $scope.idCliente        ="";
                    $scope.customSelectCliente    = true;
                    $scope.customSelectParticular = false;
                    $scope.customSelectNuevo      = false;
                    $scope.tipoCompradorPed       = "Cliente";
                    $scope.custom                 = true;
                    $scope.habilitarSelec         = true;
                    $scope.entregaPed             = "";
                    $scope.testEntregaPed.currentVal  = "";
                    $scope.jornada                = "";
                    $scope.test.currentVal        = "";                    
                    $scope.testDespachoCobro= "1"; 
                    $scope.despachoPed            = "0";      
                    $scope.tipoClientePedNota     = "1";
                    $scope.tipoClientePed         = "1";
                         $scope.auxSeldireccion  = "";
     $scope.auxSelNombDirec  = "";
     $scope.idSectorCliente  = "";
                    $scope.tipoDePago = [];
        
      $scope.tipoDePago = {
      availableOptions: [
          
        {id: 0, name: '--Seleccione Modo Pago--'},          
        {id: 1, name: 'EFECTIVO'},
        {id: 5, name: 'TRANSBANK'}/*,
        {id: 6, name: 'TRANSFERENCIA'}  */

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    } 
                    
                    
                   
                    
                        $cookieStore.remove('detalleProductoCookies');

                    
   //                 document.formCliente.nombre.value ="";
                    
                    alertify.success("Pedido generado con exito!. Numero Pedido: "+ data);                    
                   $scope.salirSeleccionarCliente();
                
        }else if(respuesta=="3"){
                    
                    setTimeout($.unblockUI, 1000); 
                    alertify.alert("Por motivos logisticos en bodega, se encuentra bloqueado la opcion para Generar Pedido. Para mas informacion contactar con el Administrador del Sistema.");

                }else if(respuesta=="0"){
                    
                    setTimeout($.unblockUI, 1000); 
                    alertify.error("Error al generar pedido, favor contactar con el Administrador del Sistema.");
                }
      
    }).error(function(error){
                        console.log(error);

    });
    

}; 
        

        
        
$scope.generarPedidoTienda = function () {
         var f = new Date();
         var objPed = new Object();
         var arrayPro     = [];
         var arrayClie    = [];
         var arrayProd    = [];
        
         objPed.idUsuario = $scope.idUsuarioLogin;
         objPed.idCliente = "2";
         arrayPro.push(objPed);
       
         var objClie= new Object();
    
    
         if($scope.tipoCompradorPed == "Cliente"){
                objClie.id            = "386";
                objClie.rut           = "190456862";
                objClie.nombre        = "Miloska Aranibar Castro";
                objClie.direccion     = "Agro Santa Maria Local 126";
                objClie.tipoComprador = "Cliente";
                objClie.comuna        = "Arica";
                objClie.celular       = "974224952";
                objClie.cobroDespacho = "0";    
                objClie.tipoDePago    = "4";
                objClie.entregaPed    = $scope.entregaPed.toString();//Transporte o retiro en tienda
         }
    
                objClie.despacho      = angular.element(document.querySelector("#fechaEntregaTienda")).val();
                objClie.jornada       = $scope.jornada.toString();
                objClie.observacioPed = $scope.observacionPedido.toString();
              
                objClie.idSector      = "3"; //Sector Centro
                objClie.tipoPedido    = "1"; //Tipo pedido con FACTURA


             
   
    
          arrayClie.push(objClie);
       
    
          for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.id           = $scope.dgVentas[i].id;
                     objDeta.precioVenta  = $scope.dgVentas[i].precioVenta;
                     objDeta.cantidadProd = $scope.dgVentas[i].cantidadProd;
                     objDeta.descuento    = $scope.dgVentas[i].descuento;
                     objDeta.aumento      = $scope.dgVentas[i].aumento;
                     objDeta.totalProd    = $scope.dgVentas[i].totalProd;
                     objDeta.idPrecioFactura    = $scope.dgVentas[i].idPrecioFactura;

                     arrayProd.push(objDeta);
           }
         
            var objCabe    = JSON.stringify(arrayPro);
            var objDeta    = JSON.stringify(arrayProd);
            var objCliente = JSON.stringify(arrayClie);
            
       
    
    
          
   


    
    
        $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=insertarPedido&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({objCabe:objCabe,
                         objDeta:objDeta,
                         objCliente:objCliente}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
             
      var respuesta = data;
                
                if(respuesta != "3"){
                    
                    
                    if($scope.tipoCompradorPed == "Particular" ){//Particular
                       
                       
                         if($scope.entregaPed == "2"){//Tienda
                                                 

                                var tipPag =  $scope.tipoDePago.selectedOption.id;
                                          if( tipPag != "5"){ //Distinto a transbank
                                                    //if($scope.creditoClie == '0' || $scope.creditoClie == '1' ){
                                                        $scope.generarBoleta(data);
                                                        
                                                 /* }else{
                                                       
                                                       setTimeout($.unblockUI, 1000); 
                                                    }   */                            
                                                    
                                              
                                            }else{
                                                
                                                setTimeout($.unblockUI, 1000); 
                                            
                                            }
                            }else{
                                 setTimeout($.unblockUI, 1000); 
                        }
                    }else{
                        setTimeout($.unblockUI, 1000); 
                    }
                    
                         $scope.testDespachoCobro="";
                   $scope.customDespacho         = false;
                    
                    $scope.direccionCliente = "";
                    $scope.rutCliente       = "";
                    $scope.giroCliente      = "";
                    $scope.comunaCliente    = "";    
                    $scope.telefonoCliente  = "";  
                    $scope.observacionPedido  = "";        
                    $scope.dgVentas      = [];
                    $scope.productos     = [];
                    $scope.auxSelProd    = [];
                    $scope.descuent      = 0;
                    $scope.auxClientes   = [];
                    $scope.listProd      = [];
                    $scope.tipoComprador = "Cliente";   
                    $scope.custom        = true;
                    $scope.nombreCliente ="";
                    $scope.idCliente        ="";
                    $scope.customSelectCliente    = true;
                    $scope.customSelectParticular = false;
                    $scope.customSelectNuevo      = false;
                    $scope.tipoCompradorPed       = "Cliente";
                    $scope.custom                 = true;
                    $scope.habilitarSelec         = true;
                    $scope.entregaPed             = "";
                    $scope.testEntregaPed.currentVal  = "";
                    $scope.jornada="";
                    $scope.test.currentVal="";                    
                    $scope.testDespachoCobro="1"; 
                    $scope.despachoPed="0";      
                    $scope.tipoClientePedNota = "1";
                    $scope.tipoClientePed    ="1";
                    
                    $scope.tipoDePago = [];
        
      $scope.tipoDePago = {
      availableOptions: [
          
        {id: 0, name: '--Seleccione Modo Pago--'},          
        {id: 1, name: 'EFECTIVO'},
        {id: 5, name: 'TRANSBANK'}/*,
        {id: 6, name: 'TRANSFERENCIA'}  */

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    } 
                    
                    
                   
                    
                        $cookieStore.remove('detalleProductoCookies');

                    
   //                 document.formCliente.nombre.value ="";
                    
                    alertify.success("Pedido generado con exito!. Numero Pedido: "+ data);
                    
                   
                
        }else if(respuesta=="3"){
                    
                    setTimeout($.unblockUI, 1000); 
                    alertify.alert("Por motivos logisticos en bodega, se encuentra bloqueado la opcion para Generar Pedido. Para mas informacion contactar con el Administrador del Sistema.");

                }else if(respuesta=="0"){
                    
                    setTimeout($.unblockUI, 1000); 
                    alertify.error("Error al generar pedido, favor contactar con el Administrador del Sistema.");
                }
      
    }).error(function(error){
                        console.log(error);

    });
    

}; 
         
        
        
$scope.initClientes = function () {
    //$scope.testEntregaPed.currentVal =1;
 

    
    
    $scope.listarSectores();
    
    var diaDesp  = 5;
    var diaXDesp = parseInt(daym) + diaDesp;
    
    
      if(diaXDesp < 10){
          diaXDesp = "0"+diaXDesp;
      }
    
       

    $scope.testDespachoCobro="1";  
    
    document.getElementById('fechaEntrega').value= year+"-"+month+"-"+daym;    
    document.getElementById('fechaEntrega').max  = year+"-"+month+"-"+diaXDesp;
    document.getElementById('fechaEntrega').min  = year+"-"+month+"-"+daym;


    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({rut:'',
                             nombre:'',
                            tipo:'Particular'}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  
    $scope.particular = [];

         
          $scope.particular = data;



          var arrobjParticular  = [];

          for(var i = 0; i < $scope.particular.length; i++) {
               var objParticular= new Object();
               objParticular = $scope.particular[i].nombre;                   
               arrobjParticular.push(objParticular); 
          }



           
         $('#nombDivPart .typeahead').typeahead({
              local: arrobjParticular
            }).on('typeahead:selected', function(event, selection) {
                  //alert(selection.value);  
             $scope.seleccionarClienteNombre(selection.value, 'Particular');

            $(this).typeahead('setQuery', selection.value);
         });            





          }).error(function(error){
                        console.log("error: "+data);

        });
    
    
    
  /*          $http.get('FunctionIntranet.php?act=listarClientes&rut=&nombre=&tipo=Particular').success(function(data) {
            console.log(data);
            $scope.particular = [];
            $scope.particular = data;



          var arrobjParticular  = [];

          for(var i = 0; i < $scope.particular.length; i++) {
               var objParticular= new Object();
               objParticular = $scope.particular[i].nombre;                   
               arrobjParticular.push(objParticular); 
          }



           
         $('#nombDivPart .typeahead').typeahead({
              local: arrobjParticular
            }).on('typeahead:selected', function(event, selection) {
                  //alert(selection.value);  
             $scope.seleccionarClienteNombre(selection.value, 'Particular');

            $(this).typeahead('setQuery', selection.value);
         });            






        }).
        error(function(data, status, headers, config) {
            console.log("error: "+data);
        });*/
    
    
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({rut:'',
                             nombre:'',
                             tipo:'Cliente'}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  

                      $scope.clientes = [];
                      $scope.clientes = data;
            
          var arrClie2  = [];

          for(var i = 0; i < $scope.clientes.length; i++) {
               var objClie2= new Object();
               objClie2 = $scope.clientes[i].nombre;                   
               arrClie2.push(objClie2); 
          }


          $scope.nombreCliente="";

         $('#nombreDiv .typeahead').typeahead({
              local: arrClie2
            }).on('typeahead:selected', function(event, selection) {
                  //alert(selection.value);  
             $scope.seleccionarClienteNombre(selection.value, 'Cliente');

            $(this).typeahead('setQuery', selection.value);
         });            


          }).error(function(error){
                        console.log("error: "+data);

        });
    
    
    
    
    
    
     /*   $http.get('FunctionIntranet.php?act=listarClientes&rut=&nombre=&tipo=Cliente').success(function(data) {
            console.log(data);
            $scope.clientes = [];
            $scope.clientes = data;



          var arrClie2  = [];

          for(var i = 0; i < $scope.clientes.length; i++) {
               var objClie2= new Object();
               objClie2 = $scope.clientes[i].nombre;                   
               arrClie2.push(objClie2); 
          }


          $scope.nombreCliente="";

         $('#nombreDiv .typeahead').typeahead({
              local: arrClie2
            }).on('typeahead:selected', function(event, selection) {
                  //alert(selection.value);  
             $scope.seleccionarClienteNombre(selection.value, 'Cliente');

            $(this).typeahead('setQuery', selection.value);
         });            






        }).
        error(function(data, status, headers, config) {
            console.log("error: "+data);
        });*/
    
    

    
       
};
        
    /*    
$scope.seleccionarClienteRut = function(obj){ 
    
        $scope.direccionCliente ="";
        $scope.nombreCliente    ="";
        $scope.rutCliente       ="";
        $scope.giroCliente      ="";
        $scope.comunaCliente    ="";
        $scope.telefonoCliente  ="";
        $scope.idCliente        ="";

        $http.get('FunctionIntranet.php?act=listarClientes&rut='+obj+'&nombre=').success(function(data) {
                console.log(data);
            
            $scope.auxClientes = [];
            $scope.auxClientes= data;
            
             $scope.direccionCliente =  $scope.auxClientes[0].direccion;
             $scope.nombreCliente    =  $scope.auxClientes[0].nombre;            
             $scope.rutCliente       =  $scope.auxClientes[0].rut; 
             $scope.giroCliente      =  $scope.auxClientes[0].giro; 
             $scope.comunaCliente    =  $scope.auxClientes[0].comuna; 
             $scope.telefonoCliente  =  $scope.auxClientes[0].telefono; 
             $scope.idCliente        =  $scope.auxClientes[0].id_cliente; 
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};  */
        
       
$scope.seleccionarClienteNombre = function(obj, objTipo){ 
     $scope.idSectorCliente  = "";
        $scope.listDirecciones = [];
        $scope.auxSeldireccion = "";
        $scope.auxSelNombDirec = "";    
        $scope.direccionCliente ="";
        $scope.nombreCliente    ="";
        $scope.rutCliente       ="";
        $scope.giroCliente      ="";
        $scope.comunaCliente    ="";
        $scope.telefonoCliente  ="";
        $scope.idCliente        ="";
        $scope.tipo_comprador   ="";
    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({nombre:obj,
                             tipo:objTipo}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  

                     $scope.auxClientes = [];
                     $scope.auxClientes= data;
            
                  //   $scope.direccionCliente =  $scope.auxClientes[0].direccion;
                     $scope.nombreCliente    =  $scope.auxClientes[0].nombre;            
                     $scope.rutCliente       =  $scope.auxClientes[0].rut; 
                     $scope.giroCliente      =  $scope.auxClientes[0].giro; 
                     $scope.comunaCliente    =  $scope.auxClientes[0].comuna; 
                     $scope.telefonoCliente  =  $scope.auxClientes[0].telefono; 
                     $scope.idCliente        =  $scope.auxClientes[0].id_cliente; 
                     $scope.tipo_comprador   =  $scope.auxClientes[0].tipo_comprador;
                     $scope.idSectoresC      =  $scope.auxClientes[0].id_sector;
          
                    $scope.sectoresLists = {
                      availableOptions: $scope.listSectores,                      
                      selectedOption: {id:  $scope.auxClientes[0].id_sector} 
                    }

            
                    $scope.listarDireccionesCliente($scope.idCliente);
                    $scope.listDetaCobraClie($scope.idCliente);
                    

          }).error(function(error){
                        console.log("error: "+data);

        });

    
    
    
    
    
    
    
};    
    
    

$scope.selDireccionClie = function (obj) { 
     $scope.auxSeldireccion  = "";
     $scope.auxSelNombDirec  = "";
     $scope.direccionCliente = "";
     $scope.idSectorCliente  = "";

    
   if( obj.id_sector == "0" || obj.id_sector == "" || obj.direccion ==""){    
       alertify.alert("Cliente sin direccion o Sector. Porfavor contactar con el administrador o supervisor de transporte."); 
   }else{
       $scope.auxSeldireccion = obj.id;    
       $scope.auxSelNombDirec = obj.direccion;
       $scope.direccionCliente= obj.direccion;
       $scope.idSectorCliente = obj.id_sector;

   }


};     
    
    
    
$scope.listarDireccionesCliente = function(idCliente){
      $scope.listDirecciones  = [];  
        $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=listarDireccionesCliente&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id_cliente:idCliente}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
      
        
         $scope.listDirecciones =  data;
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
    
    
}
       

$scope.enviarPedido = function () {
    $scope.nombreCliente="";
    var length = $scope.dgVentas.length;        
    if(length>0){   
         
        if(length < 21 ){
        
           if($scope.tipoIdLogin == '4' || $scope.tipoIdLogin == '8'){
              $("#myModalPrecio").modal();              
            } else {
             
          /*    $("#myModalCliente").modal();               
                    $scope.initClientes(); */
                
                
                      document.getElementById('divSeleccionarCliente').style.display               = 'block';      
                     document.getElementById('divSeleccionarProductos').style.display             = 'none';   
                
                
             
                
                
            }
            

        }else{
             alertify.alert("Maximo 20 tipos de producto, para poder generar un pedido"); 
        }
        
      
    
    
    }else{
         alertify.alert("Para poder generar un pedido necesita agregar productos a la lista."); 
    }    
    
};
 
        

$scope.validarStockProd = function(){
         var arrayProd    = [];

        
          for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.id           = $scope.dgVentas[i].id;
                     objDeta.cantidadProd = $scope.dgVentas[i].cantidadProd;
                     arrayProd.push(objDeta);
           }
    
    
     var arrProd = JSON.stringify(arrayProd);
    
              
                $http({
                         method : 'POST',
                         url : 'FunctionIntranet.php?act=consultarProdEnStock&tienda='+$scope.tiendaSeleccionada,
                         data:  $.param({arrProd:arrProd}),
                                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data);               
             
                            var respuesta = data.charAt(data.length-1);
                    
                    
                    if(respuesta=="1"){
                        
                        if($scope.tipoIdLogin == '4' || $scope.tipoIdLogin == '8'){
                               $scope.generarPedidoTienda();
                            }else{
                               $scope.generarPedido();
                        }
         
                        
                        
                     }else{
                         alertify.alert("Los siguientes productos se encuentran sin Stock:"+data+ "<br/>Favor volver a seleccionar los productos, ingresando nuevamente el Tipo y la Marca.");
                         setTimeout($.unblockUI, 1000); 
                     }


                    }).error(function(error){
                                        console.log(error);
                    setTimeout($.unblockUI, 1000); 

                    });  
};        
        
        
        

$scope.confirmarPedido = function () { 
    $scope.flagProdAcgt = false;
        
    /*if($scope.tipoIdLogin == '2'){//Tipo usuario
        if($scope.tipo_comprador == 'Mascotero') //Tipo Cliente
          {   
               for(var i = 0; i < $scope.dgVentas.length; i++){
                         if($scope.dgVentas[i].prod_venta_act == '0'){//Producto activo
                                  $scope.flagProdAcgt = true;
                             break;
                         }
               }
           }
        
    };*/
    
 if($scope.flagProdAcgt == false){   
    alertify.confirm("Esta seguro que desea generar pedido ?", function (e) {
        if (e) {
            
            $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
             } }); 
            
            
            /*

            
              if($scope.tipoIdLogin == '4' || $scope.tipoIdLogin == '8'){
                     $("#myModalClienteTienda").modal("hide");
              }else{
                     $("#myModalCliente").modal("hide");
            
              }*/
            
            
            
            $scope.validarStockProd();
            
            
        } else {
            // user clicked "cancel"
        }
    });    
    }else{
        setTimeout($.unblockUI, 1000); 
                     alertify.alert("No se puede generar pedido, favor contactar con el supervisor de ventas.");
    }
};


$scope.selTipoPedido = function(te){
     alert(te);
}

 $scope.selTipoPedidoGenerar = function(tipPed){
     $scope.tipoClientePedNota = tipPed;
 
     
 }  


 $scope.selTipoComprador = function (rdo) {   
    $scope.sectoresLists = [];
    $scope.tipoDePago = [];
        
      $scope.tipoDePago = {
      availableOptions: [
          
        {id: 0, name: '--Seleccione Modo Pago--'},          
        {id: 1, name: 'EFECTIVO'},
        {id: 5, name: 'TRANSBANK'}/*,
        {id: 6, name: 'TRANSFERENCIA'}  */

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    } 
     
    $scope.direccionCliente ="";
    $scope.nombreCliente    ="";
    $scope.rutCliente       ="";
    $scope.giroCliente      ="";
    $scope.comunaCliente    ="";
    $scope.telefonoCliente  ="";
    $scope.idCliente        ="";   
    $scope.listDirecciones  = []; 
    $scope.auxSeldireccion         = "";
    $scope.auxSelNombDirec         =  "";
    $scope.direccionCliente = "";
    $scope.idSectorCliente  = "";
     
     
    $scope.test.currentVal        = "";                    
    $scope.jornada        = "";                    
    $scope.entregaPed        = "";                    
    $scope.testEntregaPed.currentVal  = "";

          
     
     
     if(rdo=="Cliente"){           
         
            $scope.tipoClientePedNota      = 1;
            $scope.tipoClientePed          ="1";
            $scope.tipoCompradorPed        = rdo;
            $scope.custom                  = true;
            $scope.habilitarSelec          = true;            
            $scope.customSelectCliente     = true;
            $scope.customSelectParticular  = false;
            $scope.customSelectNuevo       = false;
            $scope.customDespacho          = false;
            $scope.customlstDireccion      = true;

             

      }else if(rdo=="Particular" ){
             $scope.tipoClientePed ="2";
             $scope.tipoClientePedNota      = 2;
             $scope.tipoCompradorPed       = rdo; 
             $scope.custom                 = false;
             $scope.habilitarSelec         = true;
             $scope.customSelectCliente    = false;
             $scope.customSelectParticular = true;
             $scope.customSelectNuevo      = false;
             $scope.customDespacho         = false;
             $scope.customlstDireccion         = true;

    }else if(rdo=="Nuevo"){
             $scope.tipoCompradorPed           = rdo; 
             $scope.tipoClientePedNota      = 2;
             $scope.tipoClientePed ="3";
             $scope.custom                 = false;
             $scope.habilitarSelec         = false;
             $scope.customSelectCliente    = false;
             $scope.customSelectParticular = false;
             $scope.customSelectNuevo      = true;
             $scope.customDespacho         = false;
             $scope.customlstDireccion         = false;
       
    }
   $scope.listarSectores();   
   $scope.initClientes();     
};
        


        
  $scope.test = {};
   $scope.testEntregaPed = {};

  
 // $scope.test.currentVal='true';
  
  $scope.setTransactionDebit = function(value){
    $scope.test.currentVal   = value;   
    $scope.jornada           = value;  
  };
        
        
  $scope.setTransactionEntregaPed    = function(value){  
      
    $scope.testEntregaPed.currentVal = value;   
    $scope.entregaPed                = value;  
      
  };

        
  $scope.setDespacho=function(value){
      
    $scope.testDespachoCobro       = value;   
    $scope.despachoPed             = value;  
      
  };        
        
        

$scope.listarSectores = function(){        
    var arrayDeta     = [];
    var objDeta= new Object();

    $http.get('FunctionIntranet.php?act=listarSector&tienda='+$scope.tiendaSeleccionada).success(function(data) {
            console.log(data);         
           
           $scope.listSectores = data;
                objDeta= new Object();
                objDeta.name      = "--Seleccionar Sector--";
                objDeta.id        = 0; 
                arrayDeta.push(objDeta);
     
          
              for (var i = 0; i <    data.length; i++) {   
                        objDeta= new Object();
                        objDeta.name      = data[i].name;
                        objDeta.id        = data[i].id; 
                        arrayDeta.push(objDeta);
              }
           
           
           
           
           
           $scope.sectoresLists = {
              availableOptions: arrayDeta,                      
              selectedOption: {id: 0} 
            }
           
            }).
            error(function(data, status, headers, config) {
                console.log("error al listar sectores: "+data);
            });
    
};

        
 $scope.precioUsuaDesc = function(){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=precioUsuaDesc&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idUsua:$scope.idUsuarioLogin                       
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="0"){
                    alertify.custom = alertify.extend("custom");
                    alertify.custom("Para poder realizar descuento, favor contactar con supervisora de Ventas.");
                    $scope.descuent = 0;
                }
          

          }).error(function(error){
                console.log(error);
        });
}
 
 
$scope.generarBoleta = function (data) {
    
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
    
    
   /* var rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);*/
   
    
    var TpoDTE        = "39";
    var FchEmision    = daym+"-"+month+"-"+year;
    var Rut           = "55555555-5";/*res+'-'+digito;*/
    var RznSoc        = "Persona Natural"; //$scope.listPedidosIndex.nombre;
    var Giro          = "Persona Natural";
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

    
    

   for (i = 0; i <  $scope.dgVentas.length; i++) {   
    total = 0;   
    var objDeta= new Object();
    total = $scope.dgVentas[i].precioVenta;    
    var totalNeto = (Math.round(total));
    total = (Math.round(total) * iva) + Math.round(totalNeto);       
       
       
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.dgVentas[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.dgVentas[i].cantidadProd;
            objDeta.Precio      = Math.round(total);
            objDeta.Codigo      =  $scope.dgVentas[i].id;
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
                                        idPedido:data,
                                        TipoServicio:'3'
										
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
              
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                 /* alertify.alert(RznSoc+" Factura Generada!");
                  alertify.set({ delay: 10000 });*/
                   setTimeout($.unblockUI, 1000);     
              /*   $scope.buscarPedido('');         
                 $scope.verDetallePedidoSalir();
                 $scope.detPedido           = [];     
                 $scope.listPedidosIndex    = [];  */   
                    
                    
                }else{
                    /*var arrayNubox = data.split(';;;');
                    alertify.error(arrayNubox[3]);*/
                }
          
               setTimeout($.unblockUI, 1000);   
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });
};   
 

 $scope.guadarProductoFotos = function(){
      var arrObj = [];
      var objProd= new Object();

      objProd.foto          =  $scope.fotos;
      objProd.idProd        =  $scope.idProdSel; 

    
      
      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
     
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=saveProductoModFoto&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objProdutos:objCabe}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Producto modificado con exito!");
                $scope.precioPart ="";
                
            }else{
                alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");
            }
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
};
    
    
    
    
    
 $scope.selProductos = function (obj) {    
    $scope.auxSelProd = obj;
   // $scope.selPorcentaje = obj.porcentaje;
     $scope.listarDescuentoVentas(obj);
};    
    
    
 $scope.selProductosCodDesc = function (obj) {   
    var descuto = $scope.auxSelProd.precioVenta - obj.valor_neto;     
    $scope.descuent = Number(descuto);     
    $scope.codDesc = Number(obj.codDesc);
   
};     
           
    
    

 $scope.listarDescuentoVentas = function(obj){
     
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=listarDescuentosVentas&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id_prod:obj.id}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
      
        
         $scope.listDescVentas =  data;
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
};    
    
    
    
    
    
$scope.salirSeleccionarCliente =  function(){
      document.getElementById('divSeleccionarCliente').style.display               = 'none';      
      document.getElementById('divSeleccionarProductos').style.display             = 'block';   
}    
    
    
    
   /*
      $('#dgDirecciones').on('click', 'tbody tr', function(event) {
  $(this).addClass('highlight').siblings().removeClass('highlight');
});
   */  
          

}]);