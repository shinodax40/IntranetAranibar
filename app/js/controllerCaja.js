angularRoutingApp.controller('controllerCaja', ['$scope', '$http',
    function ($scope, $http) {
                $scope.customCajaInicio   = false;
         
             $scope.listProd         = [];
        $scope.auxSelProdDesagrupar  = [];
              $scope.dgProductos     = [];
              $scope.dgProductos2    = [];  
              $scope.producto        = [];
              $scope.idProducto      = "";                 
              $scope.idProdDesc = "";
              $scope.nombreProd      = "";
              $scope.idProd          = "";
              $scope.ingresos        = 0;
              $scope.salidas         = 0;
              $scope.codBodega       = 0;
        
             $scope.baseInv          = "";
             $scope.filaInv          = "";
             $scope.unidInv          = "";
             $scope.loading          = false; 

            $scope.customNomb        = false;
            $scope.customCod         = false;
            $scope.customCodBarra    = true;
            $scope.estado_cobranza   = 0;
            $scope.lisProdOfert    = []; 
                $scope.cantidadProfGrup = "";
          $scope.tipoDePago = {
              availableOptions: [

                {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                {id: 1, name: 'EFECTIVO'},
                {id: 5, name: 'DEBITO'}
              ],
              selectedOption: {id: 0} //This sets the default value of the select in the ui
    }
        
   $scope.typeOptions = [
       { name: 'Feature', value: 'feature' },
       { name: 'Bug', value: 'bug' },
       { name: 'Enhancement', value: 'enhancement' }
];
        
   
        
$scope.init = function () {    
     $scope.loading2=false;
    $scope.listarProductoOfertaDesc();
    
    $scope.listarInicioCaja();
    document.getElementById('divPedido').style.display             = 'none';    
    document.getElementById('divDesagrupar').style.display           = 'none';

    $('#codProducto').focus();
    
       if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }

    $('#rutInput').Rut({
        on_error: function(){ alertify.alert('Rut incorrecto.'); },
        format_on: 'keyup'
    });
};

$scope.formPuntos = {
  rut: "",
  valor: 0,
  ocupar: false,
  canjear: 0
};

        $scope.getCanjearPuntosCliente = function () {
            return ($scope.formPuntos.ocupar ? $scope.formPuntos.canjear : 0);
        };

        $scope.ocuparCanje = function () {
            $scope.formPuntos.canjear = 0;
            if ($scope.formPuntos.ocupar
                && ($scope.getTotal() - $scope.formPuntos.valor > 0)) {
                $scope.formPuntos.canjear = $scope.formPuntos.valor;
            }
        };

        
        $scope.obtenerPuntos = function () {
            $scope.loading2=true;
            if ($.Rut.validar($scope.formPuntos.rut)) {
                $http({
                    method: 'GET',
                    url: 'FunctionIntranet.php?act=consultarPuntos&tienda=' + $scope.tiendaSeleccionada + '&rut=' + $scope.formPuntos.rut
                }).success(function (response) {
                    $scope.formPuntos.valor = response.data;
                    $scope.formPuntos.canjear = 0;
                    $scope.loading2=false;
                }).error(function (error) {
                    console.log(error);
                                        $scope.loading2=false;

                });
            }
        };

        $scope.registrarPuntos = function (idPedido) {
            $http({
                method: 'POST',
                url: 'FunctionIntranet.php?act=registrarPuntos&tienda=' + $scope.tiendaSeleccionada,
                data: {
                    rut: $scope.formPuntos.rut,
                    valor: $scope.formPuntos.canjear,
                    ocupaPuntos: $scope.formPuntos.ocupar,
                    total: $scope.getTotal(),
                    idPedido: idPedido
                }
            }).success(function (response) {

            }).error(function (error) {
                console.log(error);
            });
        };

  $scope.selTipoBsuqeda =  function(tipoBusq){
         $('#codProducto').focus();

      if(tipoBusq == "1"){ //codigo barra
          
                   // $scope.tipoClientePedNota = "1";
                    $scope.customNomb         = false;
                   // $scope.customCod          = false;
                    $scope.customCodBarra     = true;
          
          
      }else if(tipoBusq == "2"){//codigo
          
                 // $scope.tipoClientePedNota = "2";
                    $scope.customNomb         = false;
               //   $scope.customCod          = true;
                    $scope.customCodBarra     = false;
               
      }else if(tipoBusq == "3"){//nombre
                   
                   // $scope.tipoClientePedNota = "3";
          //          $scope.customNomb         = true;
                   // $scope.customCod          = false;
          //          $scope.customCodBarra     = false;
      }
      
      
  };   



$scope.buscarProd = function(){
$scope.productoDuplicado = [];
   var codProd = angular.element(document.querySelector("#codProducto")).val();
    
    if(codProd==""){
         codProd = codProd;
       }else{
         codProd = $scope.idProducto;
       }

if($scope.idProducto !=""){

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
             url : 'FunctionIntranet.php?act=listarProductosCajasTienda&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idProd:codProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                    
                    setTimeout($.unblockUI, 500); 
                    $scope.producto = data;
                    
                    
       
                 if(data.length != 0){
                     
                     if( data.length == 1){
                            var sonido = new Audio();
                            sonido.src = 'correct-ding.mp3';
                            sonido.play();
                     
                       $scope.nombreProd  = data[0].nombreProd;
                       $scope.idProd      = data[0].id;
                       $scope.stock       = parseInt(data[0].stockProd - data[0].salidasProd);
                       $scope.salidasProd = data[0].salidasProd;             
                       $scope.cod_barra   = data[0].cod_barra;
                       $scope.precioPart  = data[0].precioPart;
                     
                     
                       $scope.foto        = data[0].foto;
                       $scope.imgUrl1     = data[0].imgUrl2;

                      if($scope.stock > 0){
                           $scope.agregarSelProductos();
                       }else{
                        
                                   $scope.idProducto ="";               
                                   alertify.alert("No hay en stock,"); 
                                   var sonido = new Audio();
                                   sonido.src = 'SD_MENU_WRONG_18.mp3';
                                   sonido.play();
                                   $('#codProducto').focus();
                      }
     
                         
                         
                        $('#codProducto').focus();
                     }else{
                         $scope.productoDuplicado = data;
                         $("#myModalListProdDuplicado").modal();      
                     }
        
                     
             
                       

                 }else{
                  //   $scope.idProducto="";
                          alertify.alert("El Producto no existe."); 
                           $('#codProducto').focus();
                          var sonido = new Audio();
                            sonido.src = 'SD_MENU_WRONG_18.mp3';
                            sonido.play();
                 }
       
       
       
       
          }).error(function(error){
                        console.log(error);
    
    });
 }else{
     alertify.alert("Favor ingresar codigo del producto para realizar la busqueda."); 
          var sonido = new Audio();
                            sonido.src = 'SD_MENU_WRONG_18.mp3';
                            sonido.play();

 }
    
};


$scope.selProductosDesagrupado = function (obj) { 
     $scope.idProdDesc = "";
    
   if( obj.stockProd == "0"){    
       alertify.alert("Producto sin stock. Porfavor seleccionar otro items."); 
   }else{
       $scope.auxSelProdDesagrupar = obj;
       $scope.idProdDesc           = obj.id;
       
       $scope.grupo_prod          = obj.grupo_prod
       
   }


};          
        
$scope.insertarProductoGrupo = function(){    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=insertarGrupoProd&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd:    $scope.idProd,
                     cantIng:   parseInt($scope.cantidadProfGrup*$scope.grupo_prod),
                     cantDesc:  $scope.cantidadProfGrup,
                     idProdDes: $scope.idProdDesc}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      

                 if(data==1){                
                     
                        alertify.success("Producto desagrupado con exito!");  
                      //  $scope.buscarProductoRapidoListar();
                     $scope.verDetallePedidoSalirDesagrupar();
                     
                 }else{
                        alertify.error("Error al desagrupar, favor contactar con el Administrador del Sistema.");                       
                 }
      
                   $("#myProducoAgrupar").modal("hide");
                  $scope.cantidadProfGrup = "";
		
  }).error(function(error){
        console.log(error);

   });
    
};        

$scope.confirmarPedidoDesagrupar = function (){ 
    alertify.confirm("Esta seguro que desea desagrupar producto?", function (e) {
        if (e) {            
            $scope.insertarProductoGrupo();         
        }
    });        
};        
              

$scope.validarStockProdNodo = function(){
    var arrayProd    = [];
    
    
 if( $scope.idProdDesc != ""){    
     
     if($scope.cantidadProfGrup !=""){

    var objDeta = new Object();
             objDeta.id           = $scope.idProdDesc;
             objDeta.cantidadProd = $scope.cantidadProfGrup;
             arrayProd.push(objDeta);     
        
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
                        $scope.confirmarPedidoDesagrupar();
                                      
                     }else{
                         alertify.alert("El producto se encuentra sin Stock:"+data+ ", favor contactar con el administrador.");
                     }

                    }).error(function(error){
                         console.log(error);

                    });  
    
      }else{
          alertify.alert("Porfavor ingresar cantidad para desagrupar.");
      }
 }else{
     alertify.alert("Porfavor seleccionar producto para proceder a desagrupar.");
 }
    
};     
        
        
        


$scope.agregarSelProductos = function () {  
    

var objAddProd = new Object(); 
var length = $scope.dgProductos.length;
var bandera=false;
var objPro = [];

    
  for(var i = 0; i < length; i++) {
         objPro = $scope.dgProductos;
        if(objPro[i].idProd == $scope.idProd ){
            bandera = true;
            break;
        }             
  } 

        if(bandera==true){
            $('#codProducto').focus();
            $scope.idProducto ="";               
            alertify.alert("El producto ya existe en la lista."); 
            var sonido = new Audio();
            sonido.src = 'SD_MENU_WRONG_18.mp3';
            sonido.play();
            $('#codProducto').focus();
            return 0 ;
        }else if($scope.idProd==""){
            $scope.idProducto ="";               
            alertify.alert("Favor realizar una busqueda para agregar a la lista."); 
            var sonido = new Audio();
            sonido.src = 'SD_MENU_WRONG_18.mp3';
            sonido.play();
            $('#codProducto').focus();
            return 0 ;                    
        }

       objAddProd.cantidad       = 1;
       objAddProd.idProd         = $scope.idProd ;
       objAddProd.nombProd       = $scope.nombreProd ;
       objAddProd.precioPart     = $scope.precioPart;
       objAddProd.stock          = $scope.stock;
       objAddProd.totalProd      = parseInt(objAddProd.cantidad * $scope.precioPart );

       objAddProd.foto          = $scope.foto;
       objAddProd.imgUrl2          = $scope.imgUrl2;

    
    
       $scope.dgProductos.push(objAddProd);
       $scope.nombreProd ="";
       $scope.ingresos   = 0;
       $scope.salidas    = 0;
       $scope.realProd   ="";
       $scope.idProducto ="";               
       $scope.baseInv     = "";
       $scope.filaInv     = "";
       $scope.unidInv     = "";

       $('#codProducto').focus();

};


        
       

$scope.agregarSelProductosDuplicado = function (prod) {  
    

var objAddProd = new Object(); 
var length = $scope.dgProductos.length;
var bandera=false;
var objPro = [];

    
  for(var i = 0; i < length; i++) {
         objPro = $scope.dgProductos;
        if(objPro[i].idProd == prod.id ){
            bandera = true;
            break;
        }             
  } 

        if(bandera==true){
            $('#codProducto').focus();
            $scope.idProducto ="";               
            alertify.alert("El producto ya existe en la lista."); 
            var sonido = new Audio();
            sonido.src = 'SD_MENU_WRONG_18.mp3';
            sonido.play();
            $('#codProducto').focus();
            return 0 ;
        }else if(prod.id==""){
            $scope.idProducto ="";               
            alertify.alert("Favor realizar una busqueda para agregar a la lista."); 
            var sonido = new Audio();
            sonido.src = 'SD_MENU_WRONG_18.mp3';
            sonido.play();
            $('#codProducto').focus();
            return 0 ;                    
        }
     
       if(prod.stock > 0){

       objAddProd.cantidad       = 1;
       objAddProd.idProd         = prod.id ;
       objAddProd.nombProd       = prod.nombreProd ;
       objAddProd.precioPart     = prod.precioPart;
       objAddProd.stock          = prod.stock;
       objAddProd.totalProd      = parseInt(objAddProd.cantidad * $scope.precioPart );

       objAddProd.foto          = prod.foto;
     //  objAddProd.imgUrl2          = prod.imgUrl2;

    
    
       $scope.dgProductos.push(objAddProd);
       $scope.nombreProd ="";
       $scope.ingresos   = 0;
       $scope.salidas    = 0;
       $scope.realProd   ="";
       $scope.idProducto ="";               
       $scope.baseInv     = "";
       $scope.filaInv     = "";
       $scope.unidInv     = "";

       $('#codProducto').focus();
       }else{
                                  $scope.idProducto ="";               
                                   alertify.alert("No hay en stock,"); 
                                   var sonido = new Audio();
                                   sonido.src = 'SD_MENU_WRONG_18.mp3';
                                   sonido.play();
                                   $('#codProducto').focus();
       }
};        


$scope.eliminarProduto = function (productIndex) {     
    $scope.dgProductos.splice(productIndex, 1);         
    $('#codProducto').focus();
};
        
        



$scope.ingresarVenta = function (){
 
      var arrVenta = [];
      var iva      = 1.19;
      var total    = 0;

    
    
if($scope.customCajaInicio == true){ 
    $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

   for (var x=0; x < $scope.dgProductos.length; x++){
        var objTienda = new Object();        
            objTienda.idProd       = $scope.dgProductos[x].idProd;
            objTienda.cantidad     = $scope.dgProductos[x].cantidad;
       
             var totalNeto = (Math.round($scope.dgProductos[x].precioPart));
             total = (Math.round(totalNeto) / iva);
           
            objTienda.precio_venta = Math.round(total);
            objTienda.totalProd    = Math.round($scope.dgProductos[x].totalProd / iva);
            objTienda.descuento    = Math.round($scope.dgProductos[x].descuento / iva);         
            objTienda.aumento      = Math.round($scope.dgProductos[x].aumento   / iva);

       
            objTienda.idTienda     = $scope.idTiendaVenta;     
            objTienda.idUsuario    = $scope.idUsuarioLogin;        
            objTienda.tipoDePago   = $scope.tipoDePago.selectedOption.id;
            arrVenta.push(objTienda);        
    }
    
    var arrIng    = JSON.stringify(arrVenta);
 
      $http({
        method : 'POST',
        url : 'FunctionIntranet.php?act=ingresarVenta&tienda='+$scope.tiendaSeleccionada,
        data:  $.param({arrVenta:arrIng
                       }),
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                 
                 //var respuesta = data.charAt(data.length-1);
          
                  $scope.dgProductos2    = $scope.dgProductos;     

                if( data!="0"){
                      
                    var tipoPagoForma =  $scope.tipoDePago.selectedOption.id;   
  
                    if(tipoPagoForma=="1" || tipoPagoForma=="6" || tipoPagoForma=="5" ){
                       
                        $scope.generarBoletaNubox(data);
                  
                    
                    }else{
                        
                        
                    alertify.confirm("DESEA IMPRIMIR RECIBO BOLETA ?", function (e) {
                    if (e) {
                        $scope.imprimirBoletaElect(data);
  
                    }
                    });    
 
                        
                      
              
                    
                    }
                    
                       $scope.dgProductos    = [];     
                       $scope.tipoDePago = {
                                  availableOptions: [
                                    {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                                    {id: 1, name: 'EFECTIVO'},
                                    {id: 5, name: 'DEBITO'}
                                  ],
                                  selectedOption: {id: 0} 
                       }
                    
                    
                    
                      $('#codProducto').focus(); 
                      setTimeout($.unblockUI, 1000); 
                      alertify.success(" Numero Venta: "+ data);  
                    
                  /*****AQUI LLEGUE*****/
                   
                    
    

                    
              
                                    
          
                }else{
                    setTimeout($.unblockUI, 1000); 
                    alertify.error("Error al generar venta, favor contactar con el Administrador del Sistema.");
                }
          
          }).error(function(error){
                    setTimeout($.unblockUI, 1000); 
                    console.log(error);
    
    });
    
    
}else{
      alertify.error("Error al generar venta, favor contactar con el Administrador del Sistema.");
    
}    
    
};


        
$scope.ingresarVentaBoleta = function (){
 
      var arrVenta = [];
      var iva      = 1.19;
      var total    = 0;

    
    
if($scope.customCajaInicio == true){ 
    $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

   for (var x=0; x < $scope.dgProductos.length; x++){
        var objTienda = new Object();   
       
           
       
            objTienda.FchEmision   = '';
            objTienda.Afecto   = 'SI';

            objTienda.idProd       = $scope.dgProductos[x].idProd;
            objTienda.cantidad     = $scope.dgProductos[x].cantidad;
       
             var totalNeto = (Math.round($scope.dgProductos[x].precioPart));
             total = (Math.round(totalNeto) / iva);
           
            objTienda.precio_venta = Math.round(total);
            objTienda.totalProd    = Math.round($scope.dgProductos[x].totalProd / iva);

            objTienda.totalNubox   = $scope.dgProductos[x].totalProd;
            objTienda.nombreNubox  = $scope.dgProductos[x].nombProd;

       
            objTienda.descuento    = Math.round($scope.dgProductos[x].descuento / iva);         
            objTienda.aumento      = Math.round($scope.dgProductos[x].aumento   / iva);

       
            objTienda.idTienda     = $scope.idTiendaVenta;     
            objTienda.idUsuario    = $scope.idUsuarioLogin;        
            objTienda.tipoDePago   = $scope.tipoDePago.selectedOption.id;
            arrVenta.push(objTienda);        
    }
    
    var arrIng    = JSON.stringify(arrVenta);
 
      $http({
        method : 'POST',
        url : 'FunctionIntranet.php?act=generarBoletaVentas&tienda='+$scope.tiendaSeleccionada,
        data:  $.param({arrVenta:arrIng, descuentoPunto: $scope.getCanjearPuntosCliente(), rutPunto: $scope.formPuntos.rut}),
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                 
                 //var respuesta = data.charAt(data.length-1);
          
                  $scope.dgProductos2    = $scope.dgProductos;     

                if( data!="0"){
                    $scope.registrarPuntos(data);
                    $scope.formPuntos = {
                        rut: "",
                        valor: 0,
                        ocupar: false,
                        canjear: 0
                    };

                    var tipoPagoForma =  $scope.tipoDePago.selectedOption.id;   
  
                    if(tipoPagoForma=="1" || tipoPagoForma=="6" || tipoPagoForma=="5" ){
                       
                       alertify.alert("Validar");
                  
                    
                    }else{
                        
                    alertify.confirm("DESEA IMPRIMIR RECIBO BOLETA ?", function (e) {
                    if (e) {
                        $scope.imprimirBoletaElect(data);
  
                    }
                    });    
 
                        
                      
              
                    
                    }
                    
                       $scope.dgProductos    = [];     
                       $scope.tipoDePago = {
                                  availableOptions: [
                                    {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                                    {id: 1, name: 'EFECTIVO'},
                                    {id: 5, name: 'DEBITO'}  
                                  ],
                                  selectedOption: {id: 0} 
                       }
                    
                    
                    
                      $('#codProducto').focus(); 
                      setTimeout($.unblockUI, 1000); 
                      alertify.success(" Numero Venta: "+ data);  
                    
                  /*****AQUI LLEGUE*****/
                   
                    
    

                    
              
                                    
          
                }else{
                    setTimeout($.unblockUI, 1000); 
                    alertify.error("Error al generar venta, favor contactar con el Administrador del Sistema.");
                }
          
          }).error(function(error){
                    setTimeout($.unblockUI, 1000); 
                    console.log(error);
    
    });
    
    
}else{
      alertify.error("Error al generar venta, favor contactar con el Administrador del Sistema.");
    
}    
    
};
        

$scope.confirmarVentas = function () { 

if($scope.dgProductos.length!=0){   
     $scope.customCajaInicio=false;      
     $scope.listarInicioCaja();
    if( $scope.tipoDePago.selectedOption.id=="0"    || $scope.tipoDePago.selectedOption.id==""){
           alertify.alert("Seleccionar modo de pago."); 
                   return 0;
      }else{
    
        alertify.confirm("Esta seguro que desea generar Venta?", function (e) {
        if (e) {
            $scope.validarStockProd();
        }
        });    
          
    }      
}else{    
    alertify.alert("Para generar Venta favor agregar producto a la lista.");
         $('#codProducto').focus();

}
};


$(function(){
  $('.lectorCodigoBarra').keypress(function(e){
    if(e.which == 13) {
      $scope.buscarProd();
    }
  })
});
 
 

      /*
$(window).keydown(function(event){
    angular.element(document.querySelector("#codProducto")).val() ="324342";
     $scope.buscarProd();

});      
    */
        
$(window).keydown(function(e){
    if (e.ctrlKey){
               $("#codProducto").focus();
    }
});        
        
        

$scope.modificarPrecio = function(){
    
var length = $scope.dgProductos.length;
var objProd = new Object();
    var tDesc = 0;
    
   for(var i = 0; i < length; i++) {
      objPro = $scope.dgProductos;
       
       if(objPro[i].idProd == $scope.idProd ){
           
           
             tDesc = ($scope.dgProductos[i].precioPart) - $scope.modPrecioModal;
           
             if(tDesc < 0 ){
                  $scope.dgProductos[i].aumento = -1 * (($scope.dgProductos[i].precioPart) - $scope.modPrecioModal);
                  $scope.dgProductos[i].descuento = 0;

             }else{
                  $scope.dgProductos[i].descuento = (($scope.dgProductos[i].precioPart) - $scope.modPrecioModal);
                  $scope.dgProductos[i].aumento = 0;
                 
             }
           
           
            //  $scope.dgProductos[i].descuento = ($scope.dgProductos[i].precioPart) - $scope.modPrecioModal;
              $scope.dgProductos[i].precioPart = $scope.modPrecioModal;
              $scope.dgProductos[i].totalProd = $scope.dgProductos[i].cantidad *  $scope.dgProductos[i].precioPart ;
           break;
           
           
           
           
        }             
    }
    
     $scope.modPrecioModal = "";
     $('#codProducto').focus();
};
        
        /******aqui*****/
  
        
        
$scope.modificarPrecioDescuento = function(){
    
var length = $scope.dgProductos.length;
var objProd = new Object();
    var tDesc = 0;
    
   for(var i = 0; i < length; i++) {
      objPro = $scope.dgProductos;
       
       if(objPro[i].idProd == $scope.idProd ){
           
              tDesc = ($scope.dgProductos[i].precioPart) * ($scope.listDesProdTienda[0].descuento/100);
             
              $scope.modPrecioModal = tDesc;
           
             /*if(tDesc < 0 ){
                  $scope.dgProductos[i].aumento = -1 * (($scope.dgProductos[i].precioPart) - $scope.modPrecioModal);
                  $scope.dgProductos[i].descuento = 0;

             }else{
                  $scope.dgProductos[i].descuento = (($scope.dgProductos[i].precioPart) - $scope.modPrecioModal);
                  $scope.dgProductos[i].aumento = 0;
                 
             }*/
           
           $scope.dgProductos[i].descuento = tDesc ;
            //  $scope.dgProductos[i].descuento = ($scope.dgProductos[i].precioPart) - $scope.modPrecioModal;
              $scope.dgProductos[i].precioPart = $scope.dgProductos[i].precioPart - tDesc;
              $scope.dgProductos[i].totalProd = $scope.dgProductos[i].cantidad *  $scope.dgProductos[i].precioPart ;
           break;
        }             
    }
    
     $scope.modPrecioModal = "";
     $('#codProducto').focus();
};        
        
        
        
        
$scope.modificarAumentar = function(pro){
var length = $scope.dgProductos.length;
var objProd = new Object();
    
   for(var i = 0; i < length; i++) {
      objPro = $scope.dgProductos;
       
       if(objPro[i].idProd == pro.idProd ){
              $scope.dgProductos[i].cantidad = $scope.dgProductos[i].cantidad + 1;
              $scope.dgProductos[i].totalProd = $scope.dgProductos[i].cantidad *  $scope.dgProductos[i].precioPart ;
           
           break;
        }             
    }
     $('#codProducto').focus();
};        
        
        
$scope.modificarDisminuir = function(pro){
var length = $scope.dgProductos.length;
var objProd = new Object();
    
   for(var i = 0; i < length; i++) {
      objPro = $scope.dgProductos;
       
       if(objPro[i].idProd == pro.idProd ){
    
           if(objPro[i].cantidad != "1"){
           
           $scope.dgProductos[i].cantidad = $scope.dgProductos[i].cantidad - 1;
           $scope.dgProductos[i].totalProd = $scope.dgProductos[i].cantidad *  $scope.dgProductos[i].precioPart ;
               
           break;
        
           }else{
            
               alertify.alert("No puede ser menos de 1");
               $('#codProducto').focus();

           }
       
       }             
    }
     $('#codProducto').focus();
};   
        



$scope.getTotal = function(){
var length = $scope.dgProductos.length;
var total = 0;
   for(var i = 0; i < length; i++) {
      objPro = $scope.dgProductos;
      total += parseInt($scope.dgProductos[i].cantidad  * $scope.dgProductos[i].precioPart);
    }

return parseInt(total) - $scope.getCanjearPuntosCliente();
};



$scope.focusfous = function(){    
     $('#codProducto').focus();
}
/*consultarProductoNodo*/

$scope.verDetallePedidoSalir = function(){
 document.getElementById('divDesagrupar').style.display           = 'none';
 document.getElementById('divPedido').style.display               = 'none';      
 document.getElementById('divPedidoListado').style.display        = 'block';     
 $scope.idProducto ="";
 $('#codProducto').focus();
}

$scope.verDesagruparProductoSalir = function(){
 document.getElementById('divDesagrupar').style.display           = 'none';
 document.getElementById('divPedido').style.display               = 'block';      
 document.getElementById('divPedidoListado').style.display        = 'none';     
 $scope.idProducto ="";
 $('#codProducto').focus();
}

$scope.verDetallePedidoSalirDesagrupar = function(){
 document.getElementById('divDesagrupar').style.display           = 'none';
 document.getElementById('divPedido').style.display               = 'block';      
 document.getElementById('divPedidoListado').style.display        = 'none';     
 //$scope.idProducto ="";
 $('#codProducto').focus();
}

$scope.verDesagruparProducto = function(){
 document.getElementById('divPedido').style.display               = 'none';      
 document.getElementById('divPedidoListado').style.display        = 'none';     
 document.getElementById('divDesagrupar').style.display           = 'block';    
    
    
// $scope.idProducto ="";
 $('#codProducto').focus();
}

$scope.consultarProductoNodo = function (prodSel){
  $scope.arrProductosDesagrupar  = [];  
        
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=consultarInventarioIngCaja&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_prod:prodSel.nodo_prod.toString()}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);   
      
      $scope.listProd = data;
      /*
      $scope.grupo_prod          = data[0].grupo_prod;
      $scope.nombreProdPrincipal = prodSel.nombreProd;    
      $scope.stockProd           = data[0].stockProd;
      $scope.idProd              = prodSel.id;
      $scope.nombreProd          = data[0].nombreProd;    
      $scope.idProdDesc          = data[0].id;
      
      
    */
        
      $scope.idProd              = prodSel.id;
      
      $scope.arrProductosDesagrupar = $scope.listProd;
          
  
      
      
      $scope.verDesagruparProducto();
    //  $("#myProducoAgrupar").modal(); 
              
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
    
    
    
}




$scope.buscarProductoRapidoListar = function(){
    document.getElementById('divDesagrupar').style.display         = 'none';
    document.getElementById('divPedidoListado').style.display      = 'none';   
    document.getElementById('divPedido').style.display             = 'block';    
}


$scope.listarProductoRapido = function(){
     $scope.loading=true;
     var nombre = angular.element(document.querySelector("#codProducto")).val();

    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=buscarProductoRapido&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({nombr:nombre,
                     tipBus:'Tienda'}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);   
            $scope.arrProductos =  data;
            $scope.loading=false;              
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
    
    
} 


$scope.buscarRapidaSel = function(prod){    
    $scope.idProducto = prod.id;
    $scope.buscarProd();
}     



        
        
 function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}       
        

$scope.imprimirBoletaElect = function(numbPed){
   var arrayDeta     = []; 
   var iva = 0.19;
   var total = 0;

    
          for (var i = 0; i <    $scope.dgProductos2.length; i++) {          
                  var objDeta= new Object();           
                   objDeta.pedido         = '';
                        objDeta.nombreProd     =  $scope.dgProductos2[i].nombProd;
                        objDeta.cantidad       =  $scope.dgProductos2[i].cantidad;
                        objDeta.folio          =  numbPed;
                        total               =  Math.round($scope.dgProductos2[i].precioPart * $scope.dgProductos2[i].cantidad);    
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
                         url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaBoletaVentas',                    
                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 
         
                           $scope.dgProductos2    = [];                        
                           $scope.tipoDePago      = {
                                      availableOptions: [
                                        {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                                        {id: 1, name: 'EFECTIVO'},
                                        {id: 5, name: 'DEBITO'}  
                                      ],
                                      selectedOption: {id: 0} 
                                 }
                      
                         
                      
                      
                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });
    
      $scope.imprimirTermicaBodega(numbPed);


    
 }          
     




$scope.imprimirTermicaBodega = function(numbPed){
   var arrayDeta     = [];   
  $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:numbPed}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.detPedido = data;
                  for (var i = 0; i <    $scope.detPedido.length; i++) {          
                  var objDeta= new Object();           
                        objDeta.pedido         = '';                      
                        objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
                        objDeta.cantidad       =  $scope.detPedido[i].cantidad;
                        objDeta.bodega         =  $scope.detPedido[i].bodega;
                        objDeta.idPedido       =  numbPed;
                        arrayDeta.push(objDeta);
                   }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaCaja&tienda='+$scope.tiendaSeleccionada,                    
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











$scope.listarInicioCaja = function () {    
    $http({
                        method : 'POST',
                        data:  $.param({idUsua:$scope.idUsuarioLogin}),
                        url : 'FunctionIntranet.php?act=listarInicioCaja&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                        console.log(data);
       
        
        
        if(data != ""){
              if(data[0].hora_caja_fin !='00:00:00' ){
            
   
                  
                    alertify.alert("Caja Cerrada");
                                   
            }else{
                
                $scope.customCajaInicio   = true;
           
            }
                  
           }else{
             alertify.alert("Iniciar caja para poder realizar ventas.");
           }
        
       

          }).error(function(error){
                        console.log(error);
    
    });   

};  
  
   
$scope.validarStockProd = function(){
    
    var arrayProd    = [];

        
    for(var i = 0; i < $scope.dgProductos.length; i++){
                    var objDeta = new Object();
                     objDeta.id           = $scope.dgProductos[i].idProd;
                     objDeta.cantidadProd = $scope.dgProductos[i].cantidad;
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
                       $scope.ingresarVentaBoleta();
                     }else{
                         alertify.alert("Los siguientes productos se encuentran sin Stock:"+data+ "<br/>Favor volver a seleccionar los productos senalados, ingresando nuevamente el Tipo y la Marca.");
                         setTimeout($.unblockUI, 1000); 
                     }


                    }).error(function(error){
                                        console.log(error);
                                        setTimeout($.unblockUI, 1000); 

                    });  
};     
        
        
        
        
        
        
$scope.generarBoletaNubox = function (data) {
    
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
    
    
    var arrayDeta     = [];

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
    
    
   $scope.estado_cobranza = $scope.tipoDePago.selectedOption.id;  
    
    

  /* var iva = 0.19;*/
   var total = 0;

    
    

   for (var i = 0; i <  $scope.dgProductos.length; i++) {   
    total = 0;   
    var objDeta= new Object();
    total = Math.round($scope.dgProductos[i].precioPart);    
   
    /*var totalNeto = (Math.round(total));
    total = (Math.round(total) * iva) + Math.round(totalNeto);*/ 
       
       
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.dgProductos[i].nombProd.substr(0,35).replace("+"," "); 
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.dgProductos[i].cantidad;
            objDeta.Precio      =  Math.round(total);
            objDeta.Codigo      =  $scope.dgProductos[i].idProd;
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
          }).success(function(datax){
                console.log(datax);
              
              
              
          //     var respuesta = datax.charAt(datax.length-1);
                
                if(datax =="0"){
         
                    alertify.error("Error al generar facturas, favor intentar nuevamente.");
                
                    
                    
                }else{
                    /*var arrayNubox = data.split(';;;');
                    alertify.error(arrayNubox[3]);*/
                    
                    
                    
                          /* alertify.alert(RznSoc+" Factura Generada!");
                  alertify.set({ delay: 10000 });*/
                 //    setTimeout($.unblockUI, 1000);     
                /*   $scope.buscarPedido('');         
                 $scope.verDetallePedidoSalir();
                 $scope.detPedido           = [];     
                 $scope.listPedidosIndex    = [];  */   
                     $scope.dgProductos    = [];    
                    
                     $scope.tipoDePago = {
                          availableOptions: [
                            {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},          
                            {id: 1, name: 'EFECTIVO'},
                            {id: 5, name: 'DEBITO'}
                          ],
                          selectedOption: {id: 0} 
                     }
                       
            
           
                   alertify.confirm("Esta seguro que desea IMPRIMIR Boleta Electronica ?", function (e) {
                       
                    if(e){
                        $scope.imprimirBoletaElectCajaVentas(data);
                     }
                    });    
                    
                    
                    
                    
                    
                    
                }
          
               setTimeout($.unblockUI, 1000);   
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });
};   
 
        
        

      
$scope.imprimirBoletaElectCajaVentas = function(pedSel){
   var arrayDeta     = []; 
   var iva           = 0.19;
   var total         = 0;
   var obSelect      = "";
  var banderaOferta = false;
     
  $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.detPedido = data;
      
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
                        objDeta.idPedido       =  pedSel;
                      
    
                     
                         
         //  if(pedSel.folio!=null){
                            objDeta.folio      =  $scope.detPedido[i].folio;
                       // }else{
                         //   objDeta.folio      =  pedSel.id_pedido;
                        //}
                      
                        total               = 0;  
                        total               =  Math.round($scope.detPedido[i].precio_vendido * $scope.detPedido[i].cantidad);
                        var totalNeto       = (Math.round(total));
                        total               =  Math.round((Math.round(total) * iva) + Math.round(totalNeto));       
                        objDeta.Precio      =  formatNumero((total.toString()));
                        objDeta.TotalBol    =  formatNumero($scope.getTotalPedido().toString());
                      
                         
                          switch($scope.estado_cobranza) {
                             case '6'://Transferencia
                                obSelect = 'Transferencia';
                                break;
                             case '1'://Efectivo
                                obSelect = 'Efectivo';
                                break;
                             case '5'://transBank
                                obSelect = 'Debito';
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
                         url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaBoletaElect&tienda='+$scope.tiendaSeleccionada,                    
                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                   
                      }).success(function(data){
                   
                            console.log(data); /********AQUI*****/
                   
                       //  if(data != 0){
                            
                   //     }
                   
                          
                           

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });


        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    
    
    $scope.estado_cobranza = 0;
                             $scope.imprimirTermicaBodega(pedSel); 
       
}

$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.dgProductos2.length; j++){
        var product = $scope.dgProductos2[j];
        total += Math.round(product.cantidad * product.precioPart);
}

return Math.round(total);
}; 
        
        
        
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
        
        
        
        
        
        
        /***********AQUI ESTOY********************/
        
$scope.lisTiendaDesct = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarDescTiendaProd&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          $scope.listDesProdTienda    = []; 
       	  $scope.listDesProdTienda    = data;		 

          }).error(function(error){
                        console.log(error);
    }); 
};        
        
        
$scope.selProd = function(prod){
    
        if(prod.descuento == null){    
              $("#myModalPrecio").modal();   
            $scope.lisTiendaDesct();
            $scope.idProd          = prod.idProd;
            $scope.stockModal      = prod.stock;
            $scope.precioPartModal = prod.precioPart;
            $scope.nombreModal     = prod.nombProd;

        }else{

           alertify.alert("El producto ya se encuentra con un descuento.");          

        }
    
}
        
        
        
$scope.consultarProductoTiendasStock = function(prod){
          $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#74B6F7', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
         
               

            $scope.nombreModal     = prod.nombreProd;

    
    	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarInventarioTiendas&tienda='+$scope.tiendaSeleccionada,
                    data:  $.param({idProd:prod.id										
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
              $("#myModalStockTienda").modal(); 
          $scope.listProdStock    = []; 
       	  $scope.listProdStock    = data;		 
setTimeout($.unblockUI, 1000);  
          }).error(function(error){
                        console.log(error);
    }); 
    
    
     
    
}        
        
        
    
        
$('#dgPedidos').on('click', 'tbody tr', function(event) {
  $(this).addClass('highlight').siblings().removeClass('highlight');
});
        
        
        

}]);