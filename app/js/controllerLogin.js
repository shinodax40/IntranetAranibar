'use strict';
angularRoutingApp.controller('controllerLogin', ['$scope', '$http', '$rootScope', 'LocalStorage',
    function ($scope, $http, $rootScope, LocalStorage) {
	 $scope.grafParticular   = [];
	 $scope.grafMascotero    = [];
	 $scope.grafAlmancen     = [];
	 $scope.grafVeterinaria  = [];	
     $scope.cobradoVend      = [];
     $scope.seleccionTienda  = '';    
    
		
        
       $scope.$parent.title                = "Hola";
       $scope.$parent.img                  = "img/iconset-addictive-flavour-set/png/document-plaid-pen.png";
       $scope.$parent.showTopToggle        = false;
       $scope.$parent.customInter          = false;
       $scope.$parent.customInterLogin     = false;
       $scope.$parent.customInterEntrar    = false;
       $scope.$parent.customInterTransp    = false;
     //$scope.$parent.customInterBodega    = false;
       $scope.$parent.customSelCh          = false;
       $scope.$parent.customBotton         = false;

       $scope.$parent.customBottonFinanzas = false;     
     
       $scope.$parent.customBottonFinanzasAdministradorSupervisor = false;     

        
        $scope.$parent.customBottonFinanzasAdministrador=false;
       $scope.$parent.customBotton2        = false;
       $scope.$parent.customInterProd      = true;
       $scope.$parent.customInterModifPed  = true;
       $scope.$parent.customBottonNotaPedido = true;
       $scope.$parent.customImprimirValeBodega = false;
       $scope.$parent.customInterMarcar        = false;
       $scope.$parent.customInformeTransporte  = false;         
        $scope.$parent.customLogiCaja          = false;
        
        
       $scope.customPrecios=false
       $scope.customNomb=false; 
       $scope.rutMal=true;
     $("#myModalProductosPrecio").modal("hide");   
        
      document.getElementById('myPrecio').style.display                  = 'none';                             
	 // document.getElementById('graficoAdmin').style.display              = 'none';    
    //  document.getElementById('graficoAdminRango').style.display         = 'none'; 
    //document.getElementById('graficoAdminGeneral').style.display       = 'none'; 
    //  document.getElementById('graficoCantidadPedidos').style.display    = 'none'; 
		
		
var mydate=new Date();

var year=mydate.getYear();
if (year < 1000)
year+=1900;
var day=mydate.getDay();

var month=mydate.getMonth()+1;
if (month<10)
$scope.month=month;
		
var daym=mydate.getDate();
if (daym<10)
daym="0"+daym;	
		

        
 $('#myModallogininicial').modal({
        backdrop: 'static',
        keyboard: false
});
        
        
$scope.initListado = function () {
  $scope.cobradoVend = {
          availableOptions: [
            {id: '1', name: 'Barros Aranas'},
            {id: '2', name: 'Santa Maria'},
            {id: '3', name: 'Tucapel'},
            {id: '4', name: 'Asocapec'}  
          ],
          selectedOption: {id: 0} //This sets the default value of the select in the ui
        }     
}


$scope.seleccionarTienda = function (){
    
     $scope.seleccionTienda = $scope.cobradoVend.selectedOption.id;


}
        

        
           
 $scope.salir = function(){
    $("#myModalCrearCuenta").modal("hide");    

    $('#myModallogininicial').modal({
        backdrop: 'static',
        keyboard: false
    });
     
    
    $scope.rutLog="";
    $scope.nombreLog="";
    $scope.passLog="";
    $scope.celLog=""; 
    $scope.passLogConf=""; 

    LocalStorage.remove('sesion');
 };
        
        
$scope.ingresarCrearCuenta = function(){
    $("#myModallogininicial").modal("hide");    
    $('#myModalCrearCuenta').modal({
        backdrop: 'static',
        keyboard: false
    });

};
        
$('#rutLog').Rut({
  on_error: function(){  $scope.rutMal=false;
 }  
});
        
        

        
      /********************/
        
$(function() {
    $('#rutLog').keyup(function() {
           $scope.rutMal=true;
           $scope.customRut=false; 
           $scope.customRutCamp=false;            
    });
});        
        
$(function() {
    $('#nombreLog').keyup(function() {
           $scope.customNomb=false; 
    });
});        
        
$(function() {
    $('#celLog').keyup(function() {
           $scope.customCel=false; 
    });
});        
        
$(function() {
    $('#passLog').keyup(function() {
           $scope.customPass=false; 
            $scope.customPassTa=false;              

    });
});  
$(function() {
    $('#passLogConf').keyup(function() {
           $scope.customConfPassErr=false; 
    });
});            

    /*******************************/
        
$scope.crearCuenta = function(){
    
     if($scope.rutMal){
        if(document.getElementById("rutLog").value==""){
               $scope.customRutCamp=true; 
         }
      }else{          
          $scope.customRut=true; 
          $scope.rutMal=true;

      }
    
     if(document.getElementById("nombreLog").value==""){
           $scope.customNomb=true; 
     }
     if(document.getElementById("celLog").value==""){
           $scope.customCel=true; 
     }
    
    if(document.getElementById("passLog").value==""){
             $scope.customPass=true;         
    }else{
        if(document.getElementById("passLog").value.length < 4){
                            $scope.customPassTa=true;              
         }     
    }
    
    
    if(document.getElementById("passLog").value!=""){
        if(document.getElementById("passLog").value != document.getElementById("passLogConf").value){
              $scope.customConfPassErr=true;
        }
    }
    
    
if($scope.customRut==false && $scope.customRutCamp==false && $scope.customNomb==false && $scope.customCel==false && $scope.customPass==false && $scope.customConfPassErr==false && $scope.customPassTa ==false){
        $scope.guardarCliente();
    }
    
    
    
};
        
        
        
        
        
$scope.guardarCliente = function(accionCliente){
    
    var objClie = new Object();
    var arrClientes= [];

    objClie.rut       =   $.Rut.quitarFormato($scope.rutLog);
    objClie.nombre    =  $scope.nombreLog;
    objClie.pass      =  $scope.passLog;
    objClie.telefono  =  $scope.celLog;


    
    arrClientes.push(objClie);
    
    var objClientes    = JSON.stringify(arrClientes);
    
        
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=guardarClientePrecio&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrClie:objClientes}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
            var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                    
                    alertify.error("Rut ingresado ya existe, favor contactar con el administrador para regularizar su cuenta.");
                }else if(respuesta=="2"){
                    alertify.success("Usuario creado con exito.");
                    $scope.salir();
                }
  }).error(function(error){
        console.log(error);     

});
    
    
};        
        

        
        
$scope.cerrarCargando = function(){    
                  setTimeout($.unblockUI, 1000);     
};        
        
        
        

$scope.ingresarMenu = function(){   
    
 $scope.$parent.tiendaSeleccionada =        $scope.seleccionTienda;
    
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } });     
    
var usua = angular.element(document.querySelector("#usua")).val();
var pass = angular.element(document.querySelector("#contra")).val();
    

        
    
if($scope.seleccionTienda != ""){

    
if(usua.length!=0 || pass.length!=0){

     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=consultarUsuario&tienda='+$scope.$parent.tiendaSeleccionada,
     data:  $.param({usua:usua,
                     pass:pass}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
       
                 $scope.loginUsua = [];
                 $scope.loginUsua = data;
            
              if($scope.loginUsua.length==0){
                  setTimeout($.unblockUI, 1000); 
                  alertify.alert("La Clave Secreta ingresada no es correcta. Intentelo de nuevo, verificando las mayusculas y minusculas");
              }else{
                  $rootScope.sesion = {
                      tienda: $scope.$parent.tiendaSeleccionada,
                      datos: $scope.loginUsua
                  };
                  $scope.cerrarCargando();
                  var rolOfertas = ['Vendedor', 'Administrador', 'Finanzas', 'Supervisor'];
                  if (rolOfertas.includes($scope.$parent.tipoUsuarioLogin)) {
                      $scope.listarProductosOfertas();
                  }
                  LocalStorage.set('sesion', $rootScope.sesion);
              }
         
         
         
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
 
}else{
   alertify.alert("Favor ingresar Rut y Contraseña");   
                      setTimeout($.unblockUI, 1000); 

}
    
    
}else{
    
     alertify.alert("Favor seleccionar tienda.");   
     setTimeout($.unblockUI, 1000); 
}
    
 
}    
    



        
$scope.init = function () {
    
/*
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=tipoProductoPrecio&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
               $scope.tipos  = [];
               $scope.tipos  = data;
           
          }).error(function(error){
                        console.log(error);
    
    });    
  */  
    
    

};
        
$scope.initGrafico  = function(){    
 	$scope.totalMes = [];
	var banderaMes = false;	
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMes&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        

            for(var x=1; x < 13 ; x++){
                  banderaMes = false;
                  for (var i = 0; i < data.length; i++) {  
                     if(data[i].mes == x){
                           $scope.totalMes.push(parseInt(data[i].total));      
                           banderaMes  = true;
                         break;

                     }
                  }

                  if(banderaMes == false){
                          $scope.totalMes.push(0);

                  }
              } 
        
          $scope.initGraficoFran();

          }).error(function(error){
                        console.log(error);
    
    }); 
    
};      
        
        
$scope.initGraficoFran  = function(){   
	$scope.totalFranisca = [];
	var banderaMes = false;	
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesFrancisca&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
        /******************************/ 	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalFranisca.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalFranisca.push(0);

			  }
	      } 
		/************************/
        
         $scope.initGraficoBlanca();

          }).error(function(error){
                        console.log(error);
    
    }); 
}

$scope.initGraficoBlanca  = function(){
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesBlanca&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalBoris  = [0,0,0];

              for (var i = 0; i < data.length; i++) {         
                       $scope.totalBoris.push(parseInt(data[i].total));      
                }  
            
             $scope.initGraficoJuan();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};
        
$scope.initGraficoJuan  = function(){
    $scope.totalJuan = [];
	var banderaMes = false;	
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesJuan&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
        /******************************/ 	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalJuan.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalJuan.push(0);

			  }
	      } 
		/************************/
            
            
            
             $scope.initGraficoDiego();            
          }).error(function(error){
                        console.log(error);
    
    }); 

};
		
		
$scope.initGraficoDiego  = function(){
	
	$scope.totalDiego = [];
	var banderaMes = false;
	
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesDiego&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
      			
			
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalDiego.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalDiego.push(0);

			  }
	      } 
			
			 $scope.initGraficoCobro();
			
          }).error(function(error){
                        console.log(error);
    
    }); 

};		
		
   
/*        
$scope.initGraficoNico  = function(){
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesNico&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalNico  = [0];

              for (var i = 0; i < data.length; i++) {         
                       $scope.totalNico.push(parseInt(data[i].total));      
                }  
            
             $scope.initGraficoCobro();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};*/
        
  /*******************GRAFICA CANTIDAD VENTA*****************************************/      
$scope.cantidadGrafica = function(){
	var banderaMes=false;
	  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cantidadParticular&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsuario:$scope.idUsuarioLogin }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
            banderaMes=false; 
          
		   if(data.length != 0){
				for(var x=1; x < 13 ; x++){
				  banderaMes = false;
				  for (var i = 0; i < data.length; i++) {  
					 if(data[i].mes == x){
						 $scope.grafParticular.push(parseInt(data[i].total));
						   banderaMes  = true;
						 break;

					 }
				  }			  
				  if(banderaMes == false){
						  $scope.grafParticular.push(0);
				  }
			  }
		  }else{
				for(var x=1; x < 13 ; x++){				 
						  $scope.grafParticular.push(0);
				}			  	 
		  }
		  
		$scope.cantidadMascotero();
          }).error(function(error){
                        console.log(error);
    }); 	
}


$scope.cantidadMascotero = function(){	
		var banderaMes=false;
		  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cantidadMascotero&tienda='+$scope.tiendaSeleccionada,
  		                data:  $.param({idUsuario:$scope.idUsuarioLogin }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
            banderaMes=false;
			  
			  
		   if(data.length != 0){
  
						for(var x=1; x < 13 ; x++){
						  banderaMes = false;
						  for (var i = 0; i < data.length; i++) {  
							 if(data[i].mes == x){
								 $scope.grafMascotero.push(parseInt(data[i].total));
								 banderaMes  = true;
								 break;

							 }
						  }			  
						  if(banderaMes == false){
								  $scope.grafMascotero.push(0);
						  }
					  }
			 }else{
				for(var x=1; x < 13 ; x++){				 
						  $scope.grafMascotero.push(0);
				}			  	 
		     }  
			  
		 $scope.cantidadAlmacen();	  
			  
          }).error(function(error){
                        console.log(error);
    }); 
}


$scope.cantidadAlmacen = function(){	
		var banderaMes=false;
		  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cantidadAlmacen&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsuario:$scope.idUsuarioLogin }),			  
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
            banderaMes=false;
			 
			if(data.length != 0){  			  
						for(var x=1; x < 13 ; x++){
						  banderaMes = false;
						  for (var i = 0; i < data.length; i++) {  
							 if(data[i].mes == x){
								 $scope.grafAlmancen.push(parseInt(data[i].total));
								   banderaMes  = true;
								 break;

							 }
						  }			  
						  if(banderaMes == false){
								  $scope.grafAlmancen.push(0);
						  }
					  }
		  }else{
				for(var x=1; x < 13 ; x++){				 
						  $scope.grafAlmancen.push(0);
				}			  	 
		  }  	   
		
		  $scope.cantidadVeterinaria();	  
			  
          }).error(function(error){
                        console.log(error);
    }); 
}

$scope.cantidadVeterinaria = function(){	
		var banderaMes=false;

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cantidadVeterinaria&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsuario:$scope.idUsuarioLogin }),			  
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
            banderaMes=false;			  
				if(data.length != 0){    			  
					for(var x=1; x < 13 ; x++){
					  banderaMes = false;
					  for (var i = 0; i < data.length; i++) {  
						 if(data[i].mes == x){
							 $scope.grafVeterinaria.push(parseInt(data[i].total));
							   banderaMes  = true;
							 break;

						 }
					  }			  
					  if(banderaMes == false){
							  $scope.grafVeterinaria.push(0);
					  }
				  }
					
			 }else{
				for(var x=1; x < 13 ; x++){				 
						  $scope.grafVeterinaria.push(0);
				}			  	 
		  } 	
		 
		 $scope.graficaCantidad();
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}



/*************************************************************************/
		
		

$scope.graficaCantidad = function(){
	
new Chart(document.getElementById("bar-chart-grouped"), {
    type: 'bar',
    data: {
      labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
      datasets: [
        {
          label: "Particulares",
          backgroundColor: "#500787",
          data: $scope.grafParticular
        }, {
          label: "Mascoteros",
          backgroundColor: "#74D935",
          data: $scope.grafMascotero
        },
		  {
          label: "Almacenes",
          backgroundColor: "#205C98",
          data: $scope.grafAlmancen
        },
		  {
          label: "Veterinarias",
          backgroundColor: "#8F8F8F",
          data: $scope.grafVeterinaria
        }
		  
      ]
    },
    options: {
      title: {
        display: true,
        text: 'Cantidad Pedidos Mensual'
      }
    }
});
	
}




$scope.initGraficoCobro  = function(){
	
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesCobro&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalDeudaCobro  = [];

              for (var i = 0; i < data.length; i++) {         
                       $scope.totalDeudaCobro.push(parseInt(data[i].total));      
                }  
            
             $scope.grafico();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};        
   
        
$scope.grafico = function (){    
           const CHART = document.getElementById("lineChart");
           let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                datasets: [
                    {
                        label: "Total 2018",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalMes,
                        spanGaps: false,
                    },
                    {
                        label: "Francisca",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(192,75,134,0.4)",
                        borderColor: "rgba(192,75,134,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(192,75,134,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(192,75,134,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalFranisca,
                        spanGaps: false,
                    },
                    
                    {
                        label: "Blanca",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(76,192,75,0.4)",
                        borderColor: "rgba(76,192,75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(76,192,75,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(76,192,75,1)",
                        pointHoverBorderColor: "rgba(76,192,75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalBoris,
                        spanGaps: false,
                    },
                    
                                        {
                        label: "Juan",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(	75, 76, 192,0.4)",
                        borderColor: "rgba(	75, 76, 192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(75, 76, 192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75, 76, 192,1)",
                        pointHoverBorderColor: "rgba(75, 76, 192,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalJuan,
                        spanGaps: false,
                    }/*,
                                        {
                        label: "Nico",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(192, 133, 75,0.4)",
                        borderColor: "rgba(192, 133, 75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(192, 133, 75,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(192, 133, 75,1)",
                        pointHoverBorderColor: "rgba(192, 133, 75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalNico,
                        spanGaps: false,
                    }*/
                    ,
                    {
                        label: "Pend. Cobro",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(172,53,47,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(172,53,47,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(172,53,47,1)",
                        pointHoverBorderColor: "rgba(172,53,47,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDeudaCobro,
                        spanGaps: false,
                    }
					,
					 {
                        label: "Diego",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(255, 172, 53,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(255, 172, 53,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDiego,
                        spanGaps: false,
                    }
                
                
                
                
                ]
               }

            })
    
    $scope.cerrarCargando();
    if($scope.$parent.tipoUsuarioLogin == 'Administrador'){
    document.getElementById('idAdministrador').style.display  = 'block';
   
       }
    
};        
        
        
$scope.selTipoPrecio = function () {
    
     $http.get('FunctionIntranet.php?act=marcaProductoPrecio&id='+$scope.tipo.codCategoria+'&tienda='+$scope.tiendaSeleccionada).success(function(data) {
        console.log(data);
        $scope.marcas = [];
        $scope.marcas = data;
         
    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
            
};

        
           
$scope.selMarca = function () {

    $scope.listProd         = [];
    $scope.loadingProd      = true;
    $scope.loadingProdVacio = false;
    $scope.auxMarca         = $scope.marca;         

};       
                
         
$scope.buscarProductos = function () {
    $scope.loading=true; 

     var marcaProd           = ($scope.marca == undefined ? "": $scope.marca.codMarca);
     var categoriaProd       = ($scope.tipo == undefined ? "": $scope.tipo.codCategoria);
     var idProveedor         = ($scope.prove == undefined ? "": $scope.prove);
    

     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProductosTotalPrecio&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({codProducto:idProveedor, 
                                        nombreProd:$scope.nombProd, 
                                        marcaProd:marcaProd, 
                                        categoriaProd:categoriaProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                $scope.listProd = data;
                $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
    
};     
        
       

$scope.initGraficoUsuario  = function(){
   $scope.totalUsuario= [];
   var banderaMes=false;

	
	
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesUsuario&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:$scope.idUsuarioLogin }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
           //   $scope.totalUsuario  = [0,0];

	  

		  for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
					 $scope.totalUsuario.push(parseInt(data[i].total));
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalUsuario.push(0);

			  }
	      } 
			
             $scope.graficoUsuario();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};    
        
        
        
$scope.graficoUsuario = function (){
            const CHART = document.getElementById("lineChart");

            let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                datasets: [
                    {
                        label: "Total 2018",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(	75, 75, 192,0.4)",
                        borderColor: "rgba(	75, 75, 192,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(	75, 75, 192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(	75, 75, 192,1)",
                        pointHoverBorderColor: "rgba(	75, 75, 192,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalUsuario,
                        spanGaps: false,
                    }
                ]
               }

            })
            
        $scope.cerrarCargando();
        document.getElementById('idVendedor').style.display = 'block';
	$scope.cantidadGrafica();
    
};         
        
        
$scope.initGraficoUsuarioSema  = function(){    
        var diaSemanas  = ["Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
    
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorSemanasRango&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:$scope.idUsuarioLogin }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
            $scope.totalUsuarioSemana  = [];
            for(var x=0; x < diaSemanas.length; x++){
              var diaEstado =false;

                  for(var y=0; y < data.length; y++){
                      if(diaSemanas[x].toString() == data[y].dia.toString()){
                         diaEstado=true;      
                         $scope.totalUsuarioSemana.push(parseInt(data[y].total));
                         break;
                       }                      
                  } 
              
                        if(!diaEstado){
                            $scope.totalUsuarioSemana.push(0);             
                        }
            }

             $scope.graficoUsuarioSemana();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};    
      
        
        
        
 $scope.graficoUsuarioSemana = function (){
            const CHART = document.getElementById("lineChartRango");
            let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
                datasets: [
                    {
                        label: "Ventas Semanal",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(	192, 75, 75,0.4)",
                        borderColor: "rgba(	192, 75, 75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(	192, 75, 75,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(	192, 75, 75,1)",
                        pointHoverBorderColor: "rgba(	192, 75, 75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalUsuarioSemana,
                        spanGaps: false,
                    }
                ]
               }

            })
    
};    
        
        
        
        
$scope.initGraficoUsuarioGeneral  = function(){    
     var diaSemanas  = ["Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];    
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorSemRangoGene&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
            $scope.totalUsuarioGeneral = [];
            for(var x=0; x < diaSemanas.length; x++){
              var diaEstado =false;

                  for(var y=0; y < data.length; y++){
                      if(diaSemanas[x].toString() == data[y].dia.toString()){
                         diaEstado=true;      
                         $scope.totalUsuarioGeneral.push(parseInt(data[y].total));
                         break;
                       }                      
                  } 
              
                        if(!diaEstado){
                            $scope.totalUsuarioGeneral.push(0);             
                        }
            }

             $scope.graficoUsuarioGeneral();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};    
      

 $scope.graficoUsuarioGeneral = function (){    
            const CHART = document.getElementById("lineChartGeneral");
            let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
                datasets: [
                    {
                        label: "Ventas Semanal",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(	192, 75, 75,0.4)",
                        borderColor: "rgba(	192, 75, 75,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(	192, 75, 75,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(	192, 75, 75,1)",
                        pointHoverBorderColor: "rgba(	192, 75, 75,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalUsuarioGeneral,
                        spanGaps: false,
                    }
                ]
               }

            })
    
};      
        
        
        
$scope.listarProductosOfertas = function () {     
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarProductosOfertas&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.productosOfertas = [];                
                $scope.productosOfertas = data;
          }).error(function(error){
                        console.log(error);
    
    });
};       
        
        
            
        
        
        
        
        /******************************************/
        
$scope.listarProductosSinStock = function () {

    $scope.listarProductosSinStock = [];      
    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarProductosSinStock&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                         
                $scope.listarProductosSinStock = data;
          }).error(function(error){
                        console.log(error);
    
    });
 
    
   var arrayDeta = [];

   for (var i = 0; i <    $scope.listarProductosSinStock.length; i++) {   
   var objDeta= new Object();           
            objDeta.folio          =  $scope.listarProductosSinStock[i].folio;
            objDeta.id_pedido      =  $scope.listarProductosSinStock[i].id_pedido;
            objDeta.id_pedido      =  $scope.listarProductosSinStock[i].id_pedido;
            objDeta.id_pedido      =  $scope.listarProductosSinStock[i].id_pedido;
       
    arrayDeta.push(objDeta);       
   }
   
var jsonData=angular.toJson($scope.listarProductosSinStock);
var objectToSerialize={'productos':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarProductoPDFSTOCK&tienda='+$scope.tiendaSeleccionada,
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
         /*   var filename ="productos_"+$scope.fechaActual+"_"+$scope.horaActual+".pdf";    */
                        var filename ="productos_.pdf";          

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