/* global angular */

const itemDefault = {id: null, nombre: "Seleccionar"};

String.prototype.replaceFormat = function () {
    var formatted = this;
    for (var prop in arguments[0]) {
        var regexp = new RegExp('\\${' + prop + '\\}', 'gi');
        formatted = formatted.replace(regexp, arguments[0][prop]);
    }
    return formatted;
};

Array.prototype.findById = function (id) {
    for (var i = 0; i < this.length; i++) {
        if (this[i].id === id) {
            return this[i];
        }
    }
    return itemDefault;
};


var app = angular.module('appTiendaAranibar', ['ui.router', 'ngAnimate', 'ui.bootstrap']).value('loading', {
    start: function () {
        $('#preloader').css({"display": "block"});
    },
    finish: function () {
        $('#preloader').css({"display": "none"});
    }
}).value('swal', {
    info: function (txt) {
        this.show("info", txt);
    },
    success: function (txt) {
        this.show("success", txt);
    },
    error: function (txt) {
        this.show("error", txt);
    },
    warning: function (txt) {
        this.show("warning", txt);
    },
    show: function (alert, txt) {
        Swal.fire({
            icon: alert,
            text: txt,
            confirmButtonText: 'Continuar'
        });
    },
    manual: function (obj) {
        Swal.fire(obj);
    }
}).config(function ($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/login');

    $stateProvider
            .state('login', {
                url: '/login',
                data: {pageTitle: 'Login'},
                templateUrl: 'page/login.html',
                controller: 'loginController',
                authenticate: false
            })
            .state('app', {
                url: '/app',
                template: '<div ui-view></div>',
                abstract: true,
                authenticate: true
            })
            .state('app.cuenta', {
                url: '/cuenta',
                data: {pageTitle: 'Libro'},
                templateUrl: 'page/cuenta.html',
                controller: 'cuentaController',
                authenticate: true
            });
}).run(function ($rootScope, $state, LocalStorage) {
    $rootScope.$state = $state;
    $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
        $rootScope.loggedUser = LocalStorage.get('session');
        if (toState.authenticate && !$rootScope.loggedUser) {
            event.preventDefault();
            $state.transitionTo("inicio", null, {notify: false});
            $state.go('inicio');
        }
    });
}).filter('sprintf', function () {
    function parse(str, args) {
        if (angular.isUndefined(str)) {
            return "";
        } else {
            var i = 0;
            return str.replace(/%s/g, function () {
                var result = '';
                if (!angular.isUndefined(args[i]) && args[i] !== null) {
                    result = args[i];
                }
                i++;
                return result;
            });
        }
    }

    return function () {
        return parse(Array.prototype.slice.call(arguments, 0, 1)[0], Array.prototype.slice.call(arguments, 1));
    };
}).filter('strReplace', function () {
    return function (input, from, to) {
        input = input || '';
        from = from || '';
        to = to || '';
        return input.replace(new RegExp(from, 'g'), to);
    };
}).filter('searchObject', function () {
    return function (_id, _list, _attr) {
        if (angular.isDefined(_id) && angular.isArray(_list)) {
            for (var i = 0; i < _list.length; i++) {
                if (_list[i].id === _id) {
                    return _list[i][_attr];
                }
            }
        }
        return '';
    };
}).constant('ROL', {
    CLIENTE: 'CLIENTE'
}).constant('CODE', {
    OK: 0
}).constant('MAPPING', {
    LOGIN: "autenticar",
    CUENTA: "cuenta"
}).factory('Util', function ($filter) {
    var utilidades = {};
    utilidades.formatEndpoint = function (url, _mapping) {
        return $filter('sprintf')('%s/%s%s', url, _mapping);
    };
    utilidades.convertToDate = function (_value) {
        if (_value === null || _value === undefined) {
            return null;
        }
        return new Date(_value);
    };
    utilidades.formatDate = function (_value, format) {
        if (_value === null || _value === undefined) {
            return "";
        } else {
            return $filter('date')(_value, format);
        }
    };
    utilidades.addDays = function (_date, _days) {
        _date.setDate(_date.getDate() + _days);
        return _date;
    };
    utilidades.isValidField = function (_field) {
        return angular.isDefined(_field) && _field !== null;
    };
    utilidades.isNumeric = function (_n) {
        return angular.isNumber(_n) && !isNaN(_n) && !isFinite(_n);
    };
    return utilidades;
}).factory('Url', function (Util) {
    const REST_PATH = "admin/rest";
    var obj = {};
    obj.getService = function (_mapping) {
        return Util.formatEndpoint(REST_PATH, _mapping);
    };
    return obj;
}).factory('LocalStorage', function () {
    let storage = window.localStorage;
    return {
        get: function (_name) {
            return angular.fromJson(storage.getItem(_name));
        },
        set: function (_name, value) {
            storage.setItem(_name, angular.toJson(value));
        },
        remove: function (_name) {
            storage.removeItem(_name);
        }
    };
}).controller('inicioController', function () {

});