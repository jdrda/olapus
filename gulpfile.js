/**
 * Elixir asset management
 * 
 * Rules for mixing styles and scripts
 * 
 * @category Mixing
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

var elixir = require('laravel-elixir');

elixir(function (mix) {

    /**
     * ADMIN
     */

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
            .copy("bower_components/jQuery-slimScroll/jquery.slimscroll.js",
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
     * Speakingurl
     */
    mix
            .copy("bower_components/speakingurl/lib/speakingurl.js", "resources/assets/js/admin");

    /**
     * Pace
     */
    mix
            .copy("bower_components/PACE/themes/blue/pace-theme-corner-indicator.css", "resources/assets/css/admin")
            .copy("bower_components/PACE/pace.js", "resources/assets/js/admin")
            .styles([
                'admin/pace-theme-corner-indicator.css',
                ], 'public/css/pace.css')
            .scripts([
                'admin/pace.js'
                ], 'public/js/pace.js');
                
    /**
     * jquery_lazyload
     */
    mix
            .copy("bower_components/jquery_lazyload/jquery.lazyload.js", "resources/assets/js/admin");

    /**
     * Select2
     */
    mix
            .copy("bower_components/select2/dist/css/select2.css", "resources/assets/css/admin")
            .copy("bower_components/select2/dist/js/select2.full.js", "resources/assets/js/admin")
            .copy("bower_components/select2/dist/js/i18n", "public/js/i18n")

    /**
     * Tiny MCE
     */
    mix
            .copy("bower_components/tinymce/langs", "public/js/admin/tinymce/langs")
            .copy("bower_components/tinymce/plugins/**/*.min.js", "public/js/admin/tinymce/plugins")
            .copy("bower_components/tinymce/skins", "public/js/admin/tinymce/skins")
            .copy("bower_components/tinymce/themes/**/*.min.js", "public/js/admin/tinymce/themes")
            .copy("bower_components/solire.tinymce-i18n/langs", "public/js/admin/tinymce/langs")
            .copy("bower_components/tinymce/*.js", "resources/assets/js/admin/tinymce")
            .scripts("admin/tinymce/jquery.tinymce.js", "public/js/admin/tinymce/jquery.tinymce.min.js")
            .scripts("admin/tinymce/tinymce.jquery.js", "public/js/admin/tinymce/tinymce.jquery.min.js")
            .scripts("admin/tinymce/tinymce.js", "public/js/admin/tinymce/tinymce.min.js")


    /**
     * iCheck
     */
    mix
            .copy("bower_components/iCheck/skins", "public/css/admin/iCheck/skins")
            .copy("bower_components/iCheck/icheck.js", "resources/assets/js/admin")

    /**
     * Admin main styles and scripts
     */
    mix
            .styles([
                'admin/bootstrap.css',
                'admin/font-awesome.css',
                'admin/ionicons.css',
                'admin/select2.css',
                'admin/AdminLTE.css',
                'admin/skins/_all-skins.css',
                'admin/olapus.css',
            ], 'public/css/admin.css')
            .scripts([
                'admin/jquery.js',
                'admin/bootstrap.js',
                'admin/jquery.slimscroll.js',
                'admin/fastclick.js',
                'admin/speakingurl.js',
                'admin/squarethis.js',
                'admin/select2.full.js',
                'admin/icheck.js',
                'admin/jquery.lazyload.js', 
                'admin/app.js',
            ], 'public/js/admin.js')

    /**
     * VERSIONING
     */
    mix
            .version(['css/admin.css', 'js/admin.js', 'js/html5workaround.js', 'css/pace.css', 'js/pace.js']);

}); 