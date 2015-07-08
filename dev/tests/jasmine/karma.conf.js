module.exports = function(config) {
    config.set({
        basePath: '../../..',
        frameworks: ['jasmine'],
        files: [
            'app/javascript/**/*.js',
            'dev/tests/jasmine/verbum/**/*.js'
        ],
        browsers: ['Chrome']
    });
};
