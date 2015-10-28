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
     * jquery.SlimScroll
     */
    mix
            .copy("bower_components/slimScroll/jquery.slimscroll.js", 
    "resources/assets/js/admin");
    
    
    /**
     * jquery.FastClick
     */
    mix
            .copy("bower_components/fastclick/lib/fastclick.js", 
    "resources/assets/js/admin");
    
    /**
     * Admin LTE
     */
    mix
            .copy("bower_components/AdminLTE/dist/css/AdminLTE.css", "resources/assets/css/admin")
            .copy("bower_components/AdminLTE/dist/css/skins/_all-skins.css", "resources/assets/css/admin/skins")
            .copy("bower_components/AdminLTE/dist/img", "public/build/img")
            .copy("bower_components/AdminLTE/dist/js/app.js", "resources/assets/js/admin");
    
    /**
     * HTML5 Shiv
     */
    mix
            .copy("bower_components/html5shiv/dist/html5shiv.js", "resources/assets/js/admin");
    
    /**
     * Respond
     */
    mix
            .copy("bower_components/respond/src/respond.js", "resources/assets/js/admin");
    
    /**
     * HTML5 workaround scripts
     */
    mix
            .scripts([
        'admin/html5shiv.js',
        'admin/respond.js'], 'public/js/html5workaround.js')
    ;
    
    /**
     * Admin main styles and scripts
     */
    mix
            .styles([
        'admin/bootstrap.css',
        'admin/font-awesome.css',
        'admin/ionicons.css',
        'admin/AdminLTE.css',
        'admin/skins/_all-skins.css'
        ], 'public/css/admin.css')
            .scripts([
        'admin/jquery.js',
        'admin/bootstrap.js',
        'admin/jquery.slimscroll.js',
        'admin/fastclick.js',
        'admin/app.js',
        ], 'public/js/admin.js')
            .version(['css/admin.css', 'js/admin.js', 'js/html5workaround.js'])
    ;
    
});
