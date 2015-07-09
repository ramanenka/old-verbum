var defaultConfig = require('./karma.conf.js');

module.exports = function(config) {
    defaultConfig(config);

    config.set({
        browsers: ['PhantomJS'],
        singleRun: true,
        reporters: ['dots', 'coverage'],
        preprocessors: {
            'app/javascript/**/*.js': ['coverage']
        },
        coverageReporter: {
            type: 'text-summary'
        }
    });
};
