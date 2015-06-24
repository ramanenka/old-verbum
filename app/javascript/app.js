/**
 * @class AppClass
 * @constructor
 */
var AppClass = function () {
    this.views = {};
};

/**
 * Is being called once all js is loaded. Checks the hash and
 * populates initial search based on it.
 */
AppClass.prototype.start = function() {
    if (window.location.hash) {
        var q = window.location.hash.substr(1);
        this.setCurrentSearch(q);
        this.search(q);
    }
};

/**
 * Set value to search input
 *
 * @param {String} q
 */
AppClass.prototype.setCurrentSearch = function(q) {
    this.views.search.setValue(q);
};

/**
 * Set the location hash
 *
 * @param {String} hash
 */
AppClass.prototype.setHash = function(hash) {
    window.location.hash = hash;
};

/**
 * Makes the request to the server once user triggers search and renders the results
 *
 * @param {String} q
 */
AppClass.prototype.search = function(q) {
    this.queryServer('_search/' + q, function(data) {
        var t = this.template('template-results');
        document.getElementById('results').innerHTML = t({articles: data.result})
    }.bind(this));
};

/**
 * Generic function to call server.
 *
 * @param {String} url the url to send the request to
 * @param {Function} onSuccessCallback will be called once the response is received.
 */
AppClass.prototype.queryServer = function(url, onSuccessCallback) {
    var req = new XMLHttpRequest();
    req.addEventListener('load', function(response){
        onSuccessCallback(JSON.parse(this.response));
    });
    req.open('GET', url, true);
    req.send();
};

var App = new AppClass();
