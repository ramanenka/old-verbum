(function (app) {
    var Router = Backbone.Router.extend({
        routes: {
            '': 'home',
            'search/:query' : 'search'
        },

        home: function () {
            // TODO: render home page
            console.log('home page', arguments);
        },

        search: function () {
            // TODO: handle search action
            // TODO:
            console.log('search page', arguments);
        }
    });

    app.on('app:start', function () {
        app.router = new Router();
        Backbone.history.start();
    });

})(App);
