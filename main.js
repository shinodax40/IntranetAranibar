// Creación del módulo
var angularRoutingApp = angular.module('angularRoutingApp', ['ngRoute']);

// Configuración de las rutas
angularRoutingApp.config(function($routeProvider) {

    $routeProvider
    
        .when('/', {
            templateUrl : 'app/partials/ListadoPedidos.html',
            controller  : 'controllerListadoPedido'
        })    
        .otherwise({
            redirectTo: '/'
        });
});

angularRoutingApp.controller('controllerListadoPedido', function($scope) {
   // $scope.message = 'Hola, Mundo!';
});