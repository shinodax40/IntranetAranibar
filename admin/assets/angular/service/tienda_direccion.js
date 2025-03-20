/* global app */

sourceAdminApp.service("tiendaDireccionService", function (service, Page, CONFIG) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: CONFIG.METODO.GET,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION),
                params: _filter
            });
        },
        obtainTo: function (_id) {
            return service.rest({
                method: CONFIG.METODO.GET,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION) + "/" + _id
            });
        },
        create: function (_data) {
            return service.rest({
                method: CONFIG.METODO.POST,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION),
                data: _data
            });
        },
        edit: function (_id, _data) {
            return service.rest({
                method: CONFIG.METODO.PUT,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION) + "/" + _id,
                data: _data
            });
        },
        replaceTo: function (_id, _data) {
            return service.rest({
                method: CONFIG.METODO.PATCH,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION) + "/" + _id,
                data: _data
            });
        },
        remove: function (_id) {
            return service.rest({
                method: CONFIG.METODO.DELETE,
                url: Page.getService(CONFIG.MAPPING.TIENDADIRECCION) + "/" + _id
            });
        }
    };
});