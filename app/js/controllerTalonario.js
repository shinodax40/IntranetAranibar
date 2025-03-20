angularRoutingApp.controller('controllerTalonario', ['$scope', '$http',

function ($scope, $http) {
    
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
$scope.fechaActual = daym+"/"+month+"/"+year;
$scope.horaActual  = mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();             
        
$scope.arrayDeta= [];       

	
$scope.estadoPedidoMenu = {
  availableOptions: [
	{id: '0', name: '-- Estado --'},  
	{id: '1', name: 'Pendiente'},
	{id: '2', name: 'Cobrado'},
	{id: '3', name: 'Anulado'},
	{id: '4', name: 'Transferencia'} ,
	{id: '5', name: 'Orden de No Pago'}  
      
      
  ],
  selectedOption: {id: '0'} //This sets the default value of the select in the ui
} 	
	
	

$scope.buscarTalonario  = function(){
	

	var  respTalo   = ($scope.talonarioList.selectedOption.id.toString() == "0" ? "":$scope.talonarioList.selectedOption.id.toString()); 
	var  respEstado = ($scope.estadoPedidoMenu.selectedOption.id.toString() == "0" ? "":$scope.estadoPedidoMenu.selectedOption.id.toString()); 
	
	
	
      var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
    
        $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=listarTalonarios&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id_talonario:respTalo,
                            desde:desde,
                            hasta:hasta,
						    id_estado:respEstado,
						    numDoc: $scope.numDoc}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);        
            
                 $scope.listadoTalonario  = data;
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};            
              

$scope.initTalonario = function(){    
     $http.get('FunctionIntranet.php?act=listarProvSelection&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);     
           $scope.proveedores = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error listar proveedores: "+data);
      });
}; 
    
    
$scope.init = function(){   
     $http.get('FunctionIntranet.php?act=registroTalonario&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);     
           $scope.regTalonario = data;
		 
		 
                objDeta= new Object();
                objDeta.name      = "--Talonarios--";
                objDeta.id        = 0; 
                arrayDeta.push(objDeta);
     
          
              for (var i = 0; i <    data.length; i++) {   
                        objDeta= new Object();
                        objDeta.name      = data[i].name;
                        objDeta.id        = data[i].id; 
                        arrayDeta.push(objDeta);
              }
           
           
           
           
           
           $scope.talonarioList = {
              availableOptions: arrayDeta,                      
              selectedOption: {id: 0} 
            }
		 
		 
		 
		 
		 
		 
		 
		 
            }).
            error(function(data, status, headers, config) {
                console.log("error listar talonarios: "+data);
      });
}; 
        
    
    
$scope.cargarDatos = function(index){
    
    $scope.estadoPedido = {
      availableOptions: [
        {id: '0', name: '-- Estado --'},  
        {id: '1', name: 'Pendiente'},
        {id: '2', name: 'Cobrado'},
        {id: '3', name: 'Anulado'},
	    {id: '4', name: 'Transferencia'} , 
	    {id: '5', name: 'Orden de No Pago'}            
      ],
      selectedOption: {id: index.id_estado} //This sets the default value of the select in the ui
    } 
    
    
    
     $scope.initTalonario();
    
    $scope.arrayDeta= [];  
    $scope.ncheque    = index.numero_doc;
    $scope.talonario  = index.id_talonario;
    $scope.monto      = index.monto;
    $scope.ordenDe    = index.orden_de;
    $scope.descripcion= index.descripcion;
    $scope.facturas   = index.facturas;
     $scope.codTalonario   = index.id;

    
    
    var arrayfecha = index.fecha_cobro.split('-');      
    document.getElementById('fcobro').value= arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];   
    
    
   /*var arrayFacturas = index.facturas.split(',');    
    
    
   for (var i = 0; i <    arrayFacturas.length; i++) {   
       
   var objDeta= new Object();
            objDeta.factura      =  arrayFacturas[i];
   $scope.arrayDeta.push(objDeta);
       
   }*/

    $scope.buscarTalonarioFact();
};
    
    
$scope.buscarTalonarioFact  = function(){
        $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=listarTalonariosFact&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id_talonario:$scope.codTalonario }),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);        
                 $scope.listadoTalonarioFactAux  = data;            
                 $scope.listadoTalonarioFact     = data;
            
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};        
    

$scope.eliminarFact = function(){
    alertify.confirm("¿Esta seguro que desea eliminar facturas?", function (e) {
        if (e) {
          $scope.deleteProdListado();
        } 
    });    
    
};
 
    
$scope.deleteProdListado = function(){    
      for(var i=0; i < $scope.arrayDeta.length; i++){
            $scope.arrayDeta.splice(i, 1);          
      }
    $scope.arrayDeta = [];
};  
    
    
$scope.buscarFacturas  = function(){
        $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=consultarFactura&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id_talonario:$scope.nfactura,
                            id_prove:$scope.proveedor.id_proveedor}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data); 

     
            
            if(data == "0"){
                
  
                      $http({
                            method : 'POST',
                            url : 'FunctionIntranet.php?act=consulFact&tienda='+$scope.tiendaSeleccionada,
                            data:  $.param({id_talonario:$scope.nfactura,
                                            id_prove:$scope.proveedor.id_proveedor}),
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

                          }).success(function(data){
                                        console.log(data); 
                              var respuesta = data.charAt(data.length-1);

                          if(respuesta == "1"){
                               var objDeta= new Object();
                               objDeta.num_factura    =  $scope.nfactura;
                               objDeta.id_proveedor   =  $scope.proveedor.id_proveedor;
                              
                               $scope.listadoTalonarioFact.push(objDeta); 
                           }else{
                                  alertify.alert("Factura no existe.");
                               
                           }
                     

                          }).error(function(error){
                                        console.log(error);

                      }); 
                
             }else{
                 alertify.alert("Factura ya se encuentra asociada a otro documento");
            }

          }).error(function(error){
                        console.log(error);
    
    }); 

};     
    
    
$scope.guardarTalonario  = function(){ 
    var arrayTolAux    = [];
    var arrayTol    = [];
    var arrayFac    = [];
 
    for(var i = 0; i < $scope.listadoTalonarioFact.length; i++){
            var objDeta = new Object();
             objDeta.numFact   = $scope.listadoTalonarioFact[i].num_factura;
             objDeta.idProv    = $scope.listadoTalonarioFact[i].id_proveedor;   
             objDeta.idTalo    =  $scope.codTalonario;         
        
             arrayTol.push(objDeta);
   }
    

    for(var i = 0; i < $scope.listadoTalonarioFactAux.length; i++){
            var objDeta = new Object();
             objDeta.numFact   = $scope.listadoTalonarioFactAux[i].num_factura;
             objDeta.idProv    = $scope.listadoTalonarioFactAux[i].id_proveedor;         
             arrayTolAux.push(objDeta);
   }    
    
    
    var objDeta = new Object();
     objDeta.idTalonario     = $scope.codTalonario;    
     objDeta.monto           = $scope.monto;
     objDeta.fcobro          = angular.element(document.querySelector("#fcobro")).val();
     objDeta.ordenDe         = $scope.ordenDe;
     objDeta.descripcion     = $scope.descripcion;
     objDeta.estado          = $scope.estadoPedido.selectedOption.id;
    
     arrayFac.push(objDeta);
        
         
   var objFactCabe      = JSON.stringify(arrayFac);    
   var objFactDet       = JSON.stringify(arrayTol);    
   var objFactDetAux    = JSON.stringify(arrayTolAux);    
    
    
    
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=guardarTalonario&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objFactCabe:objFactCabe,
                            objFactDet:objFactDet,
                            objFactDetAux:objFactDetAux}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);        
                    alertify.success("Documento guardado con exito!");                
            
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

$scope.generarPDFTalonario = function () {
  var arrayDeta = [];

for (var i = 0; i < $scope.listadoTalonario.length; i++) {  
     var objDeta= new Object();           
       var arrayfecha = $scope.listadoTalonario[i].fecha_cobro.split('-');            
                objDeta.nCheque      =  $scope.listadoTalonario[i].numero_doc;
                objDeta.estado       =  $scope.listadoTalonario[i].nombre;
                objDeta.monto        =  formatNumero($scope.listadoTalonario[i].monto);
                objDeta.fecha        =  arrayfecha[2]+"-"+arrayfecha[1]+"-"+arrayfecha[0];  
                objDeta.ordeDe       =  $scope.listadoTalonario[i].orden_de;
                objDeta.descripcion  =  $scope.listadoTalonario[i].descripcion;
                objDeta.facturas  =  $scope.listadoTalonario[i].facturas;        

        arrayDeta.push(objDeta);          
}
   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'detalle':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=gerexpTalonarioPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="Talonario.pdf";          
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
    
        
}]);