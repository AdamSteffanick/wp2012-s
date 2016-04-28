<?php
/* =Custom Functions and Hacks
-------------------------------------------------------------- */
/* add copyright year [yyyy-yyyy] */
function theme_copyright($year = 'auto') {
  if(intval($year) == 'auto'){ $year = date('Y'); }
  if(intval($year) == date('Y')){ echo intval($year); }
  if(intval($year) < date('Y')){ echo intval($year) . '&#8211;' . date('Y'); }
  if(intval($year) > date('Y')){ echo date('Y'); }
}
/* escape HTML entities in <code> tags */
function theme_code_esc_html($content) {
  return preg_replace_callback(
    '#(<code.*?>)(.*?)(</code>)#imsu',
    create_function(
      '$i',
      'return $i[1].esc_html($i[2]).$i[3];'
    ),
    $content
  );
}
/* Google WebFont */
function theme_google_webfonts() {
  wp_enqueue_style( 'noto-sans', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,400italic,700,700italic:latin,latin-ext', 'twentytwelve-style', null, all);
}
/* change schema.org search URL generated by Yoast SEO for use with Google Custom Search Engine, a Multisite Network, and SSL */
function theme_change_json_ld_search_url() {
  return network_home_url( '/', 'https' ) . 'search/?q={search_term_string}';
}
/* remove unused WordPress features */
function theme_cleaner() {
  /* remove WordPress generator meta tag */
  remove_action('wp_head', 'wp_generator');
  add_filter('the_generator', '__return_false');
  /* remove Windows Live Writer support */
  remove_action('wp_head', 'wlwmanifest_link');
  /* remove RSD support */
  remove_action('wp_head', 'rsd_link');
  /* remove WordPress shortlink support */
  remove_action('wp_head', 'wp_shortlink_wp_head');
}
/* remove support for older versions of Internet Explorer */
function theme_remove_old_ie_support() {
  /* remove ie.css */
  wp_deregister_style( 'twentytwelve-ie' );
}
/* remove ID attribute from stylesheets */
function theme_remove_style_id($link) {
  return preg_replace("/id='.*-css'/", '', $link);
}
/* =CSS Functions
-------------------------------------------------------------- */
/* load bootstrap.min.css before style.css */
function theme_style() {
  /* enqueue Bootstrap CSS via a CDN */
  wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', '', null, 'all');
  /* enqueue Font Awesome CSS via a CDN with Bootstrap dependency */
  wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', 'bootstrap', null, 'all');
  /* remove style.css */
  wp_deregister_style('twentytwelve-style');
  /* register/enqueue style.css with Bootstrap dependency */
  wp_enqueue_style('twentytwelve-style', network_home_url( '/', 'https' ) . 'wordpress/wp-content/themes/WP2012-Steffanick/style.css', 'bootstrap', null, all);
  wp_deregister_style('A2A_SHARE_SAVE');
  /* register/enqueue style.css with Bootstrap dependency */
  wp_enqueue_style('A2A_SHARE_SAVE', network_home_url( '/', 'https' ) . 'wordpress/wp-content/plugins/add-to-any/addtoany.min.css', 'twentytwelve-style', null, all);
}
/* remove the Open Sans font */
function theme_remove_open_sans() {
  wp_deregister_style( 'twentytwelve-fonts' );
}
/* =JavaScript Functions
-------------------------------------------------------------- */
/* load jquery.min.js (CDN), bootstrap.min.js (CDN), navigation.js, scroll-affix.js and run_prettify.js (CDN); remove jQuery Migrate */
function theme_javascript() {
  /* load jQuery via a CDN unless logged in as an administrator */
  if (!is_admin()) {
    /* remove local jQuery JavaScript */
    wp_deregister_script('jquery-core');
    /* register/enqueue jQuery JavaScript via a CDN in the <head> */
    wp_enqueue_script('jquery-core', 'https://code.jquery.com/jquery-2.2.3.min.js', '', null, false);
    /* remove jQuery Migrate */
    wp_deregister_script('jquery-migrate');
    /* remove wp-embed.min.js */
    wp_deregister_script( 'wp-embed' );
  }
  /* register/enqueue Bootstrap JavaScript via a CDN with jQuery dependency before </body> */
  wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', 'jquery-core', null, true);
  /* register/enqueue navigation.js with jQuery dependency before </body> */
  wp_enqueue_script( 'twentytwelve-navigation', network_home_url( '/', 'https' ) . 'wordpress/wp-content/themes/twentytwelve/js/navigation.js', 'jquery-core', null, true );
  /* register/enqueue scroll-affix.js with jQuery dependency before </body> */
  wp_enqueue_script( 'scroll-affix', network_home_url( '/', 'https' ) . 'wordpress/wp-content/themes/WP2012-Steffanick/js/scroll-affix.js', 'jquery-core', null, true );
  /* register/enqueue Google Code Prettify JavaScript via a CDN before </body> */
  wp_enqueue_script('google-code_prettify', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js', '', null, true);
}

/* include Google Analytics */
function theme_google_analytics() {
  include_once($_SERVER["DOCUMENT_ROOT"]."/static/analyticstracking.php");
}

/* echo browser update */
function theme_browser_update() {
echo '<script type="text/javascript"> 
  var $buoop = {c:2}; 
  function $buo_f(){
    var e = document.createElement("script"); 
    e.src = "https://browser-update.org/update.min.js"; 
    document.body.appendChild(e);
  };
  try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
  catch(e){window.attachEvent("onload", $buo_f)}
  </script>';
}

/* =Filters and Actions
-------------------------------------------------------------- */
/* NOTE: default priority = 10 */

add_filter( 'the_content', 'theme_code_esc_html' );
add_filter( 'wpseo_json_ld_search_url', 'theme_change_json_ld_search_url' );
add_filter( 'style_loader_tag' , 'theme_remove_style_id' );

add_action( 'wp_enqueue_scripts', 'theme_style' );
add_action( 'wp_enqueue_scripts', 'theme_remove_open_sans', 11 );
add_action( 'wp_enqueue_scripts', 'theme_remove_old_ie_support', 11 );
add_action( 'wp_enqueue_scripts', 'theme_javascript' );

add_action( 'after_setup_theme' , 'theme_cleaner' );

add_action( 'wp_footer', 'theme_google_webfonts' );
add_action( 'wp_footer', 'theme_browser_update' );
add_action( 'wp_footer', 'theme_google_analytics' );
?>