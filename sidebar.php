<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	<?php if ( is_woocommerce() ) : ?>
		<?php if ( is_active_sidebar( 'sidebar-wc' ) ) : ?>
			<div id="secondary" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-wc' ); ?>
			</div><!-- #secondary -->
		<?php endif; ?>
	<?php elseif ( is_front_page() || is_archive() || is_single() && ! has_post_thumbnail() ) : ?>
		<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
			<div id="secondary" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-4' ); ?>
			</div><!-- #secondary -->
		<?php endif; ?>
	<?php else : ?>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div id="secondary" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div><!-- #secondary -->
		<?php endif; ?>
	<?php endif; ?>