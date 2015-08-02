module.exports = function(grunt) {

  //Initializing the configuration object
  grunt.initConfig({
    copy: {
      fonts: {
        expand: true,
        cwd: 'bower_components/bootstrap-sass-official/assets/fonts/bootstrap',
        src: '**',
        dest: 'public/fonts/',
        flatten: true,
        filter: 'isFile',
      },
      html: {
        expand: true,
        cwd: 'website',
        src: '*.html',
        dest: 'public/',
        flatten: true,
      },
      assets: {
        expand: true,
        cwd: 'assets',
        src: '**',
        dest: 'public/assets',
        flatten: true,
        filter: 'isFile',
      }
    },
    sass: {
      maincss: {
        options: {
          style: 'expanded'
        },
        files: {
          'public/main.css': 'website/main.scss'
        }
      }
    },
    concat: {
      options: {
        separator: ';',
      },
      js_frontend: {
        src: [
          './bower_components/jquery/jquery.js',
          './bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js',
          './website/main.js'
        ],
        dest: './public/main.js',
      }
    },
    uglify: {
      options: {
        mangle: false  // Use if you want the names of your functions and variables unchanged
      },
      frontend: {
        files: {
          './public/main.js': './public/main.js'
        }
      }
    }
  });

  // Plugin loading
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Task definition
  grunt.registerTask('init', ['copy', 'sass', 'concat', 'uglify']);
  grunt.registerTask('default', ['init']);
  grunt.registerTask('dist', ['init']);

};
