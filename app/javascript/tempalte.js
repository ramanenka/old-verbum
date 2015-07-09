(function(app) {
    var cache = {};

    function compile(text) {
        var parts = text.split(/({{(?:=|with|each|done)?|}})/m);
        var source = 'var __result = "", __stack = [];\n';
        var stack = [];

        var i = 0;
        while (i < parts.length) {
            var part = parts[i];
            if (part == '{{=') {
                source += '__result += __current.' + parts[i++ + 1] + ';\n';
                i++;
            } else if (part == '{{with') {
                source += '__stack.push(__current); __current = __current.' + parts[i++ + 1] + ';\n';
                stack.push('with');
                i++;
            } else if (part == '{{each') {
                source += '__stack.push(__current); var __currentEach = __current.' + parts[i++ + 1] + ';\n';
                source += 'for(var __i = 0; __i < __currentEach.length; __i++) { \n';
                source += '__current = __currentEach[__i]; \n';
                stack.push('each');
                i++;
            } else if (part == '{{done') {
                if (stack.pop() == 'each') {
                    source += '} \n';
                }
                source += '__current = __stack.pop();\n';
                i += 2;
            } else if (part == '{{') {
                source += part[i++ + 1] + '\n';
                i++;
            } else {
                source += '__result += "' + part.replace(/"/g, '\\"').replace(/\n/g, '\\n') + '";\n';
            }
            i++;
        }
        source += 'return __result;\n';

        var result = new Function('__current', source);
        result.source = source;

        return result;
    }

    /**
     * Compiles the template into function
     *
     * @param id dom id of the template script node
     * @returns {Function}
     */
    app.template = function(id) {
        if (!cache[id]) {
            cache[id] = compile(document.getElementById(id).textContent);
        }

        return cache[id];
    };
})(App);
