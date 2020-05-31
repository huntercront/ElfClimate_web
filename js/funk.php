<?php

add_filter( 'get_search_form', 'my_search_form' );
function my_search_form( $form ) {

	$form = '
	<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
		<input type="text" value="' . get_search_query() . '" name="s" id="s" />
		<div class="search-form-input"><img src=""> <input type="submit" id="searchsubmit" value="" /></div>
	</form>';

	return $form;
}

//custom login logo
function my_login_logo(){
   echo '
   <style type="text/css">
        #login h1 a { background: url('. get_permalink() .'/wp-content/uploads/2020/04/elfclimate-logo.svg) no-repeat 0 0 !important;height:53px;width:164px; }
    </style>';
}
add_action('login_head', 'my_login_logo');

//like-button
function test_function() {
      // Set variables
      $input_test = $_POST['like_post'];
	$post_id = $_POST['post_id'];
      update_field('лайки', $input_test , $post_id );
   }
add_action( 'wp_ajax_nopriv_test_function',  'test_function' );
add_action( 'wp_ajax_test_function','test_function' );


//Добавление поддержки темы
if (function_exists('add_theme_support')) {
	add_theme_support('menus');
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
}
add_image_size( 'news_thumb', 400, 235, true );
add_image_size( 'sert', 200, 284, false );


//ajax load more post
function more_post_ajax(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 3;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;

    header("Content-Type: text/html");

    $args = array(
        'suppress_filters' => true,
        'post_type' => 'post',
        'posts_per_page' => $ppp,
        'paged'    => $page,
    );

    $loop = new WP_Query($args);

    $out = '';

    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();

	
		$category = get_the_category(); 
if($category[0]->cat_ID == 1){$cat_calss = ' article';}
if($category[0]->cat_ID == 4){$cat_calss = ' tip';}
if($category[0]->cat_ID == 3){$cat_calss = ' review';}
	
// 	 $out .= '<a href="'.get_the_permalink(). '" class="blog-right-slide">
// 		<div class="brs-content">
// 			<img src="'.get_the_post_thumbnail_url( $post,'full').'" alt="" class="brs-img">
// 			<div class="brs-head' .$cat_calss.'">'. $category[0]->cat_name.'</div>
// 			<h2 class="brs-title">'.get_the_title().'</h2>
// 			<div class="descr brs-descr">'.trim_excerpt([ 'maxchar'=>80 ]).'</div>
// 		</div>
// 	</a>';
	

$out .= '<a href="'.get_the_permalink().'" class="blog-list-card">
<div class="blc-content">
<div class="blc-img">
<div class="b-slide-head '.$cat_calss.'">'. $category[0]->cat_name.'</div>
<img class="blc-i-image" src="'.get_field('маленькая_картинка_записи', get_the_ID()).'" alt=""></div>
<h4 class="blc-title bloc-t">'.get_the_title().'</h4>
<div class="blc-descr descr">'.trim_excerpt([ 'maxchar'=>80 ]).'</div>
<div class="blc-footer sb-c"><span class="blc-footer-data">'.get_the_time('d/m/Y').'</span>
<div class="s-c blc-footer-view">
	<div class="blc-fv-img c-c">
		<img src="'.get_template_directory_uri().'/img/icons/eye-darc.svg" alt="">
	</div>
	<span>'.get_field('просмотры', get_the_ID()).'</span>
</div>
</div>
</div>
</a>';
	
	
	
    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');




//add newpost tipe portfolio

add_action('init', 'my_custom_init');
function my_custom_init(){
	register_post_type('portfolio', array(
		'labels'             => array(
			'name'               => 'Портфолио проектов', // Основное название типа записи
			'singular_name'      => 'Проект', // отдельное название записи типа Book
			'add_new'            => 'Добавить новый проект',
			'add_new_item'       => 'Добавить новый проект',
			'edit_item'          => 'Редактировать проект',
			'new_item'           => 'Новвй проект',
			'view_item'          => 'Посмотреть проект',
			'search_items'       => 'Найти проект',
			'not_found'          =>  'Проектов не найдено',
			'not_found_in_trash' => 'В корзине проектов не найдено',
			'parent_item_colon'  => '',
			'menu_name'          => 'Портфолио'

		  ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,
		'menu_icon' => 'dashicons-media-document',
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','editor','author')
	) );
}






//trim post descr

function trim_excerpt( $args = '' ){
global $post;

	if( is_string($args) )
		parse_str( $args, $args );

	$rg = (object) array_merge( array(
		'maxchar'     => 350,   // Макс. количество символов.
		'text'        => '',    // Какой текст обрезать (по умолчанию post_excerpt, если нет post_content.
								// Если в тексте есть `<!--more-->`, то `maxchar` игнорируется и берется
								// все до <!--more--> вместе с HTML.
		'autop'       => true,  // Заменить переносы строк на <p> и <br> или нет?
		'save_tags'   => '',    // Теги, которые нужно оставить в тексте, например '<strong><b><a>'.
		'more_text'   => 'Читать дальше...', // Текст ссылки `Читать дальше`.
		'ignore_more' => false, // нужно ли игнорировать <!--more--> в контенте
	), $args );

	$rg = apply_filters( 'kama_excerpt_args', $rg );

	if( ! $rg->text )
		$rg->text = $post->post_excerpt ?: $post->post_content;

	$text = $rg->text;
	// убираем блочные шорткоды: [foo]some data[/foo]. Учитывает markdown
	$text = preg_replace( '~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text );
	// убираем шоткоды: [singlepic id=3]. Учитывает markdown
	$text = preg_replace( '~\[/?[^\]]*\](?!\()~', '', $text );
	$text = trim( $text );

	// <!--more-->
	if( ! $rg->ignore_more  &&  strpos( $text, '<!--more-->') ){
		preg_match('/(.*)<!--more-->/s', $text, $mm );

		$text = trim( $mm[1] );

		$text_append = ' <a href="'. get_permalink( $post ) .'#more-'. $post->ID .'">'. $rg->more_text .'</a>';
	}
	// text, excerpt, content
	else {
		$text = trim( strip_tags($text, $rg->save_tags) );

		// Обрезаем
		if( mb_strlen($text) > $rg->maxchar ){
			$text = mb_substr( $text, 0, $rg->maxchar );
			$text = preg_replace( '~(.*)\s[^\s]*$~s', '\\1...', $text ); // кил последнее слово, оно 99% неполное
		}
	}

	// сохраняем переносы строк. Упрощенный аналог wpautop()
	if( $rg->autop ){
		$text = preg_replace(
			array("/\r/", "/\n{2,}/", "/\n/",   '~</p><br ?/?>~'),
			array('',     '</p><p>',  '<br />', '</p>'),
			$text
		);
	}

	$text = apply_filters( ' trim_excerpt', $text, $rg );

	if( isset($text_append) )
		$text .= $text_append;

	return ( $rg->autop && $text ) ? "<p>$text</p>" : $text;
}


//css js
add_action( 'wp_enqueue_scripts', 'styles__theme' );
add_action('wp_footer', 'add_scripts');

function styles__theme() {
	wp_enqueue_style( 'elfclimate', get_stylesheet_uri() );
	wp_enqueue_style( 'nuled', get_template_directory_uri() . '/css/nuled.css');
	wp_enqueue_style( 'style', get_template_directory_uri() . '/css/style.css' );
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">');
	//jquery
	wp_deregister_script('jquery-core');
	wp_deregister_script('jquery-ui');
	wp_deregister_script('jquery');
	wp_register_script( 'jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null, true );
	wp_register_script( 'jquery', false, array('jquery-core'), null, true );
	wp_enqueue_script( 'jquery' );

}
function add_scripts() {
		wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery'));
	if(is_page_template('contact-us-page.php')){
		wp_enqueue_script('map','https://api-maps.yandex.ru/2.1/?apikey=93fece7a-9fe4-47c6-80de-eaea41ec7d4a&lang=ru_RU', array('jquery'));
		wp_enqueue_script('map-init', get_template_directory_uri() . '/js/map.js', array('jquery','map'));
	}else{
		
	

	wp_enqueue_script('slider', get_template_directory_uri() . '/js/slider.js', array('jquery'));
		if(is_page_template('portfolio-page.php') or is_page('742')) {
		wp_enqueue_script('galery', get_template_directory_uri() .'/js/jquery.fancybox.min.js', array('jquery'));
		wp_enqueue_style( 'galery-css', get_template_directory_uri() . '/css/galery.css' );
	}
		}
}
 


//defer js load
function mihdan_add_defer_attribute( $tag, $handle ) {
    
  $handles = array(
    'cookies',
    'modal',
	  'fc-modal',
	  'fc-form',
	  
  );
    
   foreach( $handles as $defer_script) {
      if ( $defer_script === $handle ) {
         return str_replace( ' src', ' defer="defer" src', $tag );
      }
   }
 
   return $tag;
}
add_filter( 'script_loader_tag', 'mihdan_add_defer_attribute', 10, 2 );




//ACF cancel update
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
add_filter( 'site_transient_update_plugins', 'filter_plugin_opt' );
function filter_plugin_opt( $value ) {
	unset( $value->response['wp-hummingbird/wp-hummingbird.php'] );
	return $value;
}


function my_acf_init() {
	
	if( function_exists('acf_add_options_page') ) {

		
acf_add_options_page(array(
		'page_title' 	=> 'Соц сети и номера телефонов',
		'menu_title'	=> 'Контакты',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
	'icon_url' => 'dashicons-email',
	));
	acf_add_options_page(array(
		'page_title' 	=> 'Меню в футере',
		'menu_title'	=> 'Меню в футере',
		'menu_slug' 	=> 'theme-footer-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-tagcloud',
	));
		acf_add_options_page(array(
		'page_title' 	=> 'Меню в хедере',
		'menu_title'	=> 'Меню в хедере',
		'menu_slug' 	=> 'theme-header-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-tagcloud',
	));
}
}

add_action('acf/init', 'my_acf_init');

function register_acf_block_types() {

	// register a testimonial block.
	acf_register_block_type(array(
			'name'              => 'slider',
			'title'             => __('Слайдер под меню'),
			'description'       => __('Блок с добавление слайдера и собственных картинок'),
			'render_template'   => 'blocks/hero-slider.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'welcome-widgets-menus',
			'align' => 'wide',
			'keywords'          => array( 'Слайдер', 'Первый блок' )));
	
		acf_register_block_type(array(
			'name'              => 'choose-us',
			'title'             => __('Наши преимущества'),
			'description'       => __('Блок с преимуществами'),
			'render_template'   => 'blocks/choose-us.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon'              => 'dashicons-excerpt-view',
			'align' => 'wide',
			'keywords'          => array( 'Блок', 'Преимущества' )));
	
	
			acf_register_block_type(array(
			'name'              => 'cards-block',
			'title'             => __('Карточки с изображением'),
			'description'       => __('Карточки на сером фоне с ссылкой или вызовом модального окна'),
			'render_template'   => 'blocks/cards-block.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon'              => 'dashicons-excerpt-view',
			'align' => 'wide',
			'keywords'          => array( 'Блок', 'карточки', 'Вызов модального окна' )));
	
				acf_register_block_type(array(
			'name'              => 'services',
			'title'             => __('Слайдер с услугами'),
			'description'       => __('слайдер с прокруткой для услы с ссылкой на сторонние страницы'),
			'render_template'   => 'blocks/services.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Слайдер', 'усуги', 'ссылка на страницу' )));
	
	
	
				acf_register_block_type(array(
			'name'              => 'slider-blog',
			'title'             => __('Слайдер с записями блога'),
			'description'       => __('слайдер с прокруткой для записей из блога'),
			'render_template'   => 'blocks/blog-slider.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Слайдер', 'Блог' )));
	
	
	
					acf_register_block_type(array(
			'name'              => 'hero',
			'title'             => __('Первый блок для страницы'),
			'description'       => __('Заголовок с изображением'),
			'render_template'   => 'blocks/hero.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Картинка', 'Первый блок', 'Якорь форма' )));
	
	
						acf_register_block_type(array(
			'name'              => 'offer',
			'title'             => __('Блок с вкладками'),
			'description'       => __('Добавление вкладок с содержымим'),
			'render_template'   => 'blocks/offer.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Вкладки', 'акции', 'спец предложения' )));
	
	
							acf_register_block_type(array(
			'name'              => 'catalog',
			'title'             => __('Каталог'),
			'description'       => __('Блок с добавлением каталога'),
			'render_template'   => 'blocks/catalog.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Каталог', 'каталог продукции')));
	
	
								acf_register_block_type(array(
			'name'              => 'read-more',
			'title'             => __('Больше информации'),
			'description'       => __('Блок видами климатической техники'),
			'render_template'   => 'blocks/read-about.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'КУзнать больше', 'виды техники')));
	
	
									acf_register_block_type(array(
			'name'              => 'brands',
			'title'             => __('Популярные бренды'),
			'description'       => __('Блок с популярными брендами'),
			'render_template'   => 'blocks/brands.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'Бренды', 'Логотипы')));
	
	
									acf_register_block_type(array(
			'name'              => 'numbers-block',
			'title'             => __('Блок с цифрами'),
			'description'       => __('Блок с цифрами для описания шагов'),
			'render_template'   => 'blocks/numbers-block.php',
			'category'          => 'layout',
			'mode' => 'auto',
'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.19509 1.33791C1.7577 0.775299 2.52076 0.459229 3.31641 0.459229H17.3164C18.1121 0.459229 18.8751 0.775299 19.4377 1.33791C20.0003 1.90052 20.3164 2.66358 20.3164 3.45923V17.4592C20.3164 18.2549 20.0003 19.0179 19.4377 19.5805C18.8751 20.1432 18.1121 20.4592 17.3164 20.4592H3.31641C2.52076 20.4592 1.75769 20.1432 1.19509 19.5805C0.632477 19.0179 0.316406 18.2549 0.316406 17.4592V3.45923C0.316406 2.66358 0.632477 1.90052 1.19509 1.33791ZM11.3164 18.4592H17.3164C17.5816 18.4592 17.836 18.3539 18.0235 18.1663C18.2111 17.9788 18.3164 17.7244 18.3164 17.4592V3.45923C18.3164 3.19401 18.2111 2.93966 18.0235 2.75212C17.836 2.56459 17.5816 2.45923 17.3164 2.45923H11.3164V18.4592ZM9.31641 2.45923V18.4592H3.31641C3.05119 18.4592 2.79684 18.3539 2.6093 18.1663C2.42176 17.9788 2.31641 17.7244 2.31641 17.4592V3.45923C2.31641 3.19401 2.42176 2.93966 2.6093 2.75212C2.79684 2.56459 3.05119 2.45923 3.31641 2.45923H9.31641Z"/>
</svg>',
			'align' => 'wide',
			'keywords'          => array( 'шаги', 'цифры')));
	
	
			acf_register_block_type(array(
			'name'              => 'cols-text',
			'title'             => __('Заголовок с колонкой текста'),
			'description'       => __('Заголовок с колонкой текста'),
			'render_template'   => 'blocks/cols-text.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'editor-outdent',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'Колонки', 'заголовок')));
	
				acf_register_block_type(array(
			'name'              => 'work-with',
			'title'             => __('Работа с ElfClimate'),
			'description'       => __('Описание достоинств'),
			'render_template'   => 'blocks/work-with.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'admin-tools',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'Преимущества', 'заголовок')));
	
					acf_register_block_type(array(
			'name'              => 'cols-img',
			'title'             => __('Колонка с картинкой'),
			'description'       => __('Колонка текста с картинкой'),
			'render_template'   => 'blocks/cols-img.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'align-left',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'колонки', 'картинка')));
	
						acf_register_block_type(array(
			'name'              => 'fuul-img',
			'title'             => __('Широкая картнка'),
			'description'       => __('картинка в ширину контейнера'),
			'render_template'   => 'blocks/full-cont-img.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'editor-expand',
			'align' => 'wide',
			'keywords'          => array( 'Картинка', 'Ширинка уонтента')));
	
							acf_register_block_type(array(
			'name'              => 'double-cols-text',
			'title'             => __('Текст в 2 колонки'),
			'description'       => __('2 колнки текста рядом'),
			'render_template'   => 'blocks/double-cols-text.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'controls-pause',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'колонки')));
	
								acf_register_block_type(array(
			'name'              => 'cards-text-block',
			'title'             => __('Колнки карточка и описание'),
			'description'       => __('Колнки карточка и описание'),
			'render_template'   => 'blocks/cards-text-block.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'editor-table',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'колонки')));

									acf_register_block_type(array(
			'name'              => 'icons-descr',
			'title'             => __('Иконки с описанием'),
			'description'       => __('Икинки с опианием и опциональным заголовком'),
			'render_template'   => 'blocks/icons-descr.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'image-filter',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'колонки')));
	
	
										acf_register_block_type(array(
			'name'              => 'custom-code',
			'title'             => __('Блок для вставки кода'),
			'description'       => __('Код HTML'),
			'render_template'   => 'blocks/custom-code.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'editor-code',
			'align' => 'wide',
			'keywords'          => array( 'Текст', 'колонки')));
	
	
											acf_register_block_type(array(
			'name'              => 'sertificate',
			'title'             => __('Слайдер с сертификатами'),
			'description'       => __('Сертификаты'),
			'render_template'   => 'blocks/sertificate.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'images-alt2',
			'align' => 'wide',
			'keywords'          => array( 'sertificate', 'Сертификаты')));
	
acf_register_block_type(array(
			'name'              => 'only-text-block',
			'title'             => __('Текст во всю ширину'),
			'description'       => __('Текстовый блок во всю ширину контента'),
			'render_template'   => 'blocks/only-text-block.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'images-alt2',
			'align' => 'wide',
			'keywords'          => array( 'текст', 'вся ширина')));
	
	acf_register_block_type(array(
			'name'              => 'products',
			'title'             => __('Блок с тех газами'),
			'description'       => __('Технические газы с описанием'),
			'render_template'   => 'blocks/products.php',
			'category'          => 'layout',
			'mode' => 'auto',
			'icon' => 'cart',
			'align' => 'wide',
			'keywords'          => array( 'текст', 'карточка товара')));
}

// register blocks
if( function_exists('acf_register_block_type') ) {
	add_action('acf/init', 'register_acf_block_types');
}


