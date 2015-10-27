var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    
    //mix.sass('app.scss');
    
    /**
     * jQuery
     */
    mix
            .copy("bower_components/jquery/dist/jquery.js", 
    "resources/assets/js/admin");
    
    /**
     * Bootstrap
     */
    mix
            .copy("bower_components/bootstrap/dist/css/bootstrap.css", 
    "resources/assets/css/admin")
            .copy("bower_components/bootstrap/dist/fonts", "public/build/fonts")
            .copy("bower_components/bootstrap/dist/js/bootstrap.js", 
    "resources/assets/js/admin");
    
    /**
     * Font Awesome
     */
    mix
            .copy("bower_components/font-awesome/css/font-awesome.css", 
    "resources/assets/css/admin")
            .copy("bower_components/font-awesome/fonts", "public/build/fonts");
    
    /**
     * Ionicons
     */
    mix
            .copy("bower_components/Ionicons/css/ionicons.css", "resources/assets/css/admin")
            .copy("bower_components/Ionicons/fonts", "public/build/fonts")     
            .copy("bower_components/Ionicons/png", "public/build/png");
    
    /**
     * Admin LTE
     */
    mix
            .copy("bower_components/AdminLTE/dist/css", "resources/assets/css/admin")
            .copy("bower_components/AdminLTE/dist/img", "public/build/img")
            .copy("bower_components/AdminLTE/dist/js/app.js", "resources/assets/js/admin");
    
    
    
    /**
     * Admin styles and scripts
     */
    mix
            .styles([
        'admin/bootstrap.css',
        'admin/font-awesome.css',
        'admin/ionicons.css',
        'admin/AdminLTE.css',
        'admin/skins/skin-blue.css'
        ], 'public/css/admin.css')
            .scripts([
        'admin/jquery.js',
        'admin/bootstrap.js',
        'admin/app.js',
        ], 'public/js/admin.js')
        .version(['css/admin.css', 'js/admin.js'])
    ;
    
});
