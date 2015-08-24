<?php
if ( ! function_exists( 'oxtheme_setup' ) ) :
/**
 * oxtheme setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 */
function oxtheme_setup() {
	if ( ! isset( $content_width ) ) $content_width = 600;

	/*
	 * Make theme available for translation.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'oxtheme', get_template_directory() . '/languages' );


	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 600, 372, true );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'extra/editor-style.css') );


	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'oxtheme' ),
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'oxtheme_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );
	
	// Show title tag in default.
	add_theme_support( 'title-tag' );
}
endif; // oxtheme_setup
add_action( 'after_setup_theme', 'oxtheme_setup' );

/**
 * Enqueue scripts and styles for the front end.
 *
 */
function oxtheme_scripts() {
	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/extra/font.css');
	// Load our main stylesheet.
	wp_enqueue_style( 'oxtheme-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'oxtheme_scripts' );


/**
 * Register three oxtheme widget areas.
 *
 */
function oxtheme_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'oxtheme' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'oxtheme' ),
		'before_widget' => '<aside id="%1$s" class="box %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Wide Widget Area', 'oxtheme' ),
		'id'            => 'sidebar-2',
		'description'   => __( '160px Wide Widget', 'oxtheme' ),
		'before_widget' => '<aside id="%1$s" class="box %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Narrow Widget Area', 'oxtheme' ),
		'id'            => 'sidebar-3',
		'description'   => __( '140px Wide Widget.', 'oxtheme' ),
		'before_widget' => '<aside id="%1$s" class="box %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'oxtheme_widgets_init' );


if ( ! function_exists( 'oxtheme_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @global WP_Query   $wp_query   WordPress Query object.
 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
 */
function oxtheme_paging_nav() {
	global $wp_query, $wp_rewrite;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Previous', 'oxtheme' ),
		'next_text' => __( 'Next &rarr;', 'oxtheme' ),
	) );

	if ( $links ) :

	?>
	<nav class="paging-navigation" role="navigation">
		<div class="pagination">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;



if ( ! function_exists( 'oxtheme_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 */
function oxtheme_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" id="postnavi" role="navigation">
			<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'oxtheme' ) );
			else :
				previous_post_link( __( '<div class="sleft">&laquo; %link</div>', 'oxtheme' ), '%title');
				next_post_link( __( '<div class="sright">%link &raquo;</div>', 'oxtheme' ),'%title');
			endif;
			?>
	</nav><!-- .navigation -->
	<?php
}
endif; 


/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 */
function oxtheme_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
	<?php
			the_post_thumbnail();
	?>
	</div>

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php
			the_post_thumbnail();
	?>
	</a>

	<?php endif; // End is_singular()
}

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function oxtheme_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'oxtheme' ), max( $paged, $page ) );
	}

	return $title;
}





/**
 * Set up the WordPress core custom header settings.
 *
 *
 * @uses oxtheme_header_style()
 * @uses oxtheme_admin_header_style()
 * @uses oxtheme_admin_header_image()
 */
function oxtheme_custom_header_setup() {
	/**
	 * Filter oxtheme custom-header support arguments.
	 *
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type bool   $header_text            Whether to display custom header text. Default false.
	 *     @type int    $width                  Width in pixels of the custom header image. Default 1260.
	 *     @type int    $height                 Height in pixels of the custom header image. Default 240.
	 *     @type bool   $flex_height            Whether to allow flexible-height header images. Default true.
	 *     @type string $admin_head_callback    Callback function used to style the image displayed in
	 *                                          the Appearance > Header screen.
	 *     @type string $admin_preview_callback Callback function used to create the custom header markup in
	 *                                          the Appearance > Header screen.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'oxtheme_custom_header_args', array(
		'default-text-color'     => 'fff',
		'width'                  => 1290,
		'height'                 => 240,
		'flex-height'            => true,
	) ) );
}
add_action( 'after_setup_theme', 'oxtheme_custom_header_setup' );


function oxtheme_filter_post_empty_title($title){
if($title == ''){
$time = get_the_time('Y-m-d');
$title = $time;
}
return $title;
}
add_filter('the_title','oxtheme_filter_post_empty_title');