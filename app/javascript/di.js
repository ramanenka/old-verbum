(function(app) {
    app.DI = {};

    /**
     * @class App.DI.Container
     * @constructor
     */
    app.DI.Container = function() {
        this.reset();
    };

    /**
     * Putts object to container
     *
     * @param key
     * @param object
     */
    app.DI.Container.prototype.put = function(key, object) {
        this.objects[key] = object;
    };

    /**
     * Get object from container
     *
     * @param key
     */
    app.DI.Container.prototype.get = function(key) {
        return typeof key == 'string' ? this.objects[key] : this.instantiate(key);
    };

    /**
     * Clears the container
     */
    app.DI.Container.prototype.reset = function() {
        this.objects = {};
        this.cache = [];
        this.chain = [];
    };

    /**
     * Instantiate instance of the class and inject all it's dependencies
     *
     * @param {Function} klass
     * @returns {*}
     */
    app.DI.Container.prototype.instantiate = function(klass) {
        for (var i = 0; i < this.chain.length; i++) {
            if (this.chain[i] == klass) {
                throw {message: 'Cross-dependency is found', chain: this.chain};
            }
        }
        this.chain.push(klass);

        var instance = new klass();
        this.inject(instance);
        this.chain.pop();
        return instance;
    };

    /**
     * Inject all instance's dependencies
     *
     * @param {*} instance
     */
    app.DI.Container.prototype.inject = function(instance) {
        for (var i in instance) {
            if (typeof instance[i] != 'function') {
                continue;
            }

            var match = this.getFunctionDependency(instance[i]);
            if (match === null) {
                continue;
            }

            var dep;

            if (match.indexOf('App.') === 0) {
                var dep = app;
                var parts = match.split('.');
                parts.shift();
                while (dep[parts[0]]) {
                    dep = dep[parts[0]];
                    parts.shift();
                }

                if (parts.length > 0) {
                    throw 'Unable to inject dependency: ' + match + ' is not defined';
                }
            } else {
                dep = match;
            }
            instance[i](this.get(dep));
        }
    };

    /**
     * Look up for function dependency if any
     *
     * @private
     * @param {Function} f
     * @returns {String}
     */
    app.DI.Container.prototype.getFunctionDependency = function(f) {
        for (var i = 0; i < this.cache.length; i++) {
            var cacheEntry = this.cache[i];
            if (cacheEntry.function == f) {
                return cacheEntry.name;
            }
        }

        var source = f.toString();
        var match = source.match(/['"]inject\s+([\w\.]+)['"];/);
        match = match === null ? null : match.pop();
        this.cache.push({name: match, function: f});

        return match;
    };
})(App);
