var AppClass = function () {

};

AppClass.prototype = _.extend(AppClass.prototype, Backbone.Events, {
    start: function () {
        this.trigger('app:start');
    }
});

var App = new AppClass();
