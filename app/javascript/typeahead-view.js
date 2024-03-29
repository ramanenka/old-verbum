(function(app) {

    /**
     * @class App.TypeaheadView
     * @constructor
     */
    App.TypeaheadView = function() {
        this.el = document.getElementById('typeahead');
        this.el.addEventListener('click', this.onSuggestionClick.bind(this));
        window.addEventListener('click', this.hide.bind(this));
    };

    /**
     * Set the current search to the option that was chosen
     *
     * @param {MouseEvent} ev
     */
    App.TypeaheadView.prototype.onSuggestionClick = function(ev) {
        ev.stopPropagation();
        var q = ev.target.getAttribute('data-value');
        app.setCurrentSearch(q);
        app.search(q);
        this.hide();
    };

    /**
     * Starts the typeahead lookup
     *
     * @param {String} q
     */
    App.TypeaheadView.prototype.lookup = function(q) {
        app.queryServer('_typeahead/' + q, this.render.bind(this));
    };

    /**
     * Renders the typeahead drop-down view
     *
     * @param {Object} data
     */
    App.TypeaheadView.prototype.render = function(data) {
        if (data.result.length > 0) {
            var t = app.template('template-typeahead');
            this.el.innerHTML = t({suggestions: data.result});
            this.show();
        } else {
            this.hide();
        }
    };

    /**
     * Show the typeahead drop-down
     */
    App.TypeaheadView.prototype.show = function() {
        this.el.style.display = '';
    };

    /**
     * Hide the typeahead drop-down
     */
    App.TypeaheadView.prototype.hide = function() {
        this.el.style.display = 'none';
    };
})(App);
