(function(app) {
    var events = {};

    app.events = {

        /**
         * Register event handler for a specific event
         *
         * @param {String} event
         * @param {Function} handler
         */
        on: function(event, handler) {
            if (!events[event]) {
                events[event] = [];
            }

            events[event].push(handler);
        },

        /**
         * Notifies all event handlers about some event
         *
         * @param {String} event
         */
        emit: function(event) {
            if (events[event]) {
                var args = Array.prototype.slice.call(arguments, 1);
                for (var i = 0; i < events[event].length; i++) {
                    events[event][i].apply(null, args);
                }
            }
        },

        /**
         * Resets all registered event handlers
         */
        reset: function() {
            events = {};
        }
    };
})(App);
