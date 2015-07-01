(function(app) {

    /**
     * @class SearchFormView
     * @constructor
     */
    var SearchFormView = function() {
        this.el = document.getElementById('search-form');
        this.el.addEventListener('submit', this.onSubmit.bind(this));

        this.input = this.el.querySelector('input#q');
        this.input.addEventListener('keydown', this.onKeyDown.bind(this));
        this.input.addEventListener('keypress', this.onKeyPress.bind(this));
        this.input.addEventListener('keyup', this.onKeyUp.bind(this));
        this.newChar = null;
    };

    /**
     * Char mapping for EN and RU layouts
     */
    SearchFormView.prototype.charMap = {
        39 : 'э',
        44 : 'б',
        46 : 'ю',
        59 : 'ж',
        91 : 'х',
        93 : '\'',
        96 : 'ё',
        97 : 'ф',
        98 : 'і',
        99 : 'с',
        100 : 'в',
        101 : 'у',
        102 : 'а',
        103 : 'п',
        104 : 'р',
        105 : 'ш',
        106 : 'о',
        107 : 'л',
        108 : 'д',
        109 : 'ь',
        110 : 'т',
        111 : 'ў',
        112 : 'з',
        113 : 'й',
        114 : 'к',
        115 : 'ы',
        116 : 'е',
        117 : 'г',
        118 : 'м',
        119 : 'ц',
        120 : 'ч',
        121 : 'н',
        122 : 'я',
        1080 : 'і',
        1097 : 'ў',
        1098 : '\''
    };

    /**
     * Is being called upon form submit
     *
     * @param {Event} ev
     */
    SearchFormView.prototype.onSubmit = function(ev) {
        ev.preventDefault();

        app.setHash(this.input.value);
        app.search(this.input.value);

        app.views.typeahead.hide();
        this.input.select();
    };

    /**
     * Set value to the input and select the whole text
     *
     * @param {String} value
     */
    SearchFormView.prototype.setValue = function(value) {
        this.input.value = value.toUpperCase();
        this.input.select();
    };

    /**
     * Stores the old value of input before modification
     *
     * @param {KeyboardEvent} ev
     */
    SearchFormView.prototype.onKeyDown = function(ev) {
        this.oldInputValue = this.input.value;
    };

    SearchFormView.prototype.onKeyPress = function(ev) {
        this.newChar = this.charMap[ev.charCode] || null;
    };

    /**
     * Checks if pressed key has modified the value in
     * the input and triggers the typeahead lookup
     *
     * @param {KeyboardEvent} ev
     */
    SearchFormView.prototype.onKeyUp = function(ev) {
        var oldValue = this.oldInputValue;
        delete this.oldInputValue;
        if (this.newChar !== null) {
            this.input.value = this.input.value.substr(0, this.input.value.length - 1) + this.newChar;
            this.newChar = null;
        }
        this.input.value = this.input.value.toUpperCase();
        if (oldValue == this.input.value) {
            return;
        }

        if (this.typeaheadTimer) {
            window.clearTimeout(this.typeaheadTimer);
            this.typeaheadTimer = null;
        }
        this.typeaheadTimer = window.setTimeout(function(){
            if (this.input.value) {
                app.views.typeahead.lookup(this.input.value);
            } else {
                app.views.typeahead.hide();
            }
        }.bind(this), 200);
    };

    app.views.search = new SearchFormView();
})(App);
