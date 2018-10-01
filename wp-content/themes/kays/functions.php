<?php
/**
 * Kays functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Kays
 */

if ( ! function_exists( 'kays_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kays_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Kays, use a find and replace
	 * to change 'kays' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'kays', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
        add_image_size( 'kays-full-bleed', 2000, 1200, true);
        add_image_size('kays-index-img', 800, 450, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'kays' ),
                'social' => esc_html__( 'Social Media Menu', 'kays' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kays_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
        
        //Add theme support for Custom Logo
        add_theme_support( 'custom-logo', array(
            'width' => 90,
            'height' => 90,
            'flex-width' => true,
        ) );
        
        /* Editor styles */
        add_editor_style( array( 'inc/editor-styles.css', kays_fonts_url() ));
}
endif;
add_action( 'after_setup_theme', 'kays_setup' );

/**
 * Register custom fonts.
 */
function kays_fonts_url() {
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Ruluko and Dekko, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$ruluko = _x( 'on', 'Ruluko font: on or off', 'kays' );
	$dekko = _x( 'on', 'Dekko font: on or off', 'kays' );

	$font_families = array();
	
	if ( 'off' !== $ruluko ) {
		$font_families[] = 'Ruluko';
	}
	
	if ( 'off' !== $dekko ) {
		$font_families[] = 'Dekko';
	}
	
	
	if ( in_array( 'on', array($ruluko, $dekko) ) ) {

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function kays_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'kays-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'kays_resource_hints', 10, 2 );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kays_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kays_content_width', 640 );
}
add_action( 'after_setup_theme', 'kays_content_width', 0 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @origin Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function kays_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];
        // if the minimum width of the screen is wider than 900 pixels then the image will be 700 pixels wide 
        // at the widest. If the screen is narrower than that, then the image might be up to 900 pixels wide.
	if ( 900 <= $width ) {
		$sizes = '(min-width: 900px) 700px, 900px';
	}
        // over 900 pixels the width is a max of 600 pixels. Otherwise it's still 900 pixels
	if ( is_active_sidebar( 'sidebar-1' ) || is_active_sidebar( 'sidebar-2' ) ) {
		$sizes = '(min-width: 900px) 600px, 900px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'kays_content_image_sizes_attr', 10, 2 );

/**
 * Filter the `sizes` value in the header image markup.
 *
 * @origin Twenty Seventeen 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function kays_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'kays_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @origin Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function kays_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
        // if not on a singular page, meaning we're on an archive or the index page or anywhere else
        //  and we currently have an active sidebar and the screen is narrower than 900 pixels, 
        //  then the image will be about 90% of the viewport width. If on wider screens, 
        //  so above 900 pixels, then the width of the image will always be 800 pixels. 
        //  If don't have an active sidebar, then the values change again. 
        //  So under 1000 pixel wide screen the width is about 90vw again, 
        //  and anything over that it will always be no wider than 1000 pixels
	if ( !is_singular() ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$attr['sizes'] = '(max-width: 900px) 90vw, 800px';
		} else {
			$attr['sizes'] = '(max-width: 1000px) 90vw, 1000px';
		}
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'kays_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kays_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'kays' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'kays' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
        register_sidebar( array(
		'name'          => esc_html__( 'Page Sidebar', 'kays' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add page sidebar widgets here.', 'kays' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
        register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'kays' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add footer widgets here.', 'kays' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'kays_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kays_scripts() {
    //Enqueue Google Fonts: Ruluko and Dekko
        wp_enqueue_style( 'kays-fonts', kays_fonts_url() );
        
	wp_enqueue_style( 'kays-style', get_stylesheet_uri() );

	wp_enqueue_script( 'kays-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20151215', true );

	wp_localize_script( 'kays-navigation', 'kaysScreenReaderText', array(
            'expand' => __( 'Expand child menu', 'kays'),
            'collapse' => __( 'Collapse child menu', 'kays'),
	));
        
        wp_enqueue_script( 'kays-functions', get_template_directory_uri() . '/js/functions.js', array('jquery'), '20180920', true );
        
        wp_enqueue_script( 'kays-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kays_scripts', 'kays-fonts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load SVG icon functions.
 */
require get_template_directory() . '/inc/icon-functions.php';