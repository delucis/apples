<?php

/** Register Event Custom Post Type */
function event_post_type() {
	$labels = array(
		'name'                => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Events', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Events', 'text_domain' ),
		'view_item'           => __( 'View Event', 'text_domain' ),
		'add_new_item'        => __( 'Add New Event', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Event', 'text_domain' ),
		'update_item'         => __( 'Update Event', 'text_domain' ),
		'search_items'        => __( 'Search Events', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                => 'event',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'event', 'text_domain' ),
		'description'         => __( 'Entry containing information about an event', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar',
		'can_export'          => true,
		'has_archive'         => 'archives',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
	);
	register_post_type( 'event', $args );
}
// Hook into the 'init' action
add_action( 'init', 'event_post_type', 0 );



/** Create work_size taxonomy */
function work_size_init() {
	register_taxonomy(
		'work_size',
		'work',
		array(
			'label'   => __( 'Work Sizes' ),
			'labels'  => array(
				'singular_name' 		=> 'Work Size',
				'search_items'  		=> 'Search Work Sizes',
				'popular_items' 		=> 'Popular Work Sizes',
				'all_items'					=> 'All Work Sizes',
				'parent_item'				=> 'Parent Work Size',
				'parent_item_colon' => 'Parent Work Size:',
				'edit_item'					=> 'Edit Work Size',
				'view_item'					=> 'View Work Size',
				'update_item'				=> 'Update Work Size',
				'add_new_item'  		=> 'Add New Work Size',
				'new_item_name' 		=> 'New Work Size Name',
				'not_found'					=> 'No work sizes found',
				'no_terms'					=> 'No work sizes'
			),
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'works' ),
			'public' => true
		)
	);

	function apples_add_work_size( $term, $args ) {
		if ( !term_exists( $term, 'work_size' ) ) {
			wp_insert_term(
				$term,
				'work_size',
				$args
			);
		}
	}

	apples_add_work_size(
		'Solo Works',
		array(
			'description' => 'Works for a single instrument.',
			'slug' => 'solo-works'
		)
	);

	apples_add_work_size(
		'Chamber Music',
		array(
			'description' => 'Works for small groups of musicians.',
			'slug' => 'chamber-music'
		)
	);

	apples_add_work_size(
		'Works for Ensemble',
		array(
			'description' => 'Works for larger groups of musicians.',
			'slug' => 'ensemble'
		)
	);

	apples_add_work_size(
		'Works for Orchestra',
		array(
			'description' => 'Works for an orchestra.',
			'slug' => 'orchestra'
		)
	);

}
add_action( 'init', 'work_size_init' );


/** Handle permalink display on Edit Work pages */
add_filter('post_type_link', 'works_permalink_structure', 10, 4);
function works_permalink_structure($post_link, $post, $leavename, $sample)
{
    if ( false !== strpos( $post_link, '%work_size%' ) ) {
        $work_type_term = get_the_terms( $post->ID, 'work_size' );
				if ( $work_type_term ) {
					$post_link = str_replace( '%work_size%', array_pop( $work_type_term )->slug, $post_link );
				}
    }
    return $post_link;
}



/** Register Work Custom Post Type */
function work_post_type() {
	// Callback to hide the work_size interface, handled via ACF instead.
	function apples_hide_work_size_meta_box() {
		remove_meta_box('work_sizediv', 'work', 'side');
	}

	$labels = array(
		'name'                => _x( 'Works', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Work', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Works', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Works', 'text_domain' ),
		'view_item'           => __( 'View Work', 'text_domain' ),
		'add_new_item'        => __( 'Add New Work', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Work', 'text_domain' ),
		'update_item'         => __( 'Update Work', 'text_domain' ),
		'search_items'        => __( 'Search Works', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                => 'works/%work_size%',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'work', 'text_domain', 'author' ),
		'description'         => __( 'Entry containing information about a work', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'taxonomies'					=> array( 'work_size' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-playlist-audio',
		'can_export'          => true,
		'has_archive'         => 'archives',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
		'register_meta_box_cb'=> 'apples_hide_work_size_meta_box'
	);
	register_post_type( 'work', $args );
}
// Hook into the 'init' action
add_action( 'init', 'work_post_type', 0 );



/** Allow events to be sorted by event date */
// Get date start
function get_dtstart($post_ID) {
    $dtstart = get_field('dtstart', $post_ID);
    if ($dtstart) {
        $startdate = DateTime::createFromFormat('d/m/Y g:i a', $dtstart);
        return $startdate->format('Y/m/d')	;
    }
}
// Add new column
function apples_columns_head($defaults) {
	$columns = array_slice($defaults, 0, 2, true) +
    array("dtstart" => "Event Date") +
    array_slice($defaults, 2, count($defaults) - 1, true) ;
    return $columns;
}
// Show the start date
function apples_columns_content($column_name, $post_ID) {
    if ($column_name == 'dtstart') {
        $post_date = get_dtstart($post_ID);
        if ($post_date) {
            echo $post_date;
        }
    }
}
// Hook
add_filter('manage_event_posts_columns', 'apples_columns_head');
add_action('manage_event_posts_custom_column', 'apples_columns_content', 10, 2);
// Make sortable
function my_sortable_dtstart_column( $columns ) {
	$columns['dtstart'] = 'dtstart';
	return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'my_sortable_dtstart_column' );
// Fix orderby query
add_action( 'pre_get_posts', 'my_dtstart_orderby' );
function my_dtstart_orderby( $query ) {
	if( ! is_admin() )
		return;
  if( ! function_exists('get_current_screen') )
    return;
	$screen = get_current_screen();
	if ($screen->base == 'edit') {
		if ( $screen->post_type == 'event' ) {
			$orderby = $query->get( 'orderby');
			if( 'dtstart' == $orderby || 'menu_order title' != $orderby && 'date' != $orderby && 'title' != $orderby ) {
		        $query->set('meta_key','dtstart');
	    	    $query->set('orderby','meta_value_num');
			}
    }
	}
}



// Add custom post types to dashboard ‘At a Glance’ module
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';
    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            } else {
            $output = '<span>' . $num . ' ' . $text . '</span>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
    }
}
// Show events icon in ‘At a Glance’ module
add_action( 'admin_head', 'style_dashboard_glance_items' );
function style_dashboard_glance_items() {
	echo '<style>
		#dashboard_right_now .event-count a:before,
		#dashboard_right_now .event-count span:before {
			content: "\f145";
		}
		#dashboard_right_now .work-count a:before,
		#dashboard_right_now .work-count span:before {
			content: "\f492";
		}
	</style>';
}



function apples_configure_acf() {
	acf_update_setting('google_api_key', 'AIzaSyBDQ6v_XeOTtjg_D2iyxrjgfoDilXmglMA');
}
add_action('acf/init', 'apples_configure_acf');



// Setup core options on theme activation
function options_setup() {
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	update_option( 'blogname', 'Clara Iannotta' );
	update_option( 'blogdescription', 'composer' );
	update_option( 'timezone_string', 'Europe/Berlin' );
	update_option( 'date_format', 'j F Y' );
	update_option( 'permalink_structure', '/%year%/%monthnum%/%postname%/' );
	update_option( 'posts_per_page', 8 );
	update_option( 'posts_per_rss', 5 );
}
add_action( 'after_switch_theme', 'options_setup' );



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
			$image = 'http://claraiannotta.com/wp-content/uploads/2016/10/14589704_1310364378983890_1314027836617419242_o.jpg'; // Change this to the URL of the image you want beside your links shown on Facebook
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
    return apply_filters('wp_trim_excerpt', $text, $excerpt);
}



/** Enable excerpts on pages */
add_post_type_support( 'page', 'excerpt' );



/** Create widget to display post/page featured images */
function apples_ftrdimg_widget() {
  echo get_the_post_thumbnail(null , 'full', array('class' => 'entry-sidebar-ftrd-img'));
}
if ( function_exists('wp_register_sidebar_widget') )
    wp_register_sidebar_widget('apples-featured-image-widget', 'The Featured Image', 'apples_ftrdimg_widget');

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


// Function for dynamic copyright date in footer
function site_copyright() {
  global $wpdb;
  $copyright_dates = $wpdb->get_results("
  SELECT
  YEAR(min(post_date_gmt)) AS firstdate,
  YEAR(max(post_date_gmt)) AS lastdate
  FROM
  $wpdb->posts
  WHERE
  post_status = 'publish'
  ");
  $rightsholder = get_bloginfo('name');
  $output = '';
  if($copyright_dates) {
    $copyright = "Copyright &copy; " . $copyright_dates[0]->firstdate;
    if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
      $copyright .= '-' . $copyright_dates[0]->lastdate;
    }
    $copyright .= ' ' . $rightsholder;
    $output = $copyright;
  }
  return $output;
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
