<?php



function my_ads_shortcode( $attr ) {
    ob_start();
    get_template_part( 'template-parts/map' );
    return ob_get_clean();
}
add_shortcode( 'map', 'my_ads_shortcode' );
//wp needs
if (function_exists('add_theme_support')) {
	add_theme_support('menus');
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
}
//post thumb
add_image_size( 'news_thumb', 420, 245, true );

//css js
add_action( 'wp_enqueue_scripts', 'styles__theme' );
add_action('wp_footer', 'add_scripts');

function styles__theme() {
	wp_enqueue_style( 'mmcintergeo.ru', get_stylesheet_uri() );
	wp_enqueue_style( 'main-style', get_template_directory_uri() . '/css/style.css' );
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Istok+Web:700|Ubuntu&display=swap&subset=cyrillic' );
	//jquery
	wp_deregister_script('jquery-core');
	wp_deregister_script('jquery');
	wp_register_script( 'jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null, true );
	wp_register_script( 'jquery', false, array('jquery-core'), null, true );
	wp_enqueue_script( 'jquery' );
}
function add_scripts() {
	wp_enqueue_script('main-script', get_template_directory_uri() . '/js/script.js', array('jquery'));
	wp_enqueue_script('modal', get_template_directory_uri() . '/js/modal.js', array('jquery'));
wp_enqueue_script('cookies', get_template_directory_uri() . '/js/cookies.js', array('jquery'));





    if ( is_page(5) or is_page(12) or is_page(14)) {
wp_enqueue_script('slider', get_template_directory_uri() . '/js/siema.min.js', array('jquery'));
	}
	    if ( is_page(10) or is_page(12) or is_page(16) or is_page(95)) {
		wp_enqueue_script('map', get_template_directory_uri() . '/js/map.js', array('jquery'));
		}
// 	    if ( is_page('876') or is_single()) {
// 	wp_enqueue_style( 'simplelightbox-css', get_template_directory_uri() . '/assets/css/simplelightbox.min.css');
// 	wp_enqueue_script('simplelightbox-js', get_template_directory_uri() . '/assets/js/simple-lightbox.min.js', array(), NULL, false);
// 	}
}

//defer js load
function mihdan_add_defer_attribute( $tag, $handle ) {
    
  $handles = array(
    'cookies',
    'modal',
  );
    
   foreach( $handles as $defer_script) {
      if ( $defer_script === $handle ) {
         return str_replace( ' src', ' defer="defer" src', $tag );
      }
   }
 
   return $tag;
}
add_filter( 'script_loader_tag', 'mihdan_add_defer_attribute', 10, 2 );



//update
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );
function filter_plugin_updates( $value ) {
	unset( $value->response['advanced-custom-fields-pro/acf.php'] );
	return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_formcraft' );
function filter_plugin_formcraft( $value ) {
	unset( $value->response['formcraft3/formcraft-main.php'] );
	return $value;
}

//add footer menu

function my_acf_init() {
	
	if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Меню в футере',
		'menu_title'	=> 'Меню в футере',
		'menu_slug' 	=> 'theme-footer-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-tagcloud',
	));
			acf_add_options_page(array(
		'page_title' 	=> 'Меню',
		'menu_title'	=> 'Меню',
		'menu_slug' 	=> 'theme-menu-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-tagcloud',
	));
}
}

add_action('acf/init', 'my_acf_init');