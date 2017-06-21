<?php

/** Add favicon link to the header */
function favicon_link() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
}
add_action( 'wp_head', 'favicon_link' );



/** Add Apple icons to the header */

function apple_icons() {
	echo '<link rel="apple-touch-icon" sizes="144x144" href="apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" href="apple-touch-icon.png">' . "\n";
}
add_action( 'wp_head', 'apple_icons' );



/** Dequeue standard Google Web Fonts loaders */
function mytheme_dequeue_fonts() {
wp_dequeue_style( 'twentytwelve-fonts' );
}
add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );



/** Enqueue Google Fonts to use Lato across theme */
function load_fonts() {
  wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:300italic,300,900');
  wp_enqueue_style( 'googleFonts');
}
add_action('wp_print_styles', 'load_fonts');



/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function apples_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Home Page Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears on the home page in the Apples Theme', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'WooCommerce Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-wc',
		'description' => __( 'Appears on WooCommerce pages in the Apples Theme', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'widgets_init', 'apples_widgets_init' );




/** Generate Facebook Open Graph Meta Tags */
/** based on work by Paul Underwood — http://www.paulund.co.uk/add-facebook-open-graph-tags-to-wordpress */

add_action('wp_head', 'add_fb_open_graph_tags');
function add_fb_open_graph_tags() {
	if (is_single()||is_page()) {
		global $post;
		if(get_the_post_thumbnail($post->ID, 'thumbnail')) {
			$thumbnail_id = get_post_thumbnail_id($post->ID);
			$thumbnail_object = get_post($thumbnail_id);
			$image = $thumbnail_object->guid;
		} else {
			$image = 'http://claraiannotta.com/wp-content/uploads/2013/02/Clara_Iannotta-e1365368373758.png'; // Change this to the URL of the image you want beside your links shown on Facebook
		}
		//$description = get_bloginfo('description');
		$description = my_excerpt( $post->post_content, $post->post_excerpt );
		$description = strip_tags($description);
		$description = str_replace("\"", "'", $description);
?>
<meta property="og:title" content="<?php the_title(); ?>" />
<meta property="og:type" content="article" />
<meta property="og:image" content="<?php echo $image; ?>" />
<meta property="og:url" content="<?php the_permalink(); ?>" />
<meta property="og:description" content="<?php echo $description ?>" />
<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
<?php 	}
}
function my_excerpt($text, $excerpt){
    if ($excerpt) return $excerpt;
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $text = strip_tags($text);
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    $words = preg_split("/[\n
	 ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
    } else {
            $text = implode(' ', $words);
    }
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}



/** Enable excerpts on pages */
add_post_type_support( 'page', 'excerpt' );



/** Create widget to display post/page featured images */
function apples_ftrdimg_widget() {

    echo get_the_post_thumbnail($page->ID, 'full', array('class' => 'entry-sidebar-ftrd-img'));

}
if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('The Featured Image'), 'apples_ftrdimg_widget');

// Use website URL for admin log-in logo link
function wpc_url_login(){
	return "http://www.claraiannotta.com/"; // your URL here
}
add_filter('login_headerurl', 'wpc_url_login');



/** New entry-meta function */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<time class="entry-date" datetime="%3$s">%4$s</time>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	$utility_text = __( '%3$s', 'twentytwelve' );

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}



/** Add Custom logo to log-in page */
function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_bloginfo( 'stylesheet_directory' ) ?>/img/ci-login-logo.png);
            background-size: 274px 63px;
            width: 274px;	
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/** Custom text on the WooCommerce order thank you page. */
function order_received_spam_warning( $text, $order ) {
    $new = $text . '<br /><br />You will receive an e-mail containing your download link.<br /><br /><strong>If you don’t receive your e-mail in the next 20 minutes, please check your SPAM folder!</strong>';
    return $new;
}
add_filter('woocommerce_thankyou_order_received_text', 'order_received_spam_warning', 10, 2 );
?>