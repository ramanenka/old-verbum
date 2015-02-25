module.exports = function(grunt) {
    "use strict";

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                sourceMap: true,
                sourceMapIncludeSources: true
            },
            deps: {
                options: {
                    sourceMapName: 'public/cache/javascript/deps.min.js.map'
                },
                files: {
                    'public/cache/javascript/deps.min.js': [
                        'bower_components/underscore/underscore.js',
                        'bower_components/jquery/dist/jquery.js',
                        'bower_components/backbone/backbone.js',
                        'bower_components/bootstrap/dist/js/bootstrap.js'
                    ]
                }
            },
            src: {
                options: {
                    sourceMapName: 'public/cache/javascript/scripts.min.js.map'
                },
                files: {
                    'public/cache/javascript/scripts.min.js': [
                        'app/javascript/app.js',
                        'app/javascript/router.js'
                    ]
                }
            }
        },
        watch: {
            js: {
                files: 'app/javascript/**/*.js',
                tasks: ['uglify:src']
            },
            less: {
                files: 'app/less/**/*.less',
                tasks: ['less']
            }
        },
        symlink: {
            options: {
                overwrite: false
            },
            bootstrapFonts: {
                src: 'bower_components/bootstrap/dist/fonts',
                dest: 'public/cache/fonts'
            },
            bootstrapCSS: {
                src: 'bower_components/bootstrap/dist/css/bootstrap.min.css',
                dest: 'public/cache/css/bootstrap.min.css'
            }
        },
        less: {
            compileFrontend: {
                options: {
                    strictMath: true,
                    strictImports: true,
                    strictUnits: true,
                    compress: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapFilename: 'public/cache/css/frontend.min.css.map',
                    sourceMapURL: 'frontend.min.css.map'
                },
                src: 'app/less/frontend.less',
                dest: 'public/cache/css/frontend.min.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-symlink');
    grunt.loadNpmTasks('grunt-contrib-less');

};
