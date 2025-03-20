angularRoutingApp.directive("fileInput", function($parse, $q, $timeout){  
    return{  
           link: function($scope, element, attrs){  
                element.on("change", function(event){  
                    let files = event.target.files;  
                    //console.log(files[0].name);  
                    getFileBuffer(files[0]).then(function(resp) {
                        $parse(attrs.fileInput).assign($scope, resp);
                        $timeout(function() {
                            $scope.$apply();  
                        });
                    });
                });  
           }  
    }
    function getFileBuffer(file) {
        let deferred = new $q.defer();
        let reader = new FileReader();
        reader.onloadend = function(e) {
            deferred.resolve(e.target.result);
        };
        reader.onerror = function(e) {
            deferred.reject(e.target.error);
        };
        reader.readAsDataURL(file);
        return deferred.promise;
    }
 });

angularRoutingApp.controller('controllerCierresCajas', ['$scope', '$http',
    function ($scope, $http) {   
        
         $scope.listadoCierres = [];
         $scope.listadoProductoVenta = [];
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
            $scope.tiendaBase = "";
        $scope.id_usuario = "";
            $scope.codTipoCobro             = 0;    

     $scope.selecVenta = [];
        $scope.bloquearTipoDoc  = false;
        
$scope.init = function () {
            
  if($scope.tipoIdLogin == '1' || $scope.tipoIdLogin == '9' || $scope.tipoIdLogin == '7'){
             $scope.bloquearTipoDoc = true;
      }
   

    if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }    
    
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
    
      document.getElementById('desdeb').value= year+"-"+month+"-"+daym;
      document.getElementById('hastab').value= year+"-"+month+"-"+daym;
    
    
   
$scope.listarCierresCajas();
};
     
        
        
 $scope.preVerSolicitudDetalle = function(sol){    
         $scope.listadoVentas = [];
         $scope.idReciboNumero      = sol.id_caja;

}        

 $scope.anularPedido2  = function (index){
    $scope.idPedido = index.id_pedido;
    $scope.idFolio = index.folio;
    $("#myModalObserPedidoAnular").modal();
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
        
        
$scope.anularPedido = function () {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=anularVentaTienda&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idInforme:$scope.idPedido,
                             observacion:$scope.observacionVendAnular,
                             tienda:$scope.tiendaBase}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                 var respuesta = data.charAt(data.length-1);
                
                if(respuesta==1){                
                    alertify.success("Venta anulada.");            
                    $("#myModalObserPedidoAnular").modal("hide");
                    $scope.listarVentasGeneradasTiendas();

                }else{
                    alertify.error("Error al anular venta, favor contactar con el Administrador del Sistema.");
                }
        
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
 
        
$scope.listarCierresCajas =  function () { 
    
    $scope.listadoCierres       = [];
    $scope.listadoProductoVenta = [];
    
      var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
    
    if($scope.bloquearTipoDoc == true){
          $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarCierreCajasMultiple&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({fInicio:desde, 
                                        fFin:hasta

                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
      
                        $scope.listadoCierres = data;
              
          }).error(function(error){
                        console.log(error);
    
          });
    
    }else{
                $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarCierreCajas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({fInicio:desde, 
                                        fFin:hasta

                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
      
                        $scope.listadoCierres = data;
              
          }).error(function(error){
                        console.log(error);
    
          });                                                
    
                                                        
    }
    
    /*
              $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidosTiendas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario: $scope.idUsuarioLogin,
                                        fInicio:desde, 
                                        fFin:hasta

                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
      
                        $scope.listadoProductoVenta = data;
              
          }).error(function(error){
                        console.log(error);
    
          });
    */
    

    
}



$scope.getValorSumaMonto = function(){
    var total = 0;
    for(var i = 0; i < $scope.listadoVentas.length; i++){
        var totalPed = $scope.listadoVentas[i];
        if(totalPed.anulada != "S"){ 
                total += parseInt(totalPed.total);
        }

           
    }
    
      
    return total;
};    



        
$scope.listarVentasGeneradasTiendas = function(cierres){
    var tiendaBase="";
    var usuario="";
     if(cierres == null){
        tiendaBase = $scope.tiendaBase;
        usuario = $scope.id_usuario; 
    }else{
         tiendaBase   = cierres.tiendaBase;
          usuario = cierres.id_usuario; 
    }
    
    
     $scope.listadoVentas = [];
   //  var desde =  document.getElementById('desdeb').value= year+"-"+month+"-"+daym;
    // var hasta =  document.getElementById('hastab').value= year+"-"+month+"-"+daym;            
      var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
    
    
    
    
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentasGeneradasTiendas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({desde:desde, hasta:hasta, tienda:tiendaBase, usuario:usuario}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       
            $scope.listadoVentas = data;
            $scope.tiendaBase    = cierres.tiendaBase;
            $scope.id_usuario    = cierres.id_usuario;

            
            $scope.getValorSumaMonto();

          }).error(function(error){
                        console.log(error);
    
    });   
}





$scope.guadarArchivoInforme = function(){
    
        
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
    method: 'POST',
    url: 'FunctionIntranet.php?act=subirArchivoCaja&tienda='+$scope.tiendaSeleccionada, 
    data: $.param({ idCaja: $scope.idReciboNumero
                   , imagen: $scope.variableBase64 }),
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
}).success(
    function(res) {
        
        
          var respuesta = res.charAt(res.length-1);
      
                 if(respuesta=="1"){                
                       alertify.success("Archivo subido con exito!");
                    }else{
                        
                        if(respuesta=="2"){
                             alertify.error("Error al subir archivo.");
                        }else if(respuesta=="3"){
                              alertify.error("La imagen debe ser png.");
                        }else if(respuesta=="0"){
                        alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

                        }
                        
                    }
        
          setTimeout($.unblockUI, 1000); 
    },
    function(err) {
          setTimeout($.unblockUI, 1000); 
                             alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

    }
);

};    
        
 $scope.verDetalle = function (prod) {
    
    $scope.idVenta =  prod.id_pedido;
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listaDetalleTiendas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idVenta:prod.id_pedido, tienda:$scope.tiendaBase}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       
            $scope.detalleVentas = data.detalles;
            $scope.pedidoDescuento = data.pedidoDescuento;
          }).error(function(error){
                        console.log(error);
    
    });   

};        
       
        
        
$scope.cargarPedidoCobro= function (pedidoIndex) {
    
    $scope.folio = pedidoIndex.folio;    

    $scope.idPedidoTienda = pedidoIndex.id_pedido;    
    $scope.codTipoCobro  =  pedidoIndex.estado_cobranza;     
    

          $scope.cobros = {
          availableOptions: [
            {id: '0', name: '-- Tipo de Cobro --'},  
            {id: '1', name: 'Efectivo'},
            {id: '5', name: 'Debito'}  ,
            {id: '6', name: 'Transferencia'}  

          ],
          selectedOption: {id: $scope.codTipoCobro} //This sets the default value of the select in the ui
        }

    
}
        
 $scope.modificarPedidoCobro = function(){
    
    var fPago = $scope.cobros.selectedOption.id;
     
     
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarVentaTienda&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idPedidoTienda, idPago:fPago, tienda:$scope.tiendaBase}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Cobro guardado con exito!");
                    $scope.idPedidoTienda="";
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
          
            
        $scope.listarVentasGeneradasTiendas (); 
        
          }).error(function(error){
                        console.log(error);
    
    });    

};              
        
        
        
     
$scope.paginado = function(){
    
    
          $scope.currentPage=1;
      $scope.numLimit=6;
      $scope.start = 0;
      $scope.$watch('conf.myData',function(newVal){
        if(newVal){
         $scope.pages=Math.ceil($scope.conf.myData.length/$scope.numLimit);
   
        }
      });
      $scope.hideNext=function(){
        if(($scope.start+ $scope.numLimit) < $scope.conf.myData.length){
          return false;
        }
        else 
        return true;
      };
       $scope.hidePrev=function(){
        if($scope.start===0){
          return true;
        }
        else 
        return false;
      };
      $scope.nextPage=function(){
        console.log("next pages");
        $scope.currentPage++;
        $scope.start=$scope.start+ $scope.numLimit;
        console.log( $scope.start)
      };
      $scope.PrevPage=function(){
        if($scope.currentPage>1){
          $scope.currentPage--;
        }
        console.log("next pages");
        $scope.start=$scope.start - $scope.numLimit;
        console.log( $scope.start)
      };
    
}

$scope.syncCierreCaja = function (id_caja) {
  alertify.confirm("Esta seguro que desea sincronizar el cierre de caja?", function (e) {
    if (e) {
      $http({
          method : 'POST',
          data:  $.param({data: {id_caja: id_caja}}),
          url : 'FunctionIntranet.php?act=syncCierreCaja&tienda='+$scope.tiendaSeleccionada,
          headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

      }).success(function(data){
        if(data == 1){
          alertify.success("Cierre de Caja Sincronizado");
          const cierre = $scope.listadoCierres.find(c=> c.id_caja == id_caja);
          cierre.centralizado = 1;
        } 
        if(data == 0) alertify.success("Se produjo un error al sincronizar el cierre de caja. Intente nuevamente desde Resumen.");
      }).error(function(error){
          alertify.success("Se produjo un error al sincronizar el cierre de caja. Intente nuevamente desde Resumen.");
      });  
    }
  });
}
        
        
        
      
        
        
}]);