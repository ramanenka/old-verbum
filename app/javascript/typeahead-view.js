(function(app) {

    /**
     * @class App.TypeaheadView
     * @constructor
     */
    App.TypeaheadView = function() {
        this.suggestions = [];
        this.activeSuggestionIndex = null;
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
            this.activeSuggestionIndex = null;
            this.suggestions = this.el.getElementsByClassName('suggestion');
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
        this.activeSuggestionIndex = null;
        this.suggestions = [];
    };

    /**
     * @returns {Number}
     */
    App.TypeaheadView.prototype.hasSuggestions = function() {
        return this.suggestions.length;
    };

    /**
     * @returns {boolean}
     */
    App.TypeaheadView.prototype.hasActiveSuggestion = function() {
        return this.activeSuggestionIndex !== null;
    };

    /**
     * Move selection to the next suggestion (Downwards Arrow)
     */
    App.TypeaheadView.prototype.nextSuggestion = function() {
        this.moveToSuggestion(
            this.activeSuggestionIndex === null ? 0 : (this.activeSuggestionIndex + 1)
        );
    };

    /**
     * Move selection to the previous suggestion (Upwards Arrow)
     */
    App.TypeaheadView.prototype.prevSuggestion = function() {
        this.moveToSuggestion(this.activeSuggestionIndex - 1);
    };

    /**
     * Move selection to a suggestion
     * @param index
     */
    App.TypeaheadView.prototype.moveToSuggestion = function(index) {
        if (this.activeSuggestionIndex !== null) {
            this.suggestions[this.activeSuggestionIndex].classList.remove('active');
        }
        var maxIndex = this.suggestions.length - 1;
        if (index > maxIndex) {
            index = 0;
        } else if (index < 0) {
            index = maxIndex;
        }
        this.suggestions[index].classList.add('active');
        this.activeSuggestionIndex = index;
    };

    /**
     * Search by selected suggestion
     */
    App.TypeaheadView.prototype.useActiveSuggestion = function() {
        if (this.activeSuggestionIndex === null) {
            return;
        }
        var q = this.suggestions[this.activeSuggestionIndex].getAttribute('data-value');
        app.setCurrentSearch(q);
        app.search(q);
        this.hide();
    };
})(App);
