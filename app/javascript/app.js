var App = (function() {
    return {
        views: {},

        /**
         * Is being called once all js is loaded. Checks the hash and
         * populates initial search based on it.
         */
        start: function () {
            this.views.search = new App.SearchFormView();
            this.views.typeahead = new App.TypeaheadView();
            window.addEventListener('popstate', function (e) {
                this.setPreloadedSearch(e.state || {q: '', results: {result: []}});
            }.bind(this));
        },

        /**
         * Set value to search input
         *
         * @param {String} q
         */
        setCurrentSearch: function (q) {
            this.views.search.setValue(q);
        },

        /**
         * Set the location path
         *
         * @param {String} path
         */
        setLocation: function (path) {
            window.history.pushState({}, '', path);
        },

        /**
         * Makes the request to the server once user triggers search and renders the results
         *
         * @param {String} q
         */
        search: function (q) {
            this.setLocation(q);
            this.queryServer(q, function(data) {
                window.history.replaceState({q: q, results: data}, '', q);
                this.renderResults(data);
            }.bind(this));
        },

        /**
         * Set preloaded search without making the request to the server
         *
         * @param data
         */
        setPreloadedSearch: function (data) {
            this.setCurrentSearch(data.q);
            this.renderResults(data.results);
            window.history.replaceState(data, '', data.q);
        },

        /**
         * Update results view with the data
         *
         * @param {Array} data
         */
        renderResults: function (data) {
            var t = this.template('template-results');
            document.getElementById('results').innerHTML = t({articles: data.result});
        },

        /**
         * Generic function to call server.
         *
         * @param {String} url the url to send the request to
         * @param {Function} onSuccessCallback will be called once the response is received.
         */
        queryServer: function (url, onSuccessCallback) {
            var req = new XMLHttpRequest();
            req.addEventListener('load', function (response) {
                onSuccessCallback(JSON.parse(this.response));
            });
            req.open('GET', url, true);
            req.setRequestHeader('Accept', 'application/json');
            req.send();
        }
    };
})();
