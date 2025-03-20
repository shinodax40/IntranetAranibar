angularRoutingApp.controller('controllerUsuarioInforme', ['$scope', '$http',

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
        
        
        
$scope.init = function () {
    $scope.listarVendedores ();
    
      if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }
};
        
        
$scope.listarVendedores = function(){        
       $http.get('FunctionIntranet.php?act=listarVendedores&tienda='+$scope.tiendaSeleccionada).success(function(data) {
            console.log(data);         
           
           $scope.listVendedores = data;           
           $scope.vendedores = {
              availableOptions: $scope.listVendedores,                      
              selectedOption: {id: 0} 
            }
           
            }).
            error(function(data, status, headers, config) {
                console.log("error al listar vendedores: "+data);
            });
    
};
      
        
$scope.buscarInforme = function (){
$scope.initGraficoUsuarioSema();    
$scope.initGraficoUsuario();    
var  resp = ($scope.vendedores.selectedOption.id.toString() == "0" ? "":$scope.vendedores.selectedOption.id.toString());    

        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listUsuarioSeguimiento&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({id_usua:resp,
                                          desde:angular.element(document.querySelector("#desdeb")).val(),
                                          hasta:angular.element(document.querySelector("#hastab")).val() }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
               $scope.arrayInforme  = data;

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
        
        
$scope.initGraficoUsuarioSema  = function(){
      var  resp = ($scope.vendedores.selectedOption.id.toString() == "0" ? "":$scope.vendedores.selectedOption.id.toString());    

     var diaSemanas  = ["Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
    
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorSemanasRango&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:resp}),
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
        
        
/*
$scope.initGraficoUsuario  = function(){
          var  resp = ($scope.vendedores.selectedOption.id.toString() == "0" ? "":$scope.vendedores.selectedOption.id.toString());    

        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesUsuario&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:resp }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalUsuario  = [0,0];

              for (var i = 0; i < data.length; i++) {         
                       $scope.totalUsuario.push(parseInt(data[i].total));      
                }  
            
             $scope.graficoUsuario();
            
          }).error(function(error){
                        console.log(error);
    
    }); 

};          
*/	
	
$scope.initGraficoUsuario  = function(){
var  resp = ($scope.vendedores.selectedOption.id.toString() == "0" ? "":$scope.vendedores.selectedOption.id.toString());    
	
   $scope.totalUsuario= [];
   var banderaMes=false;

	
	
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesUsuario&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:resp}),
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
              

    
$scope.cargarDatos = function(indexFecha){
     var arrayfecha = indexFecha.fecha.split('-');      
         
    $scope.idSeg    = indexFecha.id_seg;
    $scope.fechaSel = arrayfecha[2]+"-"+arrayfecha[1]+"-"+arrayfecha[0];     
    $scope.cantClie = parseInt(indexFecha.cant_visitas_clie);
    $scope.cantNuevo = parseInt(indexFecha.cant_visita_nueva);
    
};
    
    
$scope.updateDatos = function (){
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=updateInforme&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({cliente:$scope.cantClie,
                                       nuevo:$scope.cantNuevo,
                                       idConf: $scope.idSeg}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
                  var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                
                    alertify.success("Informe agregado o modificado con exito!");
                }else{
                    alertify.error("Error al agregado o modificado, favor contactar con el Administrador del Sistema.");
                }
            
          }).error(function(error){
                        console.log(error);
    
    }); 
};
    
 
    
$scope.saveDatosInforme = function (){
    
  var arrayProd    = [];
          for(var i = 0; i < $scope.arrayInforme.length; i++){
                    var objDeta = new Object();
                     objDeta.id_seg           = $scope.arrayInforme[i].id_seg;
                     objDeta.cant_ped_nuevo_v  = $scope.arrayInforme[i].cant_ped_nuevo_v;
                     objDeta.cant_ped_nuevo_m  = $scope.arrayInforme[i].cant_ped_nuevo_m;
                     objDeta.cant_ped_nuevo_a  = $scope.arrayInforme[i].cant_ped_nuevo_a;

                     arrayProd.push(objDeta);
           }
             
    
    
 var objCabe    = JSON.stringify(arrayProd);
    
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=arrayUpdateInforme&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({arrayInforme:objCabe}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
                  var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                
                    alertify.success("Informe agregado o modificado con exito!");
                }else{
                    alertify.error("Error al agregado o modificado, favor contactar con el Administrador del Sistema.");
                }
            
          }).error(function(error){
                        console.log(error);
    
    }); 
};    
    
	
	
	
/*************************************************************/
	
$scope.mostrarGraficosNivelGeneral = function(){
	
	  $scope.initGrafico();
      $scope.initGraficoUsuarioGeneral(); 
	
}	
	

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
        
$scope.initGrafico  = function(){
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMes&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalMes  = [];

              for (var i = 0; i < data.length; i++) {         
                       $scope.totalMes.push(parseInt(data[i].total));      
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
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesJuan&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
              $scope.totalJuan  = [];
               for (var i = 0; i < data.length; i++) {         
                       $scope.totalJuan.push(parseInt(data[i].total));      
                }              
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
			
			 $scope.initGraficoAndres();
			
          }).error(function(error){
                        console.log(error);
    
    }); 

};	
    

$scope.initGraficoAndres  = function(){   
	$scope.totalAndres = [];
	var banderaMes = false;	
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesAndres&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
         	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalAndres.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalAndres.push(0);

			  }
	      } 
		
        
         $scope.initGraficoJorge();

          }).error(function(error){
                        console.log(error);
    
    }); 
}


$scope.initGraficoJorge  = function(){   
	$scope.totalJorge = [];
	var banderaMes = false;	
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesJorge&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
         	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalJorge.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalJorge.push(0);

			  }
	      } 
		
        
         $scope.initGraficoCobro();

          }).error(function(error){
                        console.log(error);
    
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
           const CHART = document.getElementById("lineChartGeneralTodos");
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
                    ,
                    		 {
                        label: "Nicol",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(160, 169, 153, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(160, 169, 153, 1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(160, 169, 153, 1)",
                        pointHoverBorderColor: "rgba(160, 169, 153, 1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalAndres,
                        spanGaps: false,
                    }
                    ,
                     {
                        label: "Jorge",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(73, 242, 245, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(73, 242, 245, 1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(73, 242, 245, 1)",
                        pointHoverBorderColor: "rgba(73, 242, 245, 1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalJorge,
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
       
	
	
	
	
/**************************************************************/
        
}]);