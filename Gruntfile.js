var fs = require("fs")
var scssFiles = {};
var listScss = function (path) {
    fs.readdirSync(path).forEach(function (file) {
        if(fs.lstatSync(path + '/' +file).isDirectory()){
            listScss(path + '/' +file);
        }else{

            if(file.charAt(0) !=  "_"){
                file = file.replace(/\.[^/.]+$/, "");
                var cssName = "css/"+file+'.css';
                var scssName = "sass/"+file+'.scss';
                scssFiles[cssName] = scssName;

            }
        }
    });
}
listScss("sass/");
//console.log(scssFiles)
module.exports = function (grunt) {
    require('load-grunt-tasks')(grunt);
    grunt.initConfig({
        // Watch task config
        watch: {
            sass: { //we only want to run this for all files that are not style, base variables, and menu since that should refresh everything
                files: "sass/!(_base-variables).scss",
                tasks: ['newer:sass:compile'],
            },
            style_sass: { //we want this to run only for style, base variables, and menu so that we can refresh everything
                files: ["sass/style.scss"],
                tasks: ['sass:compile'],
            }
        },
        sass: {
            options: {
                sourceMap: true
            },
            compile: {
                /*cwd: 'sass/',
                src: '*.scss',
                dest: 'css/',
                expand: false,
                ext: '.css'*/
                files: scssFiles
            },
            refresh: {
                cwd: 'sass/',
                src: '*.scss',
                dest: 'css/',
                expand: true,
                ext: '.css'
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'css',
                    ext: '.min.css'
                }]
            }
        }

    });
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-newer');
    grunt.registerTask('default', ['watch', 'sass']);
    grunt.registerTask('minify', ['sass:refresh', 'cssmin']);
};