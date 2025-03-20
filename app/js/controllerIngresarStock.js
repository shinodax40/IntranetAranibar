angularRoutingApp.controller('controllerIngresarStock', ['$scope', '$http',
    function ($scope, $http) {
       $scope.$parent.title = "Ingresar Stock";
       $scope.$parent.img = "img/iconset-addictive-flavour-set/png/document-plaid-pen.png";
       $scope.$parent.showTopToggle = false;
        
        
        $scope.tipos = [];
        $scope.marcas = [];
        
        $scope.codProd = "";
        $scope.nombProd = "";        
        $scope.dgVentas = [];
        $scope.lisEscala = [];
          $scope.auxClientes = [];
        $scope.textRut    = "";
        $scope.numFactura = "";
        $scope.idProveedor ="";
        $scope.tipoClientePed    ="1";
        $scope.customSelectParticular=true;
              $scope.customProveedorNombre=true;
                $scope.customNuevoNombre=false;

        

$scope.init = function () {    
    
  document.getElementById('divPedido').style.display    = 'none'; 
  document.getElementById('divInforme').style.display   = 'block'; 

    
    if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }    
    $http.get('FunctionIntranet.php?act=tipoProducto&tienda='+$scope.tiendaSeleccionada).success(function(data) {
        console.log(data);
   $scope.tipos = [];
   $scope.tipos2 = [];
   $scope.tipos = data;
   $scope.tipos2 = data;

    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
};

$scope.change = function () {
            $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.tipo.codCategoria).success(function(data) {
                console.log(data);        
                 

                $scope.marcas = [];                
                $scope.marcas = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};   
        
             
$scope.change2 = function () {
            $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.tipo2.codCategoria).success(function(data) {
                console.log(data);           
                $scope.marcas2 = [];                
                $scope.marcas2 = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });              
};
        
        
$scope.listarProductos = function () {
             var marcaProd = ($scope.marca == undefined ? "": $scope.marca.codMarca);
             var categoriaProd = ($scope.tipo == undefined ? "": $scope.tipo.codCategoria);
             
            $http.get('FunctionIntranet.php?act=listarProductos&codProducto='+$scope.codProd+'&nombreProd='+$scope.nombProd+'&marcaProd='+marcaProd+'&categoriaProd='+categoriaProd).success(function(data) {
                console.log(data);
                
                $scope.productos = [];                
                $scope.productos = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
        };
        
        
        
         $scope.limpiar = function () {
            $scope.codProd = "";
            $scope.nombProd="";
            $scope.productos ="";
        };
        
        
        
        $scope.muestraPro = function (obj) {
            $scope.auxProd = obj;
        };
        
		 //LIMPIAR
$scope.limpiar = function () {
            $scope.codProd = "";
            $scope.nombProd="";
            $scope.productos ="";
};
        
        
        
$scope.selTipo = function () {
      $scope.loading=true; 
    
            $scope.auxTipo = $scope.tipo;
         $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.auxTipo.codCategoria).success(function(data) {
                console.log(data);                
                $scope.marcas = [];                
                $scope.marcas = data;
              $scope.loading=false;
                                 $scope.lisEscala = [];

            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
            
};
        
                

          
$scope.selMarca = function () {
      $scope.loading2=true; 
            $scope.auxMarca = $scope.marca;           
            
             var marcaProd = ($scope.auxMarca == undefined ? "": $scope.auxMarca.codMarca);
             var categoriaProd = ($scope.auxTipo == undefined ? "": $scope.auxTipo.codCategoria);
             

    
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
    
    $scope.listEscalaProd();
            
            
};
        
        
   /*          data:  $.param({categoria:$scope.codProd,
                             marca:$scope.nombProd}),*/
        
$scope.listEscalaProd =  function (){
         var marcaProd = ($scope.marca == undefined ? "": $scope.marca.codMarca);
         var categoriaProd = ($scope.tipo == undefined ? "": $scope.tipo.codCategoria);
    
      $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listEscalaProducto&tienda='+$scope.tiendaSeleccionada,
           data:  $.param({
                             idMarca:marcaProd,
                             idCategoria:categoriaProd
                            }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
          
          $scope.lisEscala = data;
      
                  
                
            }).error(function(error){
                        console.log(error);
                    $scope.loadingProd = false;
    
            }); 
     
    
}      


        

        
$scope.eliminarProduto = function (productIndex) {
     
           $scope.dgVentas.splice(productIndex, 1);
         
};
   

$scope.agregarSelProductos = function () {  
               $scope.cantProdConObs     = "";
               $scope.cantProdPendiente  = "";
               $scope.cantProdEnDesp     = "";
               $scope.cantProdEnBodega   = "";
               $scope.cantProdRechazado  = "";
 
    
           var length = $scope.dgVentas.length;
           var bandera=false;
           var objPro = [];
            for (var i = 0; i < length; i++) {
                 objPro = $scope.dgVentas;
                if(objPro[i].id == $scope.auxSelProd.id){
                    bandera = true;
                    break;
                }             
            } 
           
               if(document.prodSeleccion.producto.value==""){
                   alertify.alert("Tiene que seleccionar producto para agregar a la lista."); 
                   return 0;
                }else if ($scope.cantidadStock=="0"){ 
                    alertify.alert("Tiene que ingresar la cantidad para agregar a la lista."); 
                    return 0; 
                }else if(bandera==true){
                    alertify.alert("El producto ya existe en la lista."); 
                    return 0 ;
                }
               //$scope.auxSelProd.totalProd    = 0:
               //$scope.auxSelProd.precioVenta  = $scope.precioSotck;
               //$scope.auxSelProd.totalProd    = parseInt($scope.cantidadProd) *  parseInt($scope.auxSelProd.precioVenta); 
               $scope.auxSelProd.foto          = $scope.auxSelProd.foto;    

               $scope.auxSelProd.valor_neto    = Math.round($scope.valorNeto);
               $scope.auxSelProd.precio_venta  = Math.round(parseInt($scope.precioVenta / 1.19));
               $scope.auxSelProd.descuento     = $scope.descuento;    
               $scope.auxSelProd.cantidadProd  = $scope.cantidadStock;
               $scope.auxSelProd.precioCosto   = Math.round(((($scope.valorNeto) / $scope.cantidadStock) - (($scope.valorNeto) / $scope.cantidadStock)*($scope.descuento/100)));
               $scope.auxSelProd.totalIngresos = Math.round(((($scope.valorNeto) / $scope.cantidadStock) - (($scope.valorNeto) / $scope.cantidadStock)*($scope.descuento/100))* $scope.cantidadStock);
               $scope.auxSelProd.diferenciaPrecio = 	Math.round(($scope.precioVenta/ 1.19) - ($scope.auxSelProd.precioVenta/ 1.19));	
	           $scope.auxSelProd.bodega    =  $scope.bodega;                   
               $scope.auxSelProd.cod_barra =  $scope.cod_barra;    
               $scope.dgVentas.push($scope.auxSelProd);
               $scope.auxSelProd    = "";
          //   $scope.cantidadProd  = "";
          //   $scope.precioSotck   = 0;
               $scope.auxMarca      = [];
               $scope.listProd      = [];
               $scope.auxMarca      = [];
               $scope.listProd      = [];
               $scope.auxSelProd    = [];
    
               $scope.descuento     ="";
               $scope.valorNeto     ="";
               $scope.precioVenta   ="";
               $scope.cantidadStock ="";
               $scope.selMarca();
    };
            
        
      
        
$scope.insertarProducto = function () {
var marcaProd = ($scope.marca == undefined ? "": $scope.marca.codMarca);
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
        
        
$scope.getTotal = function(){
    var total = 0;
    for(var i = 0; i < $scope.dgVentas.length; i++){
        var product = $scope.dgVentas[i];
        total += (product.cantidadProd * product.precioVenta);
    }
    return total;
};    
        

$scope.validarCliente = function () {
    
    
    if($scope.tipoClientePed=="1"){  

        if(document.formCliente.nombre.value==""){
                alertify.alert("Seleccionar nombre proveedor para proceder."); 
                document.formCliente.nombre.focus();
                    return 0;
        }else if(document.formCliente.numFactura.value==""){
                alertify.alert("Ingresar Numero Factura"); 
                document.formCliente.numFactura.focus();
                    return 0;
        }else if(document.formCliente.idProveedor.value==""){
                alertify.alert("Seleccionar nombre proveedor para proceder."); 
                document.formCliente.idProveedor.focus();
                    return 0;
        }  
            
            
    }else if($scope.tipoClientePed=="2"){  
        
        if(document.formCliente.textRutNuevo.value==""){
                alertify.alert("Ingresar rut"); 
                document.formCliente.rut.focus();
                    return 0;
        }else if(document.formCliente.nombProveedorNuevo.value==""){
                alertify.alert("Ingresar nombre"); 
                document.formCliente.nombre.focus();
                    return 0;
        }else if(document.formCliente.numFactura.value==""){
                alertify.alert("Ingresar Numero Factura"); 
                document.formCliente.numFactura.focus();
                    return 0;
        }  
            
     }

    
       $scope.confirmarPedido();
      
}
                   

        
$scope.generarPedido = function () {
    
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
    
    
         var f = new Date();
         var objPed = new Object();
         objPed.idUsuario ="1";
         objPed.idCliente ="2";
         var arrayPro = [];
         var arrayClie = [];
         var arrayProd = [];
         arrayPro.push(objPed);

if($scope.tipoClientePed =="1"){

        var objClie= new Object();
        objClie.idAccion    = "1";//document.formCliente.idCliente.value;
        objClie.rut         = document.formCliente.rut.value;
        objClie.nombre      = document.formCliente.nombre.value;
        objClie.numFactura  = document.formCliente.numFactura.value;
        objClie.idProveedor =  $scope.idProveedor;

       
        arrayClie.push(objClie);

}else{

        var objClie= new Object();
        objClie.idAccion    = "2";//document.formCliente.idCliente.value;
        objClie.rut         = document.formCliente.textRutNuevo.value;
        objClie.nombre      = document.formCliente.nombProveedorNuevo.value;
        objClie.numFactura  = document.formCliente.numFactura.value;
        objClie.idProveedor =  "";

        arrayClie.push(objClie);




}

           for(var i = 0; i < $scope.dgVentas.length; i++){
                var objDeta = new Object();

                     objDeta.id                = $scope.dgVentas[i].id;
                     objDeta.cantidadProd      = $scope.dgVentas[i].cantidadProd;
                     objDeta.descuento         = $scope.dgVentas[i].descuento;
                     objDeta.valor_neto        = $scope.dgVentas[i].valor_neto;
                     objDeta.precio_venta      = $scope.dgVentas[i].precio_venta;
                     objDeta.diferenciaPrecio  = $scope.dgVentas[i].diferenciaPrecio;
                     objDeta.bodega            = $scope.dgVentas[i].bodega;
                     objDeta.codBarra          = $scope.dgVentas[i].cod_barra;

              arrayProd.push(objDeta);
            }


        var objCabe = JSON.stringify(arrayPro);
        var objDeta = JSON.stringify(arrayProd);
        var objCliente = JSON.stringify(arrayClie);

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
             url : 'FunctionIntranet.php?act=insertarStock&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({objCabe:objCabe,
                             objDeta:objDeta,
                             objCliente:objCliente,
                             obserbIng:$scope.observacionIngreso}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
               
			 
          var respuesta = data.charAt(data.length-1);

            if(respuesta=="1"){
                $scope.dgVentas   = [];
                $scope.productos  = [];
                $scope.auxSelProd = [];
                $scope.listProd   = [];
                alertify.success("Ingreso generado con exito!");
				 setTimeout($.unblockUI, 1000); 
                
				
            }else{
                alertify.error("Error al ingresar Stock, favor contactar con el Administrador del Sistema.");
                 setTimeout($.unblockUI, 1000);    
            }
			 
			 
			 
          }).error(function(error){
                        console.log(error);
    
    });
	
	
            
}; 
        
        
$scope.initClientes = function () {
            $http.get('FunctionIntranet.php?act=listarProveedores&rut=&nombre=&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);
             
                $scope.clientes= data;

               var length = $scope.clientes.length;
              
               var arrClie = [];
               var arrClie2 = [];
               for (var i = 0; i < length; i++) {
                   var objClie= new Object();
                   var objClie2= new Object();

                   objClie = $scope.clientes[i].rut;
                   objClie2 = $scope.clientes[i].nombre;

                   
                   arrClie.push(objClie);  
                   arrClie2.push(objClie2); 
                }
                
 
             $('#nombreDiv .typeahead').typeahead({                
                  local: objClie2
                }).on('typeahead:selected', function(event, selection) {
                        
                 $scope.seleccionarClienteNombre(selection.value);
                 
                $(this).typeahead('setQuery', selection.value);
                });
                


 
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};
        
     
        
        
 $scope.selTipoComprador = function (rdo) {   

     
     
       if(rdo=="PROVEEDOR"){  
                $scope.tipoClientePed    ="1";
                $scope.customSelectParticular =true;
                $scope.customProveedorNombre=true;
                $scope.customNuevoNombre=false;
                           $scope.numFactura ="";
                           $scope.initClientes();   
           
      }else if(rdo=="NUEVO" ){
                $scope.tipoClientePed   ="2";
                $scope.customSelectParticular =false;
                $scope.textRut ="";
                $scope.idProveedor ="";
                $scope.customProveedorNombre=false;
                $scope.customNuevoNombre=true;
          
                $scope.numFactura ="";


    }
   
};
        
        
        
        
        
        
        
        
$scope.seleccionarClienteRut = function(obj){ 
        $http.get('FunctionIntranet.php?act=listarProveedores&rut='+obj+'&nombre=&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);
            
            $scope.auxClientes = [];
            $scope.auxClientes= data;
            
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};  
        
       
$scope.seleccionarClienteNombre = function(obj){ 
    $scope.textRut = "";
    $scope.numFactura = "";
    
    document.formCliente.rut.value=="";
        $http.get('FunctionIntranet.php?act=listarProveedores&tienda='+$scope.tiendaSeleccionada+'&rut=&nombre='+obj+'').success(function(data) {
                console.log(data);
            
            $scope.auxClientes   = [];
            $scope.auxClientes   = data;
            
            $scope.textRut       = data[0].rut;
            $scope.idProveedor   = data[0].id_proveedor;
            $scope.nombProveedor = data[0].nombre;

            
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};    
       

$scope.enviarPedido = function () { 
    var length = $scope.dgVentas.length;        
    if(length>0){   
        $scope.initClientes();
        $("#myModalCliente").modal();
    }else{
         alertify.alert("Para poder ingresar Stock necesita agregar productos a la lista."); 
    }    
    
}
 

$scope.confirmarPedido = function () { 
    alertify.confirm("¿ Esta seguro que desea ingresar factura ?", function (e) {
        if (e) {
            $scope.generarPedido();
             $("#myModalCliente").modal("hide");
        } else {
            // user clicked "cancel"
        }
    });    
}

 

$scope.ingresarMenu = function(){
var usua = angular.element(document.querySelector("#usua")).val();
var pass = angular.element(document.querySelector("#contra")).val();
    
if(usua.length!=0 || pass.length!=0){

    $http.get('FunctionIntranet.php?act=consultarUsuario&tienda='+$scope.tiendaSeleccionada+'&usua='+usua+'&pass='+pass).success(function(data) {
                 console.log(data);

                 $scope.loginUsua = [];
                 $scope.loginUsua = data;
            
 if($scope.loginUsua.length==0){
                  alertify.alert("La Clave Secreta ingresada no es correcta. Inténtelo de nuevo, verificando las mayúsculas y minúsculas");
              }else{        
                  
             $("#myModallogininicial").modal("hide");
        
             $scope.idUsuarioLogin     =$scope.loginUsua[0].id;
             document.getElementById('nombreLogin').innerHTML=$scope.loginUsua[0].nombre;
             document.getElementById('rolLogin').innerHTML=$scope.loginUsua[0].nombre_tipo;
         
                  
}                
             

            }). error(function(data, status, headers, config) {
                            console.log("error: "+data);
    });
        
        
}else{
   alertify.alert("Favor ingresar Usuario y Contraseña");   
}
}








$scope.getTotalNeto = function(){
    var total = 0;
    for(var i = 0; i < $scope.dgVentas.length; i++){
        var product = $scope.dgVentas[i];
        total += (product.cantidadProd * product.precioCosto);
    }
    return total;
};    
        
        
$scope.getTotalIva= function(){
     var total = 0;
     var iva   = 0.19;
   for(var j = 0; j < $scope.dgVentas.length; j++){
        var product = $scope.dgVentas[j];
            total  += parseInt(product.cantidadProd * product.precioCosto);
    }
    var totalNeto = (parseInt(total));
    total =(parseInt(total) * iva) + parseInt(totalNeto);
    return total;
};    

        
$scope.getIva= function(){
    var total = 0;
    var iva   = 0.19;
    for(var j = 0; j < $scope.dgVentas.length; j++){
        var product = $scope.dgVentas[j];
        total += parseInt(product.cantidadProd * product.precioCosto);
    }
    total =parseInt(total) *  iva;
    return total;
};   
        

$scope.selProductos = function (obj) {
    $scope.auxSelProd = obj;
	$scope.auxSelProd.precioVenta = Math.round(parseInt(obj.precioVenta * 1.19)); 
    //$scope.precioSotck= parseInt(obj.precioVenta);
    $scope.buscarObsProd(obj);
};     
        
        
$scope.buscarObsProd = function (obj){
    
      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarProductosInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd:obj.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.producto = data;
        
         if(data.length != 0){
        
               $scope.nombreProd = data[0].nombreProd;
               $scope.idProd     = data[0].id;
               $scope.ingresos   = data[0].stockProd;             
               $scope.salidas    = data[0].salidasProd;
               $scope.cod_barra  = data[0].cod_barra;
               $scope.bodega     = data[0].bodega;

               $scope.cantProdConObs     = data[0].cantProdConObs;
               $scope.cantProdPendiente  = data[0].cantProdPendiente;
               $scope.cantProdEnDesp     = data[0].cantProdEnDesp;
               $scope.cantProdEnBodega   = data[0].cantProdEnBodega;
               $scope.cantProdRechazado  = data[0].cantProdRechazado;

             
             

             
         }else{
             alertify.alert("El Producto no existe."); 
      
         }
  }).error(function(error){
        console.log(error);

});
    
    
}


$scope.verListadoEnDetalle =  function(stdo){
    
          document.getElementById('divPedido').style.display                    = 'block';     
          document.getElementById('divInforme').style.display                    = 'none'; 
    
          $scope.verDetallePed(stdo);

}


$scope.verDetallePed = function(stdo){
    
     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleObsPed&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idEstado:stdo,
                     idProd:$scope.auxSelProd.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.listInformeTranspDeta = data;
  }).error(function(error){
        console.log(error);

});
    
    
    
}



$scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divInforme').style.display             = 'block';  
              
}


$scope.confirmarActiveProd = function(prodDet, value){
	var length = $scope.dgVentas.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
       if($scope.dgVentas[i].id == prodDet.id){
              $scope.dgVentas[i].bodega  = value;		   
           break;
       }             
    }
};	
        
        

        

$scope.generarNotaPedido = function () {
 
 
    
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
    
   for (var i = 0; i <    $scope.dgVentas.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.dgVentas[i].id;
            objDeta.nombreProd     =  $scope.dgVentas[i].nombreProd;
            objDeta.cantidad       =  $scope.dgVentas[i].cantidadProd;
            arrayDeta.push(objDeta);
 }
    

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'detalle':jsonData};
        
 
   
        var config = {
            url: 'FunctionIntranet.php?act=generarCotizacionOCPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="Orden_de_compra"+fechaActual+".pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
         
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
        

$scope.methodVenta = function(prodDet, modInpur){
	var length = $scope.dgVentas.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.dgVentas;
       
       if(objProd[i].id_prod == prodDet.id_prod){
              $scope.dgVentas[i].cod_barra  = (modInpur);		   
           break;
       }             
    }
};	
        
        
        
$scope.confirmarBorrarEscala = function (objEsc) { 
    alertify.confirm("Esta seguro que desea eliminar producto con escala ?", function (e) {
        if (e) {
            $scope.eliminarEscala(objEsc);
             $("#myModalCliente").modal("hide");
        } else {
            // user clicked "cancel"
        }
    });    
}        
        



        
$scope.eliminarEscala = function (obj){
    
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=borrarProdEscala&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idEscala:obj.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               

         var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Escala eliminado con exito!");
                $scope.listEscalaProd();
                
            }else{
                alertify.error("Error al eliminar escala, favor contactar con el Administrador del Sistema.");
            }
        
      
  }).error(function(error){
        console.log(error);

});
    
    
}
/* $idEscala, $pescala, $descripEsc, $catMinima */

$scope.updateEscalaProd = function (obj){
    
    $http({
     method : 'POST',
        url : 'FunctionIntranet.php?act=actualizarProdEscala&tienda='+$scope.tiendaSeleccionada,
        data:  $.param({idEscala:obj.id,
                        pescala:obj.pescala,
                        descripEsc:obj.descripcion,
                        catMinima:obj.cantidad_minima
                       
                       
                       }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               

         var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Actualizado con exito!");
                $scope.confirmarEstadoModificar(obj, 0);
                
            }else{
                alertify.error("Error al actualizar escala, favor contactar con el Administrador del Sistema.");
            }
        
      
  }).error(function(error){
        console.log(error);

});
    
    
}


        
$scope.confirmarEstadoModificar = function(prodDet, value){
    
	var length = $scope.lisEscala.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.lisEscala[i].id == prodDet.id){
              $scope.lisEscala[i].estado_mod  = value;	
           break;
       }             
    }
};	  
        



$scope.insertProdEscala =  function (){
         var marcaProd = ($scope.marca == undefined ? "": $scope.marca.codMarca);
         var categoriaProd = ($scope.tipo == undefined ? "": $scope.tipo.codCategoria);
    
      $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=insertarEscalaProd&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idProd:$scope.idProdMod,
                             idMarca:marcaProd,
                             idCategoria:categoriaProd
                            }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
                   var respuesta = data.charAt(data.length-1);

          if(respuesta=="1"){                
                alertify.success("Ingresado con exito!");
              $scope.listEscalaProd();
              $scope.idProdMod ="";
                
            }else{
                alertify.error("Error al Ingresado escala, favor contactar con el Administrador del Sistema.");
            }
                  
                
            }).error(function(error){
                        console.log(error);
                    $scope.loadingProd = false;
    
            }); 
     
    
}      
        

        
        
}]);