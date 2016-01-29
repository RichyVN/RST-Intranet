<?php
/**
* My functions and definitions
**/

define( 'THEMEPATH', get_bloginfo('stylesheet_directory'));
define( 'IMAGES', THEMEPATH. "/images"); 

// Define variable for url
function custom_query_vars_filter($vars) {
  $vars[] = 'kraanid' ;
	$vars[] .= 'lijst_id';
return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );

add_action( 'user_profile_update_errors', 'remove_empty_email_error' );

// Remove required email address in userprofile
function remove_empty_email_error( $arg ) {
    if ( !empty( $arg->errors['empty_email'] ) ) unset( $arg->errors['empty_email'] );
}

// Remove emoji-icons support 
function pw_remove_emojicons() 
{
// Remove from comment feed and RSS
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
// Remove from emails
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
// Remove from head tag
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
// Remove from print related styling
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
// Remove from admin area
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'pw_remove_emojicons' );

// Remove social medio options from user profile page
function si_add_contactmethod( $contactmethods ) {
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  unset($contactmethods['twitter']);  
  unset($contactmethods['facebook']);
  unset($contactmethods['linkedin']);
  unset($contactmethods['googleplus']);
  return $contactmethods;
}
add_filter('user_contactmethods','si_add_contactmethod',10,1);

// Remove personal bio options block from user profile page
if (! function_exists('remove_plain_bio') ){
    function remove_plain_bio($buffer) {
        $titles = array('#<h3>About Yourself</h3>#','#<h3>About the user</h3>#');
        $buffer=preg_replace($titles,'<h3> </h3>',$buffer,1);
        $biotable='#<h3> </h3>.+?<table.+?/tr>#s';
        $buffer=preg_replace($biotable,'<h3> </h3> <table class="form-table">',$buffer,1);
        return $buffer;
    }
    function profile_admin_buffer_start() { ob_start("remove_plain_bio"); }
    function profile_admin_buffer_end() { ob_end_flush(); }
}
add_action('admin_head', 'profile_admin_buffer_start');
add_action('admin_footer', 'profile_admin_buffer_end');

// Register extra sidebars.
function rstbv_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Header-Widget', 'rstbv' ),
		'id' => 'sidebar-header',
		'description' => __( 'Appears in the header', 'rstbv' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer-Widget', 'rstbv' ),
		'id' => 'footer-1',
		'description' => __( 'Appears in the footer', 'rstbv' ),
	) );
}
add_action( 'widgets_init', 'rstbv_widgets_init' );

// Register extra thumbnail sizes.
add_theme_support( 'post-thumbnails' );
add_image_size( 'singlepost-thumb', 750, 250, true ); // featured images
	
// Register extra menus.
register_nav_menu( 'Secondary', __( 'Secondary Menu', 'rstbv' ) );
register_nav_menu( 'Sidebar', __( 'Sidebar Menu', 'rstbv' ) );

// Register new Admin icon styles for custom icons.
function add_menu_icons_styles(){
?> 
<style>
#adminmenu #menu-posts-weekmenu div.wp-menu-image:before {
	color: orange;
	text-shadow: 1px 1px 1px black;
}
#adminmenu #menu-posts-kranen div.wp-menu-image:before {
	color: green;
	text-shadow: 1px 1px 1px black;
}
#adminmenu #menu-posts-werklijst div.wp-menu-image:before {
 color: yellow;
	text-shadow: 1px 1px 1px black;
}
</style>
<?php 
}
add_action( 'admin_head', 'add_menu_icons_styles' ); 

// Wijzig de standaard meta entries bij posts en archive bestanden door icons
if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :

function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'Bekijk alle berichten van %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
    if ( $tag_list ) {
        $utility_text = __( '
        <span class="posted-in">%1$s</span>
        <span class="tagged-as">%2$s</span>
        <span class="posted-on">%3$s</span>
        <span class="posted-by">%4$s</span>.', 'twentytwelve' );
    } elseif ( $categories_list ) {
        $utility_text = __( '
        <span class="posted-in">%1$s</span>
        <span class="posted-on">%3$s</span>
        <span class="posted-by">%4$s</span>.', 'twentytwelve' );
    } else {
        $utility_text = __( '
        <span class="posted-on">%3$s</span>
        <span class="posted-by">%4$s</span>.', 'twentytwelve' );
    }
	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

