angularRoutingApp.controller('controllerCierreCajaCentralizada', ['$scope', '$http',
    function ($scope, $http) {   
        
         $scope.listadoCierres = [];
         $scope.cierre = null;
         $scope.observacionCierre="";
        
$scope.init = function () {
    

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
        
$scope.listarCierresCajas =  function () { 
    $scope.cierres = [];
    
    var desde  = angular.element(document.querySelector("#desdeb")).val();
    var hasta  = angular.element(document.querySelector("#hastab")).val();
    
    
    $http({
                method : 'POST',
                url : 'FunctionIntranet.php?act=listarCierreCajaCentralizada&tienda='+$scope.tiendaSeleccionada,
                data:  $.param({fInicio:desde, 
                                fFin:hasta

                                }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
    }).success(function(data){
        $scope.cierres = data;
        $scope.cierres.forEach(c => {
            c.data = JSON.parse(c.data);
        });
    }).error(function(error){
        console.log(error);
    });
    
}

$scope.verDetalle = function(cierre){
    $scope.cierre = cierre.data;
    console.log($scope.cierre);
    $scope.observacionCierre = cierre.observacion;
    $("#myVerCierre").modal('show');
}


$scope.getTotalRetiroEfectivo = function () {
    if(!$scope.cierre) return 0;
    return $scope.cierre.retiros.reduce((acc, curr) => acc + parseFloat(curr.total_efectivo), 0);
}

$scope.getTotalRetiroDebito = function () {
    if(!$scope.cierre) return 0;
    return $scope.cierre.retiros.reduce((acc, curr) => acc + parseFloat(curr.total_debito), 0);
}

$scope.getTotalRetiroTransferencia = function () {
    if(!$scope.cierre) return 0;
    return $scope.cierre.retiros.reduce((acc, curr) => acc + parseFloat(curr.total_transferencia), 0);
}

$scope.getTotalDescuentosCierre = function(){
    if(!$scope.cierre) return 0;
    return $scope.cierre.descuentos.reduce((acc, curr) => acc + parseFloat(curr.monto), 0);
}
         
}]);