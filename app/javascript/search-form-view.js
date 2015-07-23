(function(app) {

    /**
     * @class App.SearchFormView
     * @constructor
     */
    App.SearchFormView = function() {
        this.el = document.getElementById('search-form');
        this.el.addEventListener('submit', this.onSubmit.bind(this));

        this.input = this.el.querySelector('input#q');
        this.input.addEventListener('keydown', this.onKeyDown.bind(this));
        this.input.addEventListener('keyup', this.onKeyUp.bind(this));
    };

    /**
     * Is being called upon form submit
     *
     * @param {Event} ev
     */
    App.SearchFormView.prototype.onSubmit = function(ev) {
        ev.preventDefault();

        app.search(this.input.value);

        app.views.typeahead.hide();
        this.input.select();
    };

    /**
     * Set value to the input and select the whole text
     *
     * @param {String} value
     */
    App.SearchFormView.prototype.setValue = function(value) {
        this.input.value = value;
        this.input.select();
    };

    /**
     * Stores the old value of input before modification
     *
     * @param {KeyboardEvent} ev
     */
    App.SearchFormView.prototype.onKeyDown = function(ev) {
        this.oldInputValue = this.input.value;
    };

    /**
     * Checks if pressed key has modified the value in
     * the input and triggers the typeahead lookup
     *
     * @param {KeyboardEvent} ev
     */
    App.SearchFormView.prototype.onKeyUp = function(ev) {
        var oldValue = this.oldInputValue;
        delete this.oldInputValue;
        if (oldValue == this.input.value) {
            return;
        }

        if (this.typeaheadTimer) {
            window.clearTimeout(this.typeaheadTimer);
            this.typeaheadTimer = null;
        }
        this.typeaheadTimer = window.setTimeout(function() {
            if (this.input.value) {
                app.views.typeahead.lookup(this.input.value);
            } else {
                app.views.typeahead.hide();
            }
        }.bind(this), 200);
    };
})(App);
