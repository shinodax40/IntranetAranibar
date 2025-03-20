'use strict';

/* Controllers */

angular.module('myApp.controllers', [])

   .controller('AppCtrl', ['$scope', '$location', function ($scope, $location) {
        $scope.isActive = function (viewLocation) {
            return viewLocation === $location.path();
        };

        $scope.title = "Sistema Control de Inventario";
        $scope.subNav1 = 0;
        $scope.img = "img/iconset-addictive-flavour-set/png/screen_aqua_glossy.png";
        $scope.showTopToggle = false;
    }])

    .controller('TypeCtrl', ['$scope', function ($scope) {
        $scope.$parent.title = "Typography";
        $scope.$parent.img = "img/iconset-addictive-flavour-set/png/cutting_pad.png";
        $scope.$parent.showTopToggle = false;
    }])

.controller('controllerIngresarStock', ['$scope', '$http',
    function ($scope, $http) {
        $scope.tipos = [];
        $scope.marcas = [];
        
        $scope.codProd = "";
        $scope.nombProd = "";        
        $scope.dgVentas = [];
        
           $('#myModallogininicial').modal({
            backdrop: 'static',
            keyboard: false
        })
        $scope.init = function () {
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
             
            $http.get('FunctionIntranet.php?             act=listarProductos&codProducto='+$scope.codProd+'&nombreProd='+$scope.nombProd+'&marcaProd='+marcaProd+'&categoriaProd='+categoriaProd).success(function(data) {
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
            $scope.auxTipo = $scope.tipo;
         $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.auxTipo.codCategoria).success(function(data) {
                console.log(data);                
                $scope.marcas = [];                
                $scope.marcas = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
            
};
        
                

          
$scope.selMarca = function () {
            $scope.auxMarca = $scope.marca;           
            
             var marcaProd = ($scope.auxMarca == undefined ? "": $scope.auxMarca.codMarca);
             var categoriaProd = ($scope.auxTipo == undefined ? "": $scope.auxTipo.codCategoria);
             
            $http.get('FunctionIntranet.php?             act=listarProductos&codProducto='+$scope.codProd+'&nombreProd='+$scope.nombProd+'&marcaProd='+marcaProd+'&categoriaProd='+categoriaProd).success(function(data) {
                console.log(data);                
                $scope.listProd = [];                
                $scope.listProd = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
            
            
};
        
        
$scope.selProductos = function (obj) {
    $scope.auxSelProd = obj;             
    $scope.precioSotck= parseInt(obj.precioVenta);
};     
        
        
$scope.eliminarProduto = function (productIndex) {
     
           $scope.dgVentas.splice(productIndex, 1);
         
};
   

$scope.agregarSelProductos = function () {           
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
                   alertify.alert("Tiene que seleccionar un producto para agregar a la lista."); 
                   return 0;
                }else if (document.prodForm.cantidad.value==""){ 
                    alertify.alert("Tiene que ingresar la cantidad para agregar a la lista."); 
                    document.prodForm.cantidad.focus();
                    return 0; 
                }else if(bandera==true){
                    alertify.alert("El producto ya existe en la lista."); 
                    return 0 ;
                }
              // $scope.auxSelProd.totalProd = 0:
               $scope.auxSelProd.precioVenta =    $scope.precioSotck;
               $scope.auxSelProd.totalProd   =parseInt($scope.cantidadProd) *  parseInt($scope.auxSelProd.precioVenta); 
               $scope.auxSelProd.cantidadProd=$scope.cantidadProd;
               $scope.dgVentas.push($scope.auxSelProd);
               $scope.auxSelProd ="";
               $scope.cantidadProd ="";
                $scope.precioSotck=0;
               $scope.auxMarca = [];
               $scope.listProd = [];
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
      
    
      if(document.formCliente.rut.value==""){
            alertify.alert("Ingresar rut"); 
            document.formCliente.rut.focus();
                   return 0;
      }else if(document.formCliente.nombre.value==""){
           alertify.alert("Ingresar nombre"); 
            document.formCliente.nombre.focus();
                  return 0;
      }else if(document.formCliente.numFactura.value==""){
           alertify.alert("Ingresar Numero Factura"); 
            document.formCliente.numFactura.focus();
                   return 0;
      }    
	
       $scope.confirmarPedido();
      
}
                   

        
$scope.generarPedido = function () {
             var f = new Date();
             var objPed = new Object();
             objPed.idUsuario ="1";
             objPed.idCliente ="2";
             var arrayPro = [];
             var arrayClie = [];
             var arrayProd = [];
             arrayPro.push(objPed);
       
       
       
            var objClie= new Object();
            objClie.id = document.formCliente.idCliente.value;
            objClie.rut = document.formCliente.rut.value;
            objClie.nombre = document.formCliente.nombre.value;
            objClie.numFactura = document.formCliente.numFactura.value;
            arrayClie.push(objClie);
       
    
    
               for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.id = $scope.dgVentas[i].id;
                     objDeta.precioVenta = $scope.dgVentas[i].precioVenta;
                     objDeta.cantidadProd = $scope.dgVentas[i].cantidadProd;

                    arrayProd.push(objDeta);
                }
           
         
            var objCabe = JSON.stringify(arrayPro);
            var objDeta = JSON.stringify(arrayProd);
            var objCliente = JSON.stringify(arrayClie);
            
             
            $http.get('FunctionIntranet.php?act=insertarStock&tienda='+$scope.tiendaSeleccionada+'&objCabe='+objCabe+'&objDeta='+objDeta+'&objCliente='+objCliente+'').success(function(data) {
                console.log(data);
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    $scope.dgVentas = [];
                    $scope.productos = [];
                    $scope.auxSelProd = [];
                    $scope.listProd = [];
                    alertify.success("Pedido generado con exito!");
                }else{
                    alertify.error("Error al ingresar Stock, favor contactar con el Administrador del Sistema.");
                }
              
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
            
}; 
        
        
$scope.initClientes = function () {
            $http.get('FunctionIntranet.php?act=listarProveedores&tienda='+$scope.tiendaSeleccionada+'&rut=&nombre=').success(function(data) {
                console.log(data);
             
                $scope.clientes= data;

               var length = $scope.clientes.length;
              
               var arrClie = [];
               var arrClie2 = [];
               for (i = 0; i < length; i++) {
                   var objClie= new Object();
                   var objClie2= new Object();

                   objClie = $scope.clientes[i].rut;
                   objClie2 = $scope.clientes[i].nombre;

                   
                   arrClie.push(objClie);  
                   arrClie2.push(objClie2); 
                }
                
  
             $('#rutDiv .typeahead').typeahead({                
                  local: arrClie
                }).on('typeahead:selected', function(event, selection) {
                        
                 $scope.seleccionarClienteRut(selection.value);
                 
                $(this).typeahead('setQuery', selection.value);
                });
                

             $('#nombreDiv .typeahead').typeahead({
                  local: arrClie2
                }).on('typeahead:selected', function(event, selection) {
                        
                 $scope.seleccionarClienteNombre(selection.value);
                 
                $(this).typeahead('setQuery', selection.value);
             });            

 
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
        };
        
        
$scope.seleccionarClienteRut = function(obj){ 
        $http.get('FunctionIntranet.php?act=listarProveedores&tienda='+$scope.tiendaSeleccionada+'&rut='+obj+'&nombre=').success(function(data) {
                console.log(data);
            
            $scope.auxClientes = [];
            $scope.auxClientes= data;
                
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
            });
};  
        
       
$scope.seleccionarClienteNombre = function(obj){ 
        $http.get('FunctionIntranet.php?act=listarProveedores&tienda='+$scope.tiendaSeleccionada+'&rut=&nombre='+obj+'').success(function(data) {
                console.log(data);
            
            $scope.auxClientes = [];
            $scope.auxClientes= data;
                
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
    alertify.confirm("¿Esta seguro que desea guardar Stock?", function (e) {
        if (e) {
            $scope.generarPedido();
             $("#myModalCliente").modal("hide");
        } else {
            // user clicked "cancel"
        }
    });    
}


/*
$('#rut').Rut({
  on_error: function(){ alertify.alert('Rut incorrecto. Para ingresar, digite  RUT (sin puntos ni guión). Ejemplo: 149801247 '); }
});

*/
  

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
    
        
}])
 

.controller('controllerListadoPedido', ['$scope', '$http', function ($scope, $http) {
    $scope.$parent.title = "Listado de Pedidos";
    $scope.$parent.img = "img/iconset-addictive-flavour-set/png/document-plaid-pen.png";
    $scope.$parent.showTopToggle = false;
    
    
    $scope.listProd=    [];  
     $scope.loading=false; 
    $scope.idPedido='';
    $scope.detPedido = [];
    $scope.listPedidos=  [];  
    $scope.unirPedidos=  [];  
    $scope.idUsuarioLogin = '';
     $scope.tipoUsuarioLogin  = '';    
         $('#myModallogininicial').modal({
            backdrop: 'static',
            keyboard: false
        })    


function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}
         
         
$scope.init = function () {
    
      $http.get('FunctionIntranet.php?act=listarPedidos&tienda='+$scope.tiendaSeleccionada+'&rut=&nombre=juan&desde=&hasta=').success(function(data) {
           console.log(data);     
           $scope.listPedidos = data;    

            }).
            error(function(data, status, headers, config) {
                console.log("error listar pedidos: "+data);
      });

};
        
    
$scope.cargarPedido= function (pedidoIndex, accion) {
    

    var rutAux    = pedidoIndex.rut;    
    var digVirif  = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido=[]; 
    $scope.btnGenerarFactura   = false;
    $scope.listPedidosIndex = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
    $scope.rutModificar =  pedidoIndex.rut;
    $scope.razonMod     =  pedidoIndex.nombre;
    $scope.giroMod      =  pedidoIndex.giro;
    $scope.direccionMod =  pedidoIndex.direccion;
    $scope.idPedido     =  pedidoIndex.id_pedido;    
    
    
    if(pedidoIndex.folio!=null){ 
       $scope.btnGenerarFactura = true;     
    }
    
    

    $http.get('FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada+'&idPedido='+pedidoIndex.id_pedido).success(function(data) {
                console.log(data);                
                $scope.detPedido = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error Listar Detalle Pedido: "+data);
    }); 

}


$scope.anularPedido = function () {

    $http.get('FunctionIntranet.php?act=anularPedido&tienda='+$scope.tiendaSeleccionada+'&idPedido='+$scope.idPedido).success(function(data) {
                console.log(data);
                
                 var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                
                    alertify.success("Pedido anulado con exito!");
                    $scope.init();
                }else{
                    alertify.error("Error al anular pedido, favor contactar con el Administrador del Sistema.");
                }
          
            }).
            error(function(data, status, headers, config) {
                console.log("error al Anular Pedido: "+data);
    }); 

}


$scope.confirmarAnularPedido = function () { 
    alertify.confirm("¿Esta seguro que desea anular pedido?", function (e) {
        if (e) {
            $scope.anularPedido();
            $("#myModalModificarPedido").modal("hide");
            
        } 
    });    
}


$scope.getTotalPedido = function(){
       var total = 0;
     var iva = 0.19;
   for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += parseInt(product.cantidad * product.precio_vendido);
    }
       var totalNeto = (parseInt(total));
               total = (parseInt(total) * iva) + parseInt(totalNeto);
    return parseInt(total);
};    

        
$scope.getTotalPedidoCantidad = function(){
    var can = 0;
    for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        can += parseInt(product.cantidad);
    }
    return can;
};    
        
        
$scope.getNeto= function(){
 
    
    
     var total = 0;
    for(var i = 0; i < $scope.detPedido.length; i++){
        var product = $scope.detPedido[i];
        total += parseInt(product.cantidad * product.precio_vendido);
    }
    return parseInt(total);
    
};    

        
$scope.getIva= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += parseInt(product.cantidad * product.precio_vendido);
    }
    total =parseInt(total) * (iva);
    return parseInt(total);
};    


$('#rutMod').Rut({
  on_error: function(){ alertify.alert('Rut incorrecto.'); },
  format_on: 'keyup'
});

        
$scope.modificarPedido = function(){
    var arrayClie = [];
    
    var objClie= new Object();
            objClie.idCliente = $scope.listPedidosIndex.id_cliente;
            objClie.rut       = $.Rut.quitarFormato($scope.rutModificar);
            objClie.nombre    = document.prodFormMof.razonMod.value;
            objClie.direccion = document.prodFormMof.direccionMod.value;
            objClie.giro      = document.prodFormMof.giroMod.value;
   arrayClie.push(objClie);
    
 var objClie= JSON.stringify(arrayClie);    
    
  $http.get('FunctionIntranet.php?act=modificarPedido&tienda='+$scope.tiendaSeleccionada+'&arrCliente='+objClie).success(function(data) {
                console.log(data);
               $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Pedido guardado con exito!");
                    $scope.init ();
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
              
            }).
            error(function(data, status, headers, config) {
                console.log("error: "+data);
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
    var Comuna        = $scope.listPedidosIndex.comuna;
    var Direccion     = $scope.listPedidosIndex.direccion;
    var Email         = "";
    var IndTraslado   = "";
    var ComunaDestino = "";
    var arrayDeta     = [];
    

 //$.blockUI({ message: "Generando Factura Electronica..." });  

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            message: 'Generando Factura Electronica...',
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

   for (var i = 0; i <    $scope.detPedido.length; i++) {   
       
   var objDeta= new Object();
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.detPedido[i].nombreProd.substr(0,35);  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.detPedido[i].cantidad;
            objDeta.Precio      =  $scope.detPedido[i].precio_vendido;
            objDeta.Codigo      =  $scope.detPedido[i].codProducto;

   arrayDeta.push(objDeta);
       
   }
    
 var objDetalles= JSON.stringify(arrayDeta);    
   
    
      $http.get('FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada+'&TpoDTE='+TpoDTE+'&FchEmision='+FchEmision+'&Rut='+Rut+'&RznSoc='+RznSoc+'&Giro='+Giro+'&Comuna='+Comuna+'&Direccion='+Direccion+'&Email='+Email+'&IndTraslado='+IndTraslado+'&ComunaDestino='+ComunaDestino+'&Detalles='+objDetalles).success(function(data, status, headers, config) {
           
          
           var arrayNubox = data.split(';;;');
          alertify.alert("N° Folio:"+arrayNubox[0]);
          
           if(arrayNubox[1]!=""){
          
           $scope.insertarFacturasNubox(arrayNubox);
           $scope.obtenerPDF(arrayNubox[0],arrayNubox[1]);
           }else{
            
               alertify.error(arrayNubox[3] );
               
           }
          
          
          setTimeout($.unblockUI, 1000); 
       
            }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al generar  factura: "+data);
      });

};
 

 
$scope.obtenerPDF = function (folio, identi) {
       
var res = identi.slice(3, 8);
    
var parametroIdent = parseInt(identi); 
            
var objectToSerialize={'identificador':parametroIdent, 
                       'folio':folio};    
            
var config = {
                url: 'FunctionIntranet.php?act=getPDF',
                data: $.param(objectToSerialize),
                method: 'POST',
                responseType: 'arraybuffer',
                 headers: {
                 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                 }
}
            
            
        $http(config).then(function successCallback(data) {              

        var linkElement = document.createElement('a');
        try {
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var filename ="factura_"+fechaActual+"_"+cad+".pdf";          
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

        
        

        
        
        
$scope.insertarFacturasNubox = function (arrayNubox) {
   
      var objDetalles= JSON.stringify(arrayNubox);    
      $http.get('FunctionIntranet.php?act=insertarFacturaNubox&tienda='+$scope.tiendaSeleccionada+'&arrayFac='+objDetalles+'&numPedido='+$scope.listPedidosIndex.id_pedido).success(function(data, status, headers, config) {
                 alertify.set({ delay: 10000 });
                 alertify.success("Factura Emitida con exito!");
            }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al generar  factura: "+data);
      });

};
        
        
 $scope.isRowSelected = function (idPedido) {
    
  for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected === true){
            alertify.alert("Sleccionado" + idPedido);
        }
    }
     
 }
       
 $scope.toggleSelection = function(item){
   
    item.isRowSelected = !item.isRowSelected;
}       
        
$scope.isAnythingSelected = function () {
    var pedido="";
    var banderaSel=false;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){
            banderaSel=true;
            pedido += ','+$scope.listPedidos[i].id_pedido;
        }
    }
    
 if(!banderaSel){     
     alertify.alert("Para generar una Nota, favor seleccionar pedidos");     
 }else{    
    
  $http.get('FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada+'&pedido='+pedido.substr(1)).success(function(data, status, headers, config) {
                   $scope.unirPedidos = data;
                   $("#myModalJoinPedido").modal();
            }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al unir Pedidos: "+data);
      });    
 }
};        

        
$scope.getTotalPedidoJoin = function(){
     var total = 0;
     var iva = 0.19;
   for(var j = 0; j < $scope.unirPedidos.length; j++){
        var product = $scope.unirPedidos[j];
        total += parseInt(product.cantidad * product.precioVenta);
    }
    
    var totalNeto = (parseInt(total));
    total =(parseInt(total) * iva) + parseInt(totalNeto);
    return parseInt(total);
    
};  
        
$scope.getNetoJoin= function(){
 
    
       var total = 0;
    for(var i = 0; i < $scope.unirPedidos.length; i++){
        var product = $scope.unirPedidos[i];
        total += parseInt(product.cantidad * product.precioVenta);
    }
    return parseInt(total);
    
};    

        
$scope.getIvaJoin= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.unirPedidos.length; j++){
        var product = $scope.unirPedidos[j];
        total += parseInt(product.cantidad * product.precioVenta);
    }
    total =parseInt(total) * (iva);
    return parseInt(total);
};    
      
        
        
$scope.generarReportePDF = function () {
    
    
    
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


var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
            objDeta.codProducto    =  $scope.unirPedidos[i].codProducto;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.fecha          =  daym+"/"+month+"/"+year;
   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=gerexpPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="nota_pedido_"+fechaActual+"_"+cad+".pdf";          
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
        
        
    
$scope.isAnythingSelectedFctura = function () {
     $scope.pedidoSelec="";
    var banderaPed= true;
     $scope.pedido="";
    var auxPedido = [];
    auxPedido =  $scope.listPedidos;
    var banderaUnir=false;
    
     for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){ 
            banderaUnir = true;
            for(var x=0; x < auxPedido.length; x++ ){  
                  if(auxPedido[x].isRowSelected == true){ 
                     if($scope.listPedidos[i].id_cliente != auxPedido[x].id_cliente){
                              banderaPed=false;
                         break;
                     }   
                  }
            }
        }
    }
    
if(banderaUnir){
 if(banderaPed){

      for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].isRowSelected == true){

                 $scope.pedido += ','+$scope.listPedidos[i].id_pedido;
                 $scope.pedidoSelec += '- '+$scope.listPedidos[i].id_pedido;
            }
        }
      $http.get('FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada+'&pedido='+ $scope.pedido.substr(1)).success(function(data, status, headers, config) {
                       $scope.unirPedidos = data;
                         $("#myModalUnirPedido").modal();
                }

                ).
                error(function(data, status, headers, config) {
                    console.log("error al unir Pedidos: "+data);
          });    
 }else{     
  alertify.alert("Para poder realizar esta accion los pedidos seleccionados tienen que ser del mismo cliente.");   
 }
}else{
      alertify.alert("Para poder realizar esta accion, seleccionar pedidos");   

}
};   

        
$scope.confirmarFacturaJoin = function () { 
  if($scope.unirPedidos.length>20){        
     alertify.alert("Para poder generar Factura Electronica en el detalle del pedido no tiene que se mas de 20 productos.");
  }else{
       alertify.confirm("¿Esta seguro que desea generar Factura Electronica a los pedidos seleccionados?", function (e) {
        if (e) {
            $scope.generarFacturaJoin();
             $("#myModalUnirPedido").modal("hide");
        }
    });    
  }
}       
                
        
$scope.generarFacturaJoin = function () {
    
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
    
    
    
    alertify.alert($scope.listPedidos[0].rut);
    var rutAux=$.Rut.quitarFormato($scope.listPedidos[0].rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    var TpoDTE ="33";
    var FchEmision =daym+"-"+month+"-"+year;
    var Rut = res+'-'+digito;
    var RznSoc =$scope.listPedidos[0].nombre;
    var Giro = $scope.listPedidos[0].giro;
    var Comuna =$scope.listPedidos[0].comuna;
    var Direccion =$scope.listPedidos[0].direccion;
    var Email ="";
    var IndTraslado ="";
    var ComunaDestino ="";
    var arrayDeta = [];
    

 $.blockUI({ message: "Generando Factura Electronica..." });     //20 productos

   for (i = 0; i <    $scope.unirPedidos.length; i++) {   
       
   var objDeta= new Object();
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.unirPedidos[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.unirPedidos[i].cantidad;
            objDeta.Precio      =  $scope.unirPedidos[i].precioVenta;
            objDeta.Codigo      =  $scope.unirPedidos[i].codProducto;
   arrayDeta.push(objDeta);
       
   }
    
 var objDetalles= JSON.stringify(arrayDeta);    
   
    
      $http.get('FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada+'&TpoDTE='+TpoDTE+'&FchEmision='+FchEmision+'&Rut='+Rut+'&RznSoc='+RznSoc+'&Giro='+Giro+'&Comuna='+Comuna+'&Direccion='+Direccion+'&Email='+Email+'&IndTraslado='+IndTraslado+'&ComunaDestino='+ComunaDestino+'&Detalles='+objDetalles).success(function(data, status, headers, config) {
           
          
           var arrayNubox = data.split(';;;');
           alertify.alert("N° Folio:"+arrayNubox[0]);
          
           if(arrayNubox[1]!=""){          
               $scope.insertarFacturasNuboxJoin(arrayNubox);
               $scope.obtenerPDF(arrayNubox[0],arrayNubox[1]);
           }else{            
               alertify.error(arrayNubox[3] );
               
           }
          
          
          setTimeout($.unblockUI, 1000); 
       
 }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al generar  factura: "+data);
      });

};

        
$scope.insertarFacturasNuboxJoin = function (arrayNubox) {
      var arrayPedidos = $scope.pedido.split(',');
      var objDetalles= JSON.stringify(arrayNubox);   
      var objPedidos= JSON.stringify(arrayPedidos);    

      $http.get('FunctionIntranet.php?act=insertarFacturaNuboxJoin&tienda='+$scope.tiendaSeleccionada+'&arrayFac='+objDetalles+'&arrayPedido='+objPedidos).success(function(data, status, headers, config) {
                 alertify.set({ delay: 10000 });
                 alertify.success("Factura Emitida con exito!");
            }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al generar  factura: "+data);
      });

};

        
        
$scope.generarReporteFacturaPDF = function () {
    
    
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

var totalPedido = $scope.getTotalPedidoJoin();
var iva = $scope.getIvaJoin();
var neto = $scope.getNetoJoin();
    
    
var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.unirPedidos[i].codProducto;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.precioVenta    =  $scope.unirPedidos[i].precioVenta;
            objDeta.total          =  $scope.unirPedidos[i].precioVenta *  $scope.unirPedidos[i].cantidad;   
       
       
            objDeta.fecha          =  daym+"/"+month+"/"+year;
            objDeta.rut            =  $scope.listPedidosIndex.rut;
            objDeta.razosocial     =  $scope.listPedidosIndex.nombre;
            objDeta.direccion      =  $scope.listPedidosIndex.direccion;
            objDeta.pedido         =  "Nota Pedido N°"+$scope.pedidoSelec;
            objDeta.totalPedido    =  totalPedido;
            objDeta.iva            =  iva;
            objDeta.neto           =  neto;
       


   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=generarFacturaPedidoPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="nota_pedido_"+fechaActual+"_"+cad+".pdf";          
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

$scope.convertToDate = function (stringDate){
  var dateOut = new Date(stringDate);
  dateOut.setDate(dateOut.getDate() + 1);
  return dateOut;
};
        
        
$scope.buscarPedido = function () {
      $scope.loading=true; 
      var rut    = angular.element(document.querySelector("#rutb")).val();
      var nombre = angular.element(document.querySelector("#nombb")).val();
      var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
      $http.get('FunctionIntranet.php?act=listarPedidos&tienda='+$scope.tiendaSeleccionada+'&rut='+rut+'&nombre='+nombre+'&desde='+desde+'&hasta='+hasta).success(function(data) {
           console.log(data);     
           $scope.listPedidos = data;    
           $scope.loading=false; 
            }).
            error(function(data, status, headers, config) {
                console.log("error buscar pedidos: "+data);
          $scope.loading=false; 
      });

};       
        
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
                     $scope.tipoUsuarioLogin    =$scope.loginUsua[0].nombre_tipo;      
                     document.getElementById('nombreLogin').innerHTML=$scope.loginUsua[0].nombre;
                     document.getElementById('rolLogin').innerHTML=$scope.loginUsua[0].nombre_tipo;

                    if($scope.tipoUsuarioLogin=='Vendedor')
                         $scope.custom = true;
            }                
             

            }). error(function(data, status, headers, config) {
                            console.log("error: "+data);
    });
        
        
}else{
   alertify.alert("Favor ingresar Usuario y Contraseña");   
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

var totalPedido = $scope.getTotalPedido();
var iva = $scope.getIva();
var neto = $scope.getNeto();
    
    
var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (i = 0; i <    $scope.detPedido.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.detPedido[i].codProducto;
            objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
            objDeta.cantidad       =  $scope.detPedido[i].cantidad;
            objDeta.precioVenta    =  formatNumero($scope.detPedido[i].precio_vendido);
            var totalProd          =  $scope.detPedido[i].precio_vendido *  $scope.detPedido[i].cantidad;
            objDeta.total          =  formatNumero(totalProd.toString());   
       
       
            objDeta.fecha          =  daym+"/"+month+"/"+year;
            objDeta.rut            =  $scope.listPedidosIndex.rut;
            objDeta.razosocial     =  $scope.listPedidosIndex.nombre;
            objDeta.direccion      =  $scope.listPedidosIndex.direccion;
            objDeta.pedido         =  "N° "+$scope.idPedido;
            objDeta.totalPedido    =  formatNumero(totalPedido.toString());
            objDeta.iva            =  formatNumero(iva.toString());
            objDeta.neto           =  formatNumero(neto.toString());
       


   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarFacturaPedidoPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="nota_pedido_"+fechaActual+"_"+cad+".pdf";          
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
        
        
$scope.confirmarFactura = function () { 
     
  if($scope.detPedido.length>20){        
     alertify.alert("Para poder generar Factura Electronica en el detalle del pedido no tiene que se mas de 20 productos.");
  }else{
       
       alertify.confirm("¿Esta seguro que desea generar Factura Electronica?", function (e) {
        if (e) {
            $scope.generarFactura();
             $("#myModalModificarPedido").modal("hide");
           }
         }      );     
   }
}           
        
        
        
}]);