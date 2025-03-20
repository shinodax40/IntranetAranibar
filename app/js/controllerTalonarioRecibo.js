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




angularRoutingApp.controller('controllerTalonarioRecibo', ['$scope', '$http','$cookies','$cookieStore', '$log',
    function ($scope, $http, $cookies, $cookieStore, $log) {
            $scope.listCliente = [];
            $scope.idCliente   = "";
            $scope.auxSelNota  = [];
            $scope.dgVentas    = [];
            $scope.rcTotal     = 0;
            $scope.rcAbono     = 0;
            $scope.rcSaldo     = 0;
            $scope.tipoBusqueda      = "0";
            $scope.tipoBusquedaPago  = "1";
            $scope.estadoPago="0";
            $scope.variableBase64="";
            $scope.nReciboTalonario="";
            $scope.nombreClieSel="";
        
         $scope.customFi          = false;     
         $scope.customFe          = false;
         $scope.customFolio       = false;
         $scope.nombreClie        = false;
        

        
        
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
        
   $scope.init = function(){
    
    if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }           
    
   document.getElementById('fIngreso').value= year+"-"+month+"-"+daym;
   document.getElementById('fIngresoHasta').value= year+"-"+month+"-"+daym;
    
    
   document.getElementById('fEntrega').value= year+"-"+month+"-"+daym;
   document.getElementById('fEntregaHasta').value= year+"-"+month+"-"+daym;
    
         $scope.customFi = true;     
         $scope.customFe = false;
         $scope.customFolio = false;
         $scope.nombreClie = false;
    
     document.getElementById('divPedidoListado').style.display    = 'block';    
     document.getElementById('divPedidoModificar').style.display  = 'none';   
    
    
    
    
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
    
    $scope.listarSol();
}



$scope.seleccionarClienteNombre = function(obj, objTipo){     
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
                     $scope.idCliente        =  $scope.auxClientes[0].id_cliente; 
                     $scope.nombreClieSel       =  $scope.auxClientes[0].nombre; 
            
            
          }).error(function(error){
                        console.log("error: "+data);

        });    
};   
        
        
$scope.agregarNotaPedido = function(){     
    
        if($scope.idCliente != ""){

        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarNotaTotalPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idCliente:$scope.idCliente,
                             nPedido:$scope.nPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  
                $scope.auxSelNota = [];
            
              if(data[0].total!=""){
                       $scope.auxSelNota.total    = data[0].total;
                       $scope.auxSelNota.nPedido  = $scope.nPedido;  
                       $scope.auxSelNota.nTipo    = "not";   
                       $scope.auxSelNota.nFolio   = "";

                       $scope.dgVentas.push($scope.auxSelNota);
                       $scope.nPedido = "";
                       $scope.getTotal(); 
                }else{
                    alertify.alert("No se pudo agregar items al detalle. Validar que el pedido este en estado ENTREGADO, lo contrario comunicarse con el Administrador del Sistema.");
                }
            
            
          }).error(function(error){
                        console.log("error: "+data);

        }); 
            
        }else{
          alertify.alert("Para poder agregar Items al detalle, seleccionar Cliente");
                                      
    }    
                
};    
   
        /*
        
$scope.confirmarValidarRuta = function (sellPedido) { 
    alertify.confirm("Esta seguro que desea validar Recibo Dinero ?", function (e) {
        if (e) {                     
            $scope.validarInforme(sellPedido);            
        }
    });    
};	*/
          
$scope.validarInforme = function(){

       $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=validarReciboCobranza&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idInforme: $scope.idReciboNumero}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               

                      // alertify.success("Actulizacion generado con exito!");
           $scope.listarSol();

          }).error(function(error){
                console.log(error);
                $scope.loadingProd = false;

        });  

};       
        
        
$scope.agregarNotaFolio = function(){  
    
    if($scope.idCliente != ""){
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarNotaTotalFolio&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idCliente:$scope.idCliente,
                             nFolio:$scope.nFolio}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  
                $scope.auxSelNota = [];
            
              if(data[0].total!=""){
                       $scope.auxSelNota.total   =  data[0].total;
                       $scope.auxSelNota.nFolio   = $scope.nFolio;
                       $scope.auxSelNota.nPedido  = data[0].id_pedido;   
                       $scope.auxSelNota.nTipo   = "fact";   
                       $scope.dgVentas.push($scope.auxSelNota);
                       $scope.nFolio ="";
                       $scope.getTotal(); 
                       
                }else{
                    alertify.alert("No se pudo agregar items al detalle. Validar que el pedido este en estado ENTREGADO, lo contrario comunicarse con el Administrador del Sistema.");
                }

            
          }).error(function(error){
                        console.log("error: "+data);

        });    
    }else{
          alertify.alert("Para poder agregar Items al detalle, seleccionar Cliente");
                                      
    }    
        
};           
                
        
$scope.getTotal = function(){
    var total = 0;
    for(var i = 0; i < $scope.dgVentas.length; i++){
        var product = $scope.dgVentas[i];
        total += (product.total);
    }
        $scope.rcTotal = formatNumero(total.toString());     
        $scope.rcAbono = formatNumero(total.toString());
}; 
        
        
 $scope.getTotalRecibo = function(){
    var aTotal     =  replaceAll($scope.rcTotal, ".", "" );
    var bTotal     =  replaceAll($scope.rcAbono, ".", "" );
    var resulTotal =  aTotal-bTotal;
     
   $scope.rcSaldo  = formatNumero(resulTotal.toString());
   $scope.rcAbono  = formatNumero(bTotal.toString());
};         
     

        
        
        
        
$scope.validarCliente = function () {
          if(document.formCliente.numeRecibo.value==""){
                alertify.alert("Ingresar Numero Recibo"); 
                document.formCliente.numeRecibo.focus();
                       return 0;
          }else if(document.formCliente.fechaCobro.value==""){
               alertify.alert("Ingresar fecha cobro"); 
                document.formCliente.fechaCobro.focus();
                      return 0;
          }else if($scope.dgVentas.length == 0){
                 alertify.alert("Ingresar items al detalle"); 
              return 0;
          }else if($scope.tipoBusqueda == "0" || $scope.tipoBusqueda == ""){
                 alertify.alert("Seleccionar tipo de pago"); 
              return 0;
          }else if($scope.variableBase64 == ""){
              
              if($scope.tipoBusqueda =="3" || $scope.tipoBusqueda =="6"  ){
                 alertify.alert("Seleccionar Imagen"); 
                 return 0;
                 }
                 
          }
    
    
      
 
    
       $scope.confirmarSolicitud();
      
}        
        
        
        
 $scope.confirmarSolicitud = function () { 
    

       alertify.confirm("Esta seguro que desea ingresar recibo ?", function (e) {           
         if(e) {                     
              $scope.insertarSolicituds();            
         }
       });    
   
}    

           
        
 $scope.insertarSolicituds =  function(){
        var arrayProd    = [];
        var totalP       = replaceAll($scope.rcTotal, ".", "");
        var totalB       = replaceAll($scope.rcAbono, ".", "");
        for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.nPedido  = $scope.dgVentas[i].nPedido;
                     objDeta.nFolio   = $scope.dgVentas[i].nFolio;
                     objDeta.nTipo    = $scope.dgVentas[i].nTipo;  
                     objDeta.total    = $scope.dgVentas[i].total;  

                     arrayProd.push(objDeta);
           }     
                 var objDeta    = JSON.stringify(arrayProd);

  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=insertarReciboUsuario&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idClie:$scope.idCliente, 
                                        fCobro:angular.element(document.querySelector("#fechaCobro")).val(), 
                                        nRecibo:$scope.numeRecibo, 
                                        tipPago:$scope.tipoBusqueda,
                                        totalRecibo:totalP,
                                        totalAbono:totalB,
                                        observacion:$scope.observacionRecibo,
                                        idUsuario: $scope.idUsuarioLogin,
                                        objDeta:objDeta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
      
      // var respuesta = data.charAt(data.length-1);
       var respuesta = data;
      $scope.nReciboTalonario = data.trim();

                 if(respuesta!=""){                
                        alertify.success("Solicitud generado con exito!, ID: "+respuesta);
                       
                     
                          if($scope.tipoBusqueda =="3" || $scope.tipoBusqueda =="6"  ){
                               $scope.idReciboNumero = respuesta.trim();
                               $scope.guadarArchivoInforme();                             
                           }
                     
                                   alertify.confirm("Esta seguro que desea IMPRIMIR RECIBO DINERO ?", function (e) {           
                                     if(e) {                     
                                          $scope.imprimirReciboDinero();   
                                            $scope.limpiarDatas();
                                     }else{
                                         $scope.limpiarDatas();
                                     }
                                   });    

                        
                         
                    }else{
                        alertify.error("Error al insertar solicitud!, favor contactar con el administrador del Sistema.");
                    }
        
        
          }).error(function(error){
                        console.log(error);
    
    });
 };
           
        
        
function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}
        
function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}
        
        
 $scope.selTipoComprador = function (rdo) {   
     $scope.formaPagoSel = rdo;
 
    if(rdo=="Efectivo"){   
        $scope.tipoBusqueda ="1";
    }else if(rdo=="Cheque" ){
        $scope.tipoBusqueda ="3";
    }else if(rdo=="Transferencia"){
         $scope.tipoBusqueda ="6";

         
    }
 
};
        
        
            
        
 $scope.selTipoBusq = function (rdo) {   
 
   if(rdo=="FechaIngreso"){   
         $scope.customFi = true;     
         $scope.customFe = false;
         $scope.customFolio = false;
         $scope.nombreClie = false;
        $scope.tipoBusquedaPago ="1";
    }else if(rdo=="FechaEntrega" ){
            $scope.customFi = false;     
         $scope.customFe = true;
         $scope.customFolio = false;
         $scope.nombreClie = false;
        $scope.tipoBusquedaPago ="2";
    }else if(rdo=="Cliente"){
        $scope.customFi = false;     
         $scope.customFe     = false;
         $scope.customFolio  = true;
         $scope.nombreClie   = false;
         $scope.tipoBusquedaPago ="4";
    }else if(rdo=="Folio"){
             $scope.customFi     = false;     
          $scope.customFe     = false;
          $scope.customFolio  = false;
          $scope.nombreClie   = true;
         $scope.tipoBusquedaPago ="3";

         
    }
 
};        
        
        
        
        
$scope.preCrearSolicitud = function (){
    document.getElementById('divPedidoListado').style.display             = 'none';    
    document.getElementById('divPedidoModificar').style.display             = 'block';  
    $scope.observacionRecibo = "";
}        
        
        
$scope.listarSol = function(){
    

      var nombre    = angular.element(document.querySelector("#nombCliente")).val();
      var folio     = angular.element(document.querySelector("#folio")).val();
      var fIngreso  = angular.element(document.querySelector("#fIngreso")).val();
      var fEntrega  = angular.element(document.querySelector("#fEntrega")).val();
    
      var fIngresoHasta  = angular.element(document.querySelector("#fIngresoHasta")).val();
      var fEntregaHasta  = angular.element(document.querySelector("#fEntregaHasta")).val();
    
      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarRecibos&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({tipBusq: $scope.tipoBusquedaPago,
                     folio:   folio,
                     nombre:  nombre,
                     fIngreso: fIngreso,
                     fEntrega: fEntrega,
                     fIngresoHasta: fIngresoHasta,
                     fEntregaHasta: fEntregaHasta,
                     tipoUsuario: $scope.tipoUsuarioLogin,
                     idUsuario: $scope.idUsuarioLogin
                    }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listSolicitud = data;
           
  }).error(function(error){
        console.log(error);
      

});  
    
   
    
    
};
        
        
$scope.modificarPedidoEstado = function(){
    var arrCobro = [];
    
    var objCobro= new Object();            
            objCobro.observacion     = $scope.reciboObservacion;
            objCobro.estadoPedido    = $scope.tipoPagos.selectedOption.id;    
            objCobro.pedido          = $scope.idReciboNumero;       

    arrCobro.push(objCobro);
    
 var objCob= JSON.stringify(arrCobro);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarTalonario&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrTalonario:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Estado guardado con exito!");
                    
                    $scope.validarInforme();
                    
                }else{
                    alertify.error("Error al guardar talonario, favor contactar con el Administrador del Sistema.");
                }
          
          
        
          }).error(function(error){
                        console.log(error);
    
    });    

};     
        
        
   
$scope.guadarArchivoInforme = function(){
      
        
$http({
    method: 'POST',
    url: 'FunctionIntranet.php?act=subirArchivoTalonario&tienda='+$scope.tiendaSeleccionada, 
    data: $.param({ idTalonario: $scope.idReciboNumero
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
        
        
    },
    function(err) {
                             alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

    }
);

};        
               
        
        
 $scope.preVerSolicitudDetalle = function(sol){    
     
     $scope.idReciboNumero      = sol.id;
     $scope.reciboObservacion   = sol.observacion;
     $scope.estadoPago          = sol.modo_pago;
      $scope.nombProdMod        = sol.nombre;
    
     
     
     
     
             $scope.tipoPagos = {
  availableOptions: [
    {id: '0', name: '-- Tipo de Cobro --'},  
    {id: '1', name: 'Efectivo'},
    {id: '3', name: 'Documento o Cheque'},
    {id: '6', name: 'Transferencia'}        
  ],
  selectedOption: {id:$scope.estadoPago} //This sets the default value of the select in the ui
}
     

     
            $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetalleRecibo&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idRecibo: sol.id }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               

                    $scope.detaSoli = data;
                    $scope.observacionRecibo = data[0].observacion;

          }).error(function(error){
                console.log(error);


        });  
    
    
}        
         /*
$scope.verDetallePedidoSalirMod = function(){
      document.getElementById('divPedidoModificar').style.display           = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block'; 
      $scope.dgVentas         = [];
      $scope.tipoBusqueda      = "0";
      $scope.listarSol();
};    */
        
        
$scope.limpiarDatas = function () {
 document.getElementById('fIngreso').value                             = year+"-"+month+"-"+daym;
 document.getElementById('fIngresoHasta').value                        = year+"-"+month+"-"+daym;
 document.getElementById('divPedidoModificar').style.display           = 'none';      
 document.getElementById('divPedidoListado').style.display             = 'block'; 
    
$scope.numeRecibo        = "";
$scope.dgVentas          = [];
$scope.rcTotal           = 0;
$scope.rcAbono           = 0;
$scope.rcSaldo           = 0;
$scope.nPedido           = "";
$scope.nFolio            = "";
$scope.idCliente         = "";    
$scope.observacionRecibo = ""; 
$scope.tipoBusquedaPago  = "1";
$scope.listarSol();
};
        
        

        
        
$scope.eliminarNota = function (notIndex) {     
    $scope.dgVentas.splice(notIndex, 1); 
};    
        
        
        
        

$scope.imprimirReciboDinero = function(){
    
        var arrayDeta     = []; 
        var totalP       = replaceAll($scope.rcTotal, ".", "");
        var totalB       = replaceAll($scope.rcAbono, ".", "");
        var resulTotal   = totalP-totalB;
        $scope.rcSaldo   = resulTotal.toString();
    
         
          for (var i = 0; i <    $scope.dgVentas.length; i++) {          
                  var objDeta= new Object();           
                  var countFil = i + 1;        
                       
                        objDeta.nRecibo       =  $scope.nReciboTalonario;
              
                        objDeta.nombCliente   =  $scope.nombreClieSel;

                        objDeta.folio         =  $scope.dgVentas[i].nFolio;
                        objDeta.nota          =  $scope.dgVentas[i].nPedido;
                        objDeta.total         =  formatNumero($scope.dgVentas[i].total.toString());

                        objDeta.conteo        =  countFil;
                        objDeta.formaPago        =  $scope.formaPagoSel;

              
                    
                        objDeta.totalRecibo     =  formatNumero(totalP.toString());
                        objDeta.totalAbono      =  formatNumero(totalB.toString());
                        objDeta.totalSaldo      =  formatNumero($scope.rcSaldo.toString());
                     

                        arrayDeta.push(objDeta);
                     }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

                  $http({
                         method : 'POST',
                         url : '//192.168.1.128/admin/FunctionIntranet.php?act=imprimirTermicaReciboDinero&tienda='+$scope.tiendaSeleccionada,                    
                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 
         
        
                         $scope.limpiarDatas();
                      
                      }).error(function(error){
                            console.log(error);
                           

                    });


    
 }        
        
        
            

}]);