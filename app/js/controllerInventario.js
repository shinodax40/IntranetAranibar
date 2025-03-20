angularRoutingApp.controller('controllerInventario', ['$scope', '$http','$cookies','$cookieStore', '$log',
    function ($scope, $http, $cookies, $cookieStore, $log) {
    $scope.customNomb         = false;
    $scope.customCod          = false;
    $scope.customCodBarra     = true;
              $scope.id_tipo_inventario      =-1;
              $scope.dgProductos             = [];
              $scope.listarInventario        = [];
              $scope.customCookies           = false; 
              $scope.producto                = [];
              $scope.idProducto              = ""; 
              $scope.nombreProd              = "";
              $scope.idProd                  = "";
              $scope.ingresos                = 0;
              $scope.salidas                 = 0;
              $scope.codBodega               = 0;        
              $scope.baseInv                 = "";
              $scope.filaInv                 = "";
              $scope.unidInv                 = "";
              $scope.tipoClientePedNota      = "1";
          //   $scope.arrayDetaInv = [];
              $scope.productoAdd             = []; 

        
              var weekdays = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
       

              var mydate=new Date();
              var weekday = weekdays[mydate.getDay()];
              
               $scope.year=mydate.getYear();
              if ($scope.year < 1000)
              $scope.year+=1900;
               $scope.day=mydate.getDay();
              $scope.month=mydate.getMonth()+1;
              if ( $scope.month<10)
               $scope.month="0"+ $scope.month;
               $scope.daym=mydate.getDate();
              if ( $scope.daym<10)
               $scope.daym="0"+ $scope.daym;    
              $scope.loading=false; 
   
        
  $scope.eliminarCookieSesion = function() {
       $cookieStore.remove('detalleProductoCookiesInvt');
       $scope.customCookies        = false;
  }
  
  
  $scope.mostrarCookieSesion = function() {
        var getmycookiesback = $cookieStore.get('detalleProductoCookiesInvt');        
        $log.info(getmycookiesback); 
        $scope.dgProductos=getmycookiesback;
        $scope.customCookies        = false;
       // $cookieStore.remove('detalleProductoCookiesInvt');
        
  }
          
   

$scope.verListadoEnDetalle =  function(stdo){
          document.getElementById('divPedido').style.display               = 'block';     
          document.getElementById('componenteInventario').style.display    = 'none'; 
            document.getElementById('divPedidoDetalle').style.display      = 'none';    

          $scope.verDetallePed(stdo);

}
  
$scope.verDetallePed = function(stdo){
    
     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleObsPed&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idEstado:stdo,
                     idProd:$scope.idProd}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.listInformeTranspDeta = data;
  }).error(function(error){
        console.log(error);

});
    
    
    
}
  
  
$scope.init = function () {
    
    
    
    
    document.getElementById('buscarFecha').value=  $scope.year+"-"+ $scope.month+"-"+ $scope.daym;
    
    $scope.listarInventarioBodegaDiario(document.getElementById('buscarFecha').value);    
    
  document.getElementById('componenteInventario').style.display    = 'block'; 
        document.getElementById('divPedidoDetalle').style.display             = 'none';    

  document.getElementById('divPedido').style.display    = 'none'; 
   
    
if($cookieStore.get('detalleProductoCookiesInvt') != null){      
     $scope.customCookies        = true;
}
    
    
     $scope.salidas         = 0;
     $scope.ingresos        = 0;        
     $scope.tipoBusqueda    = "barra";    
    
if( $scope.customInterEntrar != true){
    $scope.customInterLogin=true;
 }    
    $scope.bodegas = {
      availableOptions: [
        {id: '1', name: '1'},
        {id: '2', name: '2'},
        {id: '3', name: '3'},
        {id: '4', name: '4'},
        {id: '5', name: '5'}            

      ],
      selectedOption: {id: $scope.codBodega} //This sets the default value of the select in the ui
    } 

    $('#codProducto').focus();




    
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
    
$scope.fechaActual = daym+"-"+month+"-"+year;
$scope.cad         = mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
};



        
$scope.buscarRapidaSel = function(prod){    
    $scope.idProducto = prod.id;
    $scope.buscarProd();
    
}        
        

$scope.buscarProd = function(){
var bandera="false";
var length = $scope.dgProductos.length;
var objPro = [];
  
     $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
     } }); 
  
 
if( $scope.idProducto !=""){
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarProductosInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd:$scope.idProducto}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.producto = data;
        
         if(data.length != 0){
             
                for(var i = 0; i < length; i++) {
                 
                  objPro = $scope.dgProductos;
                    if(objPro[i].idProd ==    $scope.idProd ){
                        bandera = "true";
                    }             
                }
             
                if(bandera == "true"){    
                    alertify.alert("El producto ya existe en la lista."); 
                   /*  document.getElementById("codProducto").value = "";
                     $scope.idProducto = "";*/
                }else{
                
                       //$scope.arrProductos =  data;
                        if(data.length == 1){
                           $scope.nombreProd         = data[0].nombreProd;
                           $scope.idProd             = data[0].id;
                           $scope.ingresos           = data[0].stockProd;             
                           $scope.salidas            = data[0].salidasProd;
                           $scope.cod_barra          = data[0].cod_barra;

                           $scope.cantProdConObs     = data[0].cantProdConObs;
                           $scope.cantProdPendiente  = data[0].cantProdPendiente;
                           $scope.cantProdEnDesp     = data[0].cantProdEnDesp;
                           $scope.cantProdEnBodega   = data[0].cantProdEnBodega;
                           $scope.cantProdRechazado  = data[0].cantProdRechazado;
                           $scope.fInventario        = data[0].f_inventario;
                         //  $scope.id_tipo_inventario = parseInt(data[0].id_tipo_inventario);

                            
                          if(parseInt(data[0].id_tipo_inventario) == 1){
                              $scope.id_tipo_inventario="Subida"
                             
                             }else if (parseInt(data[0].id_tipo_inventario) == 2){
                                                            $scope.id_tipo_inventario="bajada"

                          }else if(parseInt(data[0].id_tipo_inventario) == 0){
                                                                                               $scope.id_tipo_inventario="Cuadrado"

                                   }else{
                                          $scope.id_tipo_inventario="Sin Inventariar"
                                   }    
                            
                            

                           $scope.foto         = data[0].foto;


                                 document.getElementById('componenteInventario').style.display    = 'none'; 
                                 document.getElementById('divPedido').style.display               = 'block';   
                                 setTimeout($.unblockUI, 1000); 

                      }else{
                           $scope.arrProductos =  data;
            //$("#myModalListProdDoble").modal("hide");
                          $("#myModalListProd").modal();   
                           }
    
  
                    
                }
             
                       setTimeout($.unblockUI, 1000); 

             
         }else{
             alertify.alert("El Producto no existe."); 
                 setTimeout($.unblockUI, 1000); 

           /*   document.getElementById("codProducto").value = "";
                     $scope.idProducto = "";  */

         }
          $scope.loadingProd = false;
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
 
 }else{
     alertify.alert("Favor ingresar codigo del producto para realizar la busqueda."); 
         setTimeout($.unblockUI, 1000); 


 }
};


$scope.verDetallePedidoSalir = function(){
        document.getElementById('divPedidoDetalle').style.display             = 'none';    

     document.getElementById('divPedido').style.display               = 'none';    
     document.getElementById('componenteInventario').style.display    = 'block';     
     $scope.listInformeTranspDeta = "";
}


$scope.agregarSelProductos = function () {  
$scope.dgProductos              = [];    

var objAddProd = new Object(); 

var cantidadInvetario =  parseInt(($scope.baseInv * $scope.filaInv) + $scope.unidInv) ; 
var stockInv          =  parseInt($scope.ingresos - $scope.salidas);    
var stockInventario   =  parseInt(isNaN(stockInv) ? 0 : stockInv);
    
    
  if( cantidadInvetario < 0){
        $('#codProducto').focus();
                    alertify.alert("Cantidad a ingresar tiene que ser mayor o igual a 0."); 
                    return 0 ;
     
    }else{
            if($scope.idProd==""){
            $('#codProducto').focus();
            alertify.alert("Favor realizar una busqueda para agregar a la lista."); 
            return 0 ;                    
        }else if(stockInv==0){            
            if(cantidadInvetario==0){
                   $('#codProducto').focus();
                    alertify.alert("El producto ya se encuentra sin Stock."); 
                    return 0 ;                
            }             
        }
    }

       objAddProd.realStock      = parseInt(stockInventario  -  cantidadInvetario) ;
       objAddProd.idProd         = $scope.idProd;
       objAddProd.nombProd       = $scope.nombreProd;
       objAddProd.cod_barra      = $scope.cod_barra;
       objAddProd.cantIng        = cantidadInvetario;
       objAddProd.cantIngresada  = $scope.unidInv;

    
      if(objAddProd.realStock < 0){
           objAddProd.estadoInventario = "Ingresar";
           objAddProd.realStock      = parseInt(cantidadInvetario - stockInventario)  ;
      }else if(objAddProd.realStock == 0 ){
            objAddProd.estadoInventario = "Cuadrado"; 
      }else if(objAddProd.realStock > 0 ){
            objAddProd.estadoInventario = "Salida"; 
      }


       $scope.dgProductos.push(objAddProd);
      
       $cookieStore.put('detalleProductoCookiesInvt',  $scope.dgProductos);

    
       
       $scope.nombreProd               = "";
       $scope.idProd                   = "";
       $scope.ingresos                 = 0;
       $scope.salidas                  = 0;
       $scope.realProd                 = "";
       $scope.idProducto               = "";               
       $scope.baseInv                  = "";
       $scope.filaInv                  = "";
       $scope.unidInv                  = "";
       $scope.verDetallePedidoSalir();
       $('#codProducto').focus();
    
    /****AQUI***/
    
    $scope.ingresarInventario();
    

};
        
        
$scope.listarProductoHistorial =  function(){    
     $scope.listarInventarioBodegaDiario(document.getElementById('buscarFecha').value);    
}        
        
        
$scope.seleccionarFechaListar = function(){
  $scope.idProducto="";  
  $scope.listarInventarioBodegaDiario(document.getElementById('buscarFecha').value);
    
}        
        
        
$scope.listarInventarioBodegaDiario = function(fechaInicial){    
    $scope.listarInventario = [];

     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarInventarioBodega&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({fechaListar:fechaInicial,
                     idProd:$scope.idProducto}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}   
          }).success(function(data){
                console.log(data);
   			  
          
             for (var x=0; x < data.length; x++){
                    var objProd = new Object();

                        objProd.idProd              = data[x].id_prod;
                        objProd.nombProd            = data[x].nombreProd;
                        objProd.realStock           = data[x].cantidad;
                        objProd.id_asociado         = data[x].id_asociado;
                        objProd.id_tipo_inventario  = data[x].id_tipo_inventario;
                        objProd.cantidad_ingresada  = data[x].cantidad_ingresada;
                        objProd.anulado             = data[x].anulado;
                        objProd.id                  = data[x].id;
                        objProd.fecha_ingreso                  = data[x].fecha_ingreso;
                         objProd.observacion                  = data[x].observacion;


                     if(data[x].id_tipo_inventario == 1 ){
                            objProd.estadoInventario = "Ingresar"; 
                      }else if(data[x].id_tipo_inventario == 2 ){
                            objProd.estadoInventario = "Salida"; 
                      }else if(data[x].id_tipo_inventario == 0 ){
                            objProd.estadoInventario = "Cuadrado"; 
                      }
                 
                      $scope.listarInventario.push(objProd);

                }
          
          
        
          }).error(function(error){
                        console.log("error Listar productos Inventariados: "+error);
    
      });
    

}        







$scope.ingresarInventario = function (){
    var arrCuadradro  = [];
    var arrSalida  = [];
    var arrIngreso = [];
    
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
        var objProd = new Object();
        
            objProd.idProd   = $scope.dgProductos[x].idProd;
            objProd.cantidad = $scope.dgProductos[x].realStock;
            objProd.codBodega= $scope.bodegas.selectedOption.id;
            objProd.cantIngresada= $scope.dgProductos[x].cantIngresada;

       
       
        
        if($scope.dgProductos[x].estadoInventario=="Ingresar"){
            arrIngreso.push(objProd);
        }else if($scope.dgProductos[x].estadoInventario=="Salida"){
            arrSalida.push(objProd);
        }else if($scope.dgProductos[x].estadoInventario=="Cuadrado"){
            arrCuadradro.push(objProd);
        }      
    }
    
    var arrSal    = JSON.stringify(arrSalida);
    var arrIng    = JSON.stringify(arrIngreso);
    var arrCua    = JSON.stringify(arrCuadradro);

    
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=ingresarInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrSal:arrSal,
                     arrIng:arrIng,
                     arrCua:arrCua}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
                  var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    $('#codProducto').focus();
                  //  $scope.dgProductos    = []; 
                    alertify.success("Inventario generado con exito!");
                     setTimeout($.unblockUI, 1000);    
                    $cookieStore.remove('detalleProductoCookiesInvt');
                    $scope.dgProductos = [];
                    $scope.listarInventarioBodegaDiario(); 


                }else{
                   $('#codProducto').focus();
                    alertify.error("El producto inventariado a la fecha se encuentra en la lista, intentar con otro producto.");
                     setTimeout($.unblockUI, 1000);
                }
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});

};


$scope.confirmarInvetario = function () { 
    
angular.element(document.getElementById('btnGenerar'))[0].disabled = true;
    
if($scope.dgProductos.length!=0){    
    if($scope.bodegas.selectedOption.id != 0){        
        alertify.confirm("¿Esta seguro que desea generar inventario?", function (e) {
        if (e) {
            $scope.ingresarInventario();
        }
    });    

        }else{      
            alertify.alert("Favor seleccionar numero bodega para ingresar inventario.");
           
            angular.element(document.getElementById('btnGenerar'))[0].disabled = false;

        }
}else{
    
    alertify.alert("Para ingresar inventario favor agregar producto a la lista.");
                angular.element(document.getElementById('btnGenerar'))[0].disabled = false;


}
};


$(function(){
  $('.lectorCodigoBarra').keypress(function(e){
    if(e.which == 13) {
      $scope.buscarProd();
    }
  })
});
 
        
$scope.selTipoBusqueda = function (rdo) { 
      $scope.tipBusqueda = rdo;
      $('#codProducto').focus();
};        
        
        
$scope.salir = function () { 
    $scope.verDetallePedidoSalir();
     $scope.nombreProd ="";
       $scope.idProd     ="";
       $scope.ingresos   = 0;
       $scope.salidas    = 0;
       $scope.realProd   ="";
       $scope.idProducto ="";               
       $scope.baseInv     = "";
       $scope.filaInv     = "";
       $scope.unidInv     = "";

       $('#codProducto').focus();
};           
        
        
        

$scope.confirmarAnularProducto = function (productIndex) { 

    
    alertify.confirm("Esta seguro que desea anular producto?", function (e) {
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
            
            $scope.eliminarProduto(productIndex); 
            
        } else {
            // user clicked "cancel"
        }
    });    

};
        
        
$scope.eliminarProduto = function (productIndex) {     
  /*  $scope.dgProductos.splice(productIndex, 1);         
    $('#codProducto').focus();
    $cookieStore.put('detalleProductoCookiesInvt',  $scope.dgProductos);*/
    
    
     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=anularInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_asociado:productIndex.id_asociado,
                     id_tipo_inv:productIndex.id_tipo_inventario,
                     id_invent: productIndex.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
                  setTimeout($.unblockUI, 1000);
         $scope.listarInventarioBodegaDiario();
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
    
    
}        
        

                                         
$scope.generarReporteInventario = function () {
    

  var arrayDeta = [];
  var count=0;    

   for (var i = 0; i <    $scope.listarInventario.length; i++) {   
       
       if( $scope.listarInventario[i].anulado=="0"){
           
       
                 count = count + 1   ;
               var objDeta= new Object();           
                   objDeta.pedido         = '';
                        objDeta.idProd             =  $scope.listarInventario[i].idProd;
                        objDeta.nombProd           =  $scope.listarInventario[i].nombProd;
                        objDeta.cantIng            =  $scope.listarInventario[i].cantidad_ingresada;
                        objDeta.diferencia         =  $scope.listarInventario[i].realStock;      

                        objDeta.fecha              =  $scope.fechaActual +" - "+ $scope.cad;
                        objDeta.counta             =  count;

                        if($scope.listarInventario[i].estadoInventario == "Ingresar"){

                             objDeta.urlImg     = 'img/flechaArriba-min.png';

                        }else if($scope.listarInventario[i].estadoInventario == "Cuadrado" ){

                             objDeta.urlImg     = 'img/correcto-min.png';

                        }else if($scope.listarInventario[i].estadoInventario == "Salida" ){

                             objDeta.urlImg     = 'img/flechaAbajo-min.png';

                        }



                arrayDeta.push(objDeta);
           
       }
       
     //  console.log($scope.arrayDeta[i]);
   }
    
    
    
     

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'productos':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarInventarioPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="inventario_"+$scope.fechaActual+"_"+$scope.cad+".pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
           angular.element(document.getElementById('btnGenerar'))[0].disabled = false;
                  $scope.dgProductos= [];
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
        
        
        
$scope.generarReporteInventarioIng = function () {
    var idProd       = "";
    var arrayDetaInv = [];
    var count        = 0;
    
    for (var i = 0; i <    $scope.dgProductos.length; i++) {   
       idProd += ','+$scope.dgProductos[i].idProd;
    }  
    
 $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=consultarInventarioIng&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_prod:idProd.substr(1)}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
             
         
  var prodConsulIng = data;
     
        
 for (var i = 0; i <    $scope.dgProductos.length; i++) {   
  count = count + 1   ;
   var objDeta= new Object();           
            objDeta.idProd             =  $scope.dgProductos[i].idProd;
            objDeta.nombProd           =  $scope.dgProductos[i].nombProd;         
             for(var x = 0 ; x < prodConsulIng.length ; x ++ ){
                 if(objDeta.idProd  == prodConsulIng[x].id){                     
                        objDeta.stock  = prodConsulIng[x].stockProd;
                     break;
                   }                  
             }         
            objDeta.fecha           =  $scope.fechaActual +" - "+ $scope.cad;
            objDeta.counta          =  count;
    arrayDetaInv.push(objDeta);       
   }     
   $scope.generarInventarioIngPDF(arrayDetaInv);  
     
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});     
};

    /*
  $scope.selTipoBsuqeda =  function(tipoBusq){
      
      if(tipoBusq == "1"){ //codigo barra
          
                    $scope.tipoClientePedNota = "1";
                    $scope.customNomb         = false;
                    $scope.customCod          = false;
                    $scope.customCodBarra     = true;
          
          
      }else if(tipoBusq == "2"){//codigo
          
                    $scope.tipoClientePedNota = "2";
                    $scope.customNomb         = false;
                    $scope.customCod          = true;
                    $scope.customCodBarra     = false;
               
      }else if(tipoBusq == "3"){//nombre
                   
                    $scope.tipoClientePedNota = "3";
                    $scope.customNomb         = true;
                    $scope.customCod          = false;
                    $scope.customCodBarra     = false;
      }
      
      
  };   */
        
        
  $scope.selTipoBsuqeda =  function(tipoBusq){
         $('#codProducto').focus();

      if(tipoBusq == "1"){ //codigo barra
                    $scope.customBuscarHistorial = false;

                    $scope.tipoClientePedNota = "1";
                    $scope.customNomb         = false;
                   // $scope.customCod          = false;
                    $scope.customCodBarra     = true;
          
          
      }else if(tipoBusq == "2"){//codigo
          
                    $scope.customBuscarHistorial = true;
          
          
                    $scope.tipoClientePedNota = "2";
                    $scope.customNomb         = false;
               //     $scope.customCod          = true;
                    $scope.customCodBarra     = false;
               
      }else if(tipoBusq == "3"){//nombre
                      $scope.customBuscarHistorial = false;
                 
                    $scope.tipoClientePedNota = "3";
                    $scope.customNomb         = true;
                   // $scope.customCod          = false;
                    $scope.customCodBarra     = false;
      }
      
      
  };         
        
$scope.listarProductoRapido = function(){
   var nombre = angular.element(document.querySelector("#codProducto")).val();
  

    
    
$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=buscarProductoRapido&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({nombr:nombre,
                     tipBus:'Inventario'}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);   
            $scope.arrProductos =  data;
           
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;
});
    
    
}        
        

        
$scope.generarInventarioIngPDF = function(arrayDetaInv){    
    
var jsonData=angular.toJson(arrayDetaInv);
var objectToSerialize={'detalle':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarInventarioValidarPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="inventario_ingresos"+$scope.fechaActual+"_"+$scope.cad+".pdf";          
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
        
        
        
  
        
  /*******************LECTOR CODIGO BARRA************************/  
 $(document).ready(function() {
    var App = {
        init: function(){
            let config = {
                inputStream: {
                    type : "LiveStream",
                    constraints: {
                        width: {min: 640},
                        height: {min: 480},
                        facingMode: "environment",
                        aspectRatio: {min: 1, max: 2}
                    }
                },
                locator: {
                    patchSize: "large",
                    halfSample: false
                },
                numOfWorkers: 2,
                frequency: 10,
                decoder: {
                    readers: [
                        {
                            format: "code_128_reader",
                            config: {}
                        },
                        {
                            format: "ean_reader",
                            config: {}
                        }
                    ],
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                        boxFromPatches: {
                            showTransformed: true,
                            showTransformedBox: true,
                            showBB: true
                        }
                    }
                },
                locate: true
            };
            
            let lastResult = null;
            
            Quagga.init(config, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                App.attachListeners();
                setTimeout(()=>{ Quagga.start(); alert("Listo para escanear.");}, 1000);
            });
        },
        
        attachListeners: function() {
            var self = this;
            
            $('#barcodeModal').on('hidden.bs.modal', function (e) {
              Quagga.stop();
              App.lastResult = null;
            });
        }
    };
    
    $('#barcodeModal').on('shown.bs.modal', function (e) {
        App.init();
      
        Quagga.onProcessed(function(result) {
            var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;
            
            if (result) {
                if (result.boxes) {
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function (box) {
                        return box !== result.box;
                    }).forEach(function (box) {
                        Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                    });
                }
    
                if (result.box) {
                    Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                }
    
                if (result.codeResult && result.codeResult.code) {
                    Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                }
            }
        });
    
        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
    
            if (App.lastResult !== code) {
                App.lastResult = code;
                document.getElementById("codProducto").value = code;
                $scope.idProducto = code;
                $scope.buscarProd();
                $("#barcodeModal").modal("hide");

               
            }
        });
        
        document.getElementById("codProducto").value = "";
                     $scope.idProducto = ""
        
    });
});
        
        
$scope.deleteProdListado = function(prod, prodIndex){    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=eliminarProdList&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.idPedido, producto:prod.id_prod}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                
                     $scope.detPedidoLista.splice(prodIndex, 1);
                    alertify.success("Producto eliminado con exito");
                    
                }else{
                    alertify.error("Error al eliminar producto, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
};        
     
        
$scope.conultarNuboxFolioBoleta = function(prod, prodIndex){     
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
                }else{
                    $scope.deleteProdListadoConf(prod, prodIndex);
                    
                }          
              
          }).error(function(error){
                        console.log(error);
    
    });  
    
};         
        
        
$scope.deleteProdListadoConf = function(prod, prodIndex){

    alertify.confirm("Esta seguro que desea eliminar el producto <h3>"+prod.nombreProd+"</h3>?", function (e) {
    if (e) {
          $scope.deleteProdListado(prod, prodIndex);    
    } 
    }); 

    
};        
        
        
        
$scope.verDetallePedidoInventario = function(pedidoIndex){
    
   document.getElementById('componenteInventario').style.display          = 'none'; 
   document.getElementById('divPedido').style.display                     = 'none';      
   document.getElementById('divPedidoDetalle').style.display             = 'block';    
    
    $scope.detPedidoLista           = []; 
    
    $scope.razonMod     =  pedidoIndex.nombre;
    $scope.direccionMod =  pedidoIndex.direccion;
    $scope.idPedido     =  pedidoIndex.id_pedido;    
    

    
    
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarDetallePedidoProd&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:pedidoIndex.id_pedido,
                                       idProd:$scope.idProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
   			  
                $scope.detPedidoLista = data;
        
          }).error(function(error){
                        console.log("error Listar Detalle Pedido: "+error);
    
      });
    

}



$scope.ventaPrecioDifPart = function(){
    
if($scope.precioDifPr == "" || $scope.precioDifPr ==0 ){    
       
    alertify.alert("Debe seleccionar un producto para poder realizar esta acción.");
    
} else {
    
     alertify.confirm("Desea aumentar el precio particular a la venta ?", function (e) {
        if (e) {
             $scope.prodPrecioAumt = $scope.precioDifPr;
            document.getElementById("prodPrecioAumt").value = $scope.precioDifPr ;
        } 
    }); 
}   
}



$scope.buscarProdAgregar = function(){
    
 
    

var length = $scope.detPedidoLista.length;
var objProd = new Object();
var banderaSel = "false";

for(var i = 0; i < length; i++) {
        objProd = $scope.detPedidoLista;
        if(objProd[i].id_prod == $scope.idProducto){
             banderaSel ="true";
           break;
        }             
}
    
    
    

    
if($scope.idProducto !=""){

    if(banderaSel=="false"){

       $http({
                 method : 'POST',
                 url : 'FunctionIntranet.php?act=listarProductosCajas&tienda='+$scope.tiendaSeleccionada,
                 data:  $.param({idProd:$scope.idProducto}),
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
              }).success(function(data){
                    console.log(data);                

                        $scope.productoAdd = data;

                     if(data.length != 0){

                         $scope.prodIdPrec       = data[0].id;
                         $scope.prodNombPrec     = data[0].nombreProd;
                         $scope.prodPrecioCost   = parseInt(data[0].precioVenta);
                        // $scope.selPorcentaje    = parseInt(data[0].porcentaje);
                        $scope.precioPart    = parseInt(data[0].precioPart);
                        $scope.precioDifPr    = parseInt(data[0].precioDifPr);


                       $("#myModalModProd").modal();

                     }else{
                         $scope.idProducto="";
                         alertify.alert("El Producto no existe."); 
                     }




              }).error(function(error){
                            console.log(error);

        });

    }else if(banderaSel=="true"){
         alertify.alert("El producto que desea agregar ya se encuentra en la lista."); 
    }
    
    
}else{
     alertify.alert("Favor ingresar codigo del producto para agregar a la lista."); 

 }
  
};
     
 
$scope.verDetallePedidoSalirDetalle = function (){
     document.getElementById('divPedidoDetalle').style.display        = 'none';    
     document.getElementById('divPedido').style.display               = 'block';    
     document.getElementById('componenteInventario').style.display    = 'none'; 
}

$scope.addProdListadoConf = function(){
var mensaje="";    
if($scope.prodPrecioCant > 0){
    
if($scope.accionProdLis=="M"){
    mensaje="Esta seguro que desea modificar el producto "+$scope.prodNombPrec+"?";   
}else{
     mensaje="Esta seguro que desea agregar el producto "+$scope.prodNombPrec+"?";   
}    
    
    
     alertify.confirm(mensaje, function (e) {
        if (e) {
          $scope.addProdListado();
        } 
    });  

}else{
    
    alertify.alert("Favor ingresar cantidad para poder realizar la accion.");
}        

}


      
$scope.addProdListado = function(accion){
    $scope.accionProdLis ="A";
$scope.auxSelProd       = [];
     var arrayClieDet = [];

                var objDeta = new Object();
                 objDeta.id_ped         = $scope.idPedido;
                 objDeta.id             = parseInt($scope.prodIdPrec);
                 objDeta.cantidadProd   = $scope.prodPrecioCant; 
                 objDeta.precioVenta    = parseInt($scope.prodPrecioCost) ; /*- (parseInt($scope.prodPrecioCost)  * ($scope.selPorcentaje/100));*/
    
                 objDeta.descuento      = ($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc);
                 objDeta.aumento        = ($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt); 
    
    objDeta.totalProd      = (parseInt((parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))); /*- (parseInt((parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) * ($scope.selPorcentaje/100);*/
    
    objDeta.precio_vendido = (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt )));/*- 
                     
                     (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt ))) * ($scope.selPorcentaje/100);*/
    
    arrayClieDet.push(objDeta);

      var objDetaPed= JSON.stringify(arrayClieDet);   
    
             $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=agregarProdListado&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrayClieDet:objDetaPed, accionProd:$scope.accionProdLis}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    
                    if($scope.accionProdLis=="M"){
                       
                       $scope.guardarPrecMod();
                       alertify.success("Producto modificado correctamente.");    
                       
                    }else if($scope.accionProdLis=="A"){
                    
                      $scope.auxSelProd.nombreProd     =   $scope.prodNombPrec;
                      $scope.auxSelProd.id_prod        =  $scope.prodIdPrec ;
                      $scope.auxSelProd.precio_vendido =  (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? 0: $scope.prodPrecioDesc) +  parseInt($scope.prodPrecioAumt == undefined ? 0: $scope.prodPrecioAumt)));
                     /* - (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? 0: $scope.prodPrecioDesc) +  parseInt($scope.prodPrecioAumt == undefined ? 0: $scope.prodPrecioAumt)))    * ($scope.selPorcentaje/100)*/
                        ;
                      $scope.auxSelProd.aumento        =  ($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt) 
                      $scope.auxSelProd.descuento      =  ($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) 
                      $scope.auxSelProd.cantidad       =  $scope.prodPrecioCant;
                     
                    $scope.auxSelProd.total          =  (parseInt(parseInt($scope.prodPrecioCost - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) ; /*- 
                      (parseInt(parseInt($scope.prodPrecioCost - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) *($scope.selPorcentaje/100) ;*/
                      $scope.auxSelProd.precioVenta     = $scope.prodPrecioCost; /*- ($scope.prodPrecioCost  * ($scope.selPorcentaje/100));*/
                        
                        
                      $scope.detPedidoLista.push($scope.auxSelProd);                      
                      $scope.auxSelProd       = [];
                      alertify.success("Producto agregado correctamente.");
                    
                    }
                 $("#myModalModProd").modal("hide");   
                    $scope.limpiarProdList();  
                    $scope.accionProdLis="";
                }else if(respuesta=="0"){
                    alertify.error("Error al agregar producto, favor contactar con el Administrador del Sistema.");
                }else {
                    
                 alertify.alert("Los siguientes productos se encuentran sin Stock o la cantidad ingresada es mayor al Stock existente:"+data+ "<br/>Favor volver a seleccionar los productos señalados, ingresando nuevamente el Tipo y la Marca.");
                    
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
    
    
}



$scope.guardarObservacionInvt = function(obsCLie, cli){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=guardarObsInvt&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idObs:obsCLie,
                             idClie:cli.id                         
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="0"){
                
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          

          }).error(function(error){
                console.log(error);
        });
}    



$scope.limpiarProdList= function(){
     $scope.prodNombPrec    = "";
     $scope.prodIdPrec      = "";
     $scope.prodPrecioCost  = "";
     $scope.prodPrecioVent  = "";
     $scope.prodPrecioAumt  = 0;
     $scope.prodPrecioDesc  = 0;
     $scope.prodPrecioCant  = "";
     $scope.prodPrecioTotal = "0";
    
};


}]);