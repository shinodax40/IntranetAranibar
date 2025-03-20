angularRoutingApp.controller('controllerInformeVenta', ['$scope', '$http',

function ($scope, $http) {
     $scope.sumaCantidadOfertaTotalArray    = [];
	 $scope.grafParticular                  = [];
	 $scope.grafMascotero                   = [];
	 $scope.grafAlmancen                    = [];
	 $scope.grafVeterinaria                 = [];	
	 $scope.grafPeluqueria                  = [];	
    
     $scope.totalDataFran                = [];
     $scope.totalDataBlanca              = [];
     $scope.totalDataCristian            = [];
     $scope.totalDataJorge            = [];
     $scope.totalDataJuan           = [];
     $scope.totalDataMiloska           = [];

    
    
     $scope.totalDataNicol               = [];  
     $scope.listCantPedidosSemanal       = [];   
     $scope.superVenta       = false;
     $scope.totalUsuarioAnual = new Array(50);
     
        
$scope.init = function () {
    
    $scope.listarMesyAno();
    
   if($scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }
    
    
     $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } });
     
     $scope.initGraficoUsuario();
  //   $scope.initGraficoUsuarioSema(); 
     $scope.listarVentaPortip ();
   //  $scope.listarVentaVendedoresTotal();
     $scope.listarVentaActualUsua();
     $scope.sumaCantidadOfertaTotal();    
    // $scope.listarCantPedidosSemanal();
     $scope.listarPorcentajeVenta();
     $scope.ventaAnualUsuario();
    
    $scope.listCantPedMensual();
    
    if($scope.tipoIdLogin == 7 || $scope.tipoIdLogin == 1){
        $scope.superVenta = true;
       
       }
    
    $scope.count();  
    $scope.countVentaCrecimiento();
};

    
$scope.initGraficoUsuarioSema  = function(){    
        var diaSemanas  = ["Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
    var desde2  = angular.element(document.querySelector("#desdeb")).val();
          var hasta1  = angular.element(document.querySelector("#hastab")).val();    
    
        $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorSemanasRango&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsuario:$scope.idUsuarioLogin,
                                        desde:desde2,
                                        hasta:hasta1}),
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
      
 /***************************************************///////
        
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
        
             $scope.totalUsuario  = [];

	  

		//  for(var x=1; x < 13 ; x++){
              
			 // banderaMes = false;
			 // for (var i = 0; i < data.length; i++) {  
			     //if(data[i].mes == x){
					$scope.totalUsuario.push(parseInt(data[0].mes1));
  					$scope.totalUsuario.push(parseInt(data[0].mes2));
              		$scope.totalUsuario.push(parseInt(data[0].mes3));
              		$scope.totalUsuario.push(parseInt(data[0].mes4));
              		$scope.totalUsuario.push(parseInt(data[0].mes5));
              		$scope.totalUsuario.push(parseInt(data[0].mes6));
              		$scope.totalUsuario.push(parseInt(data[0].mes7));
              		$scope.totalUsuario.push(parseInt(data[0].mes8));
              		$scope.totalUsuario.push(parseInt(data[0].mes9));
              		$scope.totalUsuario.push(parseInt(data[0].mes10));
              		$scope.totalUsuario.push(parseInt(data[0].mes11));
              		$scope.totalUsuario.push(parseInt(data[0].mes12));

					//   banderaMes  = true;
					// break;
				
				// }
			 // }
			  
			 /* if(banderaMes == false){
					  $scope.totalUsuario.push(0);

			  }*/
	     // }
            
            
			
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
                  labels: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"],
        
                datasets: [
                    {
                        label: "Ventas",
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
               },
                 options: {
      title: {
        display: true,
        text: 'Tus Ventas Anual'
      }
    }

            })
            
	//    $scope.cantidadGrafica();
    
};  
    
    
/****************************************************************************/    
  	
$scope.cerrarCargando = function(){    
                  setTimeout($.unblockUI, 1000);     
};        
        
  /*******************GRAFICA CANTIDAD VENTA*****************************************/      
/*$scope.cantidadGrafica = function(){
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
*/
/*
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
*/
/*

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
}*/

/*

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
		 
		 $scope.cantidadPeluqueria();
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}*/
/*
$scope.cantidadPeluqueria = function(){	
		var banderaMes=false;
		  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cantidadPeluqueria&tienda='+$scope.tiendaSeleccionada,
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
								 $scope.grafPeluqueria.push(parseInt(data[i].total));
								 banderaMes  = true;
								 break;

							 }
						  }			  
						  if(banderaMes == false){
								  $scope.grafPeluqueria.push(0);
						  }
					  }
			 }else{
				for(var x=1; x < 13 ; x++){				 
						  $scope.grafPeluqueria.push(0);
				}			  	 
		     }  
			  
		 $scope.graficaCantidad();	  
			  
          }).error(function(error){
                        console.log(error);
    }); 
}
*/
/*************************************************************************/
		
 $scope.graficoUsuarioSemana = function (){
            const CHART = document.getElementById("lineChartRango");
            let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO"],
                datasets: [
                    {
                        label: "Tus Ventas Semanal",
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
  		    
    
    
 
$scope.graficaCantidad = function(){
	
new Chart(document.getElementById("bar-chart-grouped"), {
    type: 'bar',
    data: {
      labels: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"],
      datasets: [
        {
          label: "Particulares",
          backgroundColor: "#ddb0ea",
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
        },
		  {
          label: "Peluqueria",
          backgroundColor: "#cc35df",
          data: $scope.grafPeluqueria
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



$scope.listarVentaPortip = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentaPorTipo&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsua:$scope.idUsuarioLogin }),			  
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	$scope.listVentaMesTotal = data;
		 
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}


$scope.listarVentaVendedoresTotal = function(){	
 $scope.listVentaVendedoresTotalAnterior = [];
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentaVendedores&tienda='+$scope.tiendaSeleccionada,
                       data:  $.param({mesSel:$scope.listMes.id, 
                                       anoSel:$scope.listAn.nombre_anual 
                                      }),		
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
    
         $scope.listVentaVendedoresTotalAnterior = data;
		 
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}
    
$scope.count = function(){
    
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaCantidadOferta&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsua:$scope.idUsuarioLogin }),			  
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
		  $('.counter').html(data[0].cantidad);
					
          }).error(function(error){
                        console.log(error);
    }); 
}

$scope.countVentaCrecimiento = function(){
    
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarPorcentajePorUsuario&tienda='+$scope.tiendaSeleccionada,
		                data:  $.param({idUsua:$scope.idUsuarioLogin }),			  
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
		  $('.counterVenta').html(data[0].total + "%");
					
          }).error(function(error){
                        console.log(error);
    }); 
}

$scope.listarVentaActualUsua = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarVentaActualUsua&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	$scope.listarVentaActualUsua = data;
		 
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}

$scope.listarPorcentajeVenta = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarAnteriorPorcentaje&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);

       	  $scope.dgPorcentaje = data;
					        $scope.cerrarCargando();

          }).error(function(error){
                        console.log(error);
    }); 
	
}

$scope.sumaCantidadOfertaTotal = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaCantidadOfertaTotal&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          $scope.sumaCantidadOfertaTotalArray    = []; 
       	$scope.sumaCantidadOfertaTotalArray = data;
		 
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}

$scope.listarCantPedidosSemanal = function(){
    	$scope.listCantPedidosSemanal = [];
          var desde2  = angular.element(document.querySelector("#desdeb2")).val();
          var hasta1  = angular.element(document.querySelector("#hastab2")).val();    
    
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listCantPedSemana&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({desde:desde2,
                              hasta:hasta1}),	
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	$scope.listCantPedidosSemanal = data;
         
        
		 
					
          }).error(function(error){
                        console.log(error);
    }); 
}


$scope.ventaAnualUsuario = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=anualVentaUsuario&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        // $scope.ordenUsuario    = [131,48,212,204,1];  
                  
                  $scope.ordenUsuario    = [215,214,212,204,213,1,226];  


         
         //$scope.ordenUsuario    = [3,2];  
         
             var totalUsuarioAnual = new Array(50);
           
         
         for (var x = 0; x < $scope.ordenUsuario.length; x++) {        
       	for (var i = 0; i < data.length; i++) {  
          
            if($scope.ordenUsuario[x] ==  data[i].id){
                
            

              

               switch ($scope.ordenUsuario[x] ) {
                  case 215://Centro
                       
                          $scope.totalDataFran.push(parseInt(data[i].mes1));
                          $scope.totalDataFran.push(parseInt(data[i].mes2));
                          $scope.totalDataFran.push(parseInt(data[i].mes3));
                          $scope.totalDataFran.push(parseInt(data[i].mes4));
                          $scope.totalDataFran.push(parseInt(data[i].mes5));
                          $scope.totalDataFran.push(parseInt(data[i].mes6));
                          $scope.totalDataFran.push(parseInt(data[i].mes7));
                          $scope.totalDataFran.push(parseInt(data[i].mes8));
                          $scope.totalDataFran.push(parseInt(data[i].mes9));
                          $scope.totalDataFran.push(parseInt(data[i].mes10));
                          $scope.totalDataFran.push(parseInt(data[i].mes11));
                          $scope.totalDataFran.push(parseInt(data[i].mes12));
                       
                    break;
                  case 214://Sur
                           
                          $scope.totalDataBlanca.push(parseInt(data[i].mes1));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes2));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes3));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes4));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes5));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes6));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes7));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes8));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes9));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes10));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes11));
                          $scope.totalDataBlanca.push(parseInt(data[i].mes12));
                         
                    break;
                      case 212: //Rural

                              $scope.totalDataCristian.push(parseInt(data[i].mes1));
                              $scope.totalDataCristian.push(parseInt(data[i].mes2));
                              $scope.totalDataCristian.push(parseInt(data[i].mes3));
                              $scope.totalDataCristian.push(parseInt(data[i].mes4));
                              $scope.totalDataCristian.push(parseInt(data[i].mes5));
                              $scope.totalDataCristian.push(parseInt(data[i].mes6));
                              $scope.totalDataCristian.push(parseInt(data[i].mes7));
                              $scope.totalDataCristian.push(parseInt(data[i].mes8));
                              $scope.totalDataCristian.push(parseInt(data[i].mes9));
                              $scope.totalDataCristian.push(parseInt(data[i].mes10));
                              $scope.totalDataCristian.push(parseInt(data[i].mes11));
                              $scope.totalDataCristian.push(parseInt(data[i].mes12));

                        break;
                     case 226: //Norte
                       
                          $scope.totalDataJorge.push(parseInt(data[i].mes1));
                          $scope.totalDataJorge.push(parseInt(data[i].mes2));
                          $scope.totalDataJorge.push(parseInt(data[i].mes3));
                          $scope.totalDataJorge.push(parseInt(data[i].mes4));
                          $scope.totalDataJorge.push(parseInt(data[i].mes5));
                          $scope.totalDataJorge.push(parseInt(data[i].mes6));
                          $scope.totalDataJorge.push(parseInt(data[i].mes7));
                          $scope.totalDataJorge.push(parseInt(data[i].mes8));
                          $scope.totalDataJorge.push(parseInt(data[i].mes9));
                          $scope.totalDataJorge.push(parseInt(data[i].mes10));
                          $scope.totalDataJorge.push(parseInt(data[i].mes11));
                          $scope.totalDataJorge.push(parseInt(data[i].mes12));
                       
                    break;   
                       
                    case 213: //Miloska                       
                          $scope.totalDataMiloska.push(parseInt(data[i].mes1));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes2));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes3));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes4));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes5));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes6));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes7));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes8));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes9));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes10));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes11));
                          $scope.totalDataMiloska.push(parseInt(data[i].mes12));                       
                    break; 
                       
                       
                    /*   case 1://Juan Aranibar
                       
                          $scope.totalDataJuan.push(parseInt(data[i].mes1));
                          $scope.totalDataJuan.push(parseInt(data[i].mes2));
                          $scope.totalDataJuan.push(parseInt(data[i].mes3));
                          $scope.totalDataJuan.push(parseInt(data[i].mes4));
                          $scope.totalDataJuan.push(parseInt(data[i].mes5));
                          $scope.totalDataJuan.push(parseInt(data[i].mes6));
                          $scope.totalDataJuan.push(parseInt(data[i].mes7));
                          $scope.totalDataJuan.push(parseInt(data[i].mes8));
                          $scope.totalDataJuan.push(parseInt(data[i].mes9));
                          $scope.totalDataJuan.push(parseInt(data[i].mes10));
                          $scope.totalDataJuan.push(parseInt(data[i].mes11));
                          $scope.totalDataJuan.push(parseInt(data[i].mes12));
                       
                    break;   */
                  default:
                    break;
                }
               
                
                
                
             //  $scope.dgAnualUsuVent.push($scope.totalUsuarioAnual);
                $scope.grafico();
               
            } 
		} 
	}
         
         console.log( $scope.totalUsuarioAnual.toString());
         
        

         
          }).error(function(error){
                        console.log(error);
    }); 
	
}





       
$scope.grafico = function (){    
           const CHART = document.getElementById("lineChartGeneralTodos");
           let lineChart = new Chart(CHART,{
                type:'line',
                data: {
                labels: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"],
                datasets: [
                  
                    {
                        label: "Centro",
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
                        data: $scope.totalDataFran,
                        spanGaps: false,
                    }
                    ,
                    {
                        label: "Sur",
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
                        data: $scope.totalDataBlanca,
                        spanGaps: false,
                    }
					,
					 {
                        label: "Rural",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(255,172,53,0.4)",
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
                        data: $scope.totalDataCristian,
                        spanGaps: false,
                    },
                    
					 {
                       label: "Norte",
                        fill: false,
                        lineTension: 0.1,
                        pointBorderColor: "rgba(31, 86, 239, 1)",
                        borderColor: "rgba(31, 86, 239, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(31, 86, 239, 1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(31, 86, 239, 1)",
                        pointHoverBorderColor: "rgba(31, 86, 239, 1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDataJorge,
                        spanGaps: false,
                    },
                 /*  	,
					 {
                        label: "Jorge",
                        fill: false,
                        lineTension: 0.1,
                        pointBorderColor: "rgba(31, 86, 239, 1)",
                        borderColor: "rgba(31, 86, 239, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(31, 86, 239, 1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(31, 86, 239, 1)",
                        pointHoverBorderColor: "rgba(31, 86, 239, 1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDataJorge,
                        spanGaps: false,
                    }
                    ,*/
					/* {
                        label: "Juan",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(229, 16, 44, 0.4)",
                        borderColor: "rgba(229, 16, 44, 1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(229, 16, 44, 1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(229, 16, 44, 1)",
                        pointHoverBorderColor: "rgba(229, 16, 44, 1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDataJuan,
                        spanGaps: false,
                    }  
                    ,*/
					 {
                        label: "Miloska",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(229, 233, 5, 210)",
                        borderColor: "rgba(110, 93, 128, 0.27)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(110, 93, 128, 0.27)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(110, 93, 128, 0.27)",
                        pointHoverBorderColor: "rgba(110, 93, 128, 0.27)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalDataMiloska,
                        spanGaps: false,
                    }
                  
                
                
                
                
                ]
               }

            })
    

   
       }



 $scope.listarProdVentaActivoVend = function(idTipoList){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProdListaVenta&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idVendedor:$scope.idUsuarioLogin,
                                        idTipList:idTipoList}),	
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listProdAct    = []; 

             $scope.listProdAct = data;
		 
					//$scope.listarUnirPorcentaje();
          }).error(function(error){
                        console.log(error);
    }); 	
}   
 
$scope.listarProdVentaActivoVendXUsuario = function(usua, idTipoList){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProdListaVenta&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idVendedor:usua.id,
                                       idTipList:idTipoList}),	
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listProdAct    = []; 

             $scope.listProdAct = data;
		 
				//	$scope.listarUnirPorcentaje();
          }).error(function(error){
                        console.log(error);
    }); 	
}    

$scope.listarProdVentaActivoVendTotal = function(idTipoList){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProdListaVentaTotal&tienda='+$scope.tiendaSeleccionada,
                          data:  $.param({idTipList:idTipoList}),	
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listProdAct    = []; 

             $scope.listProdAct = data;
		 
				//	$scope.listarUnirPorcentaje();
          }).error(function(error){
                        console.log(error);
    }); 	
}  


$scope.listCantPedMensual = function(){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listCantPedMensual&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({idUsua:$scope.idUsuarioLogin, 
							idTipo:$scope.tipoIdLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listCantPedMensual    = []; 

             $scope.listCantPedMensual = data;
		 
				//	$scope.listarUnirPorcentaje();
          }).error(function(error){
                        console.log(error);
    }); 	
}



$scope.listarMesyAno = function(){
    
         $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarMes&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.listMeses = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    
    
         $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarAno&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.listAnos = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    });     
   
}



$scope.listarVentasGeneral = function(){
    
     $scope.listVentaVendedoresTotalAnterior = [];

    
}






    
    
}]);