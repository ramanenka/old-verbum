module.exports = function(grunt) {
    "use strict";

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                sourceMap: true,
                sourceMapIncludeSources: true,
                compress: {
                    drop_debugger: false
                },
                mangle: false
            },
            src: {
                options: {
                    sourceMapName: 'public/cache/javascript/scripts.min.js.map'
                },
                files: {
                    'public/cache/javascript/scripts.min.js': [
                        'app/javascript/*.js'
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
        less: {
            frontend: {
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
    grunt.loadNpmTasks('grunt-contrib-less');

};
