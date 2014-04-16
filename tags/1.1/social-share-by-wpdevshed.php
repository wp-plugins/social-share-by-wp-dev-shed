<?php
/*/
Plugin Name: Social Share by WP Dev Shed
Plugin URI: http://wordpress.org/plugins/social-share-by-wp-dev-shed/
Description: Adds Facebook and Twitter social share buttons to your blog posts.
Version: 1.1
Author: WP Dev Shed
Author URI: http://wpdevshed.com/
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
/*/

/**
 * Enqueue scripts and styles
 */
function sswpds_script_styles() {
	wp_enqueue_script( 'sswpds-script', plugin_dir_url(__FILE__) . 'js/sswpds-scripts.js', array('jquery'), '1.0.0' );
	wp_enqueue_style( 'sswpds-styles', plugin_dir_url(__FILE__) . 'sswpds-style.css' );
}
add_action('wp_enqueue_scripts', 'sswpds_script_styles');


/**
 * Add the social share buttons to the_content()
 */
function sswpds_filter_the_content( $content ) {
	$new_content = '<div class="sswpds-social-wrap">' . "\n";
	$new_content .= '<a href="' . esc_url('http://www.facebook.com/share.php?u=') . get_permalink() . '" target="_blank"><img src="' . plugin_dir_url(__FILE__) . 'images/icon-fb.png' . '" alt="" /></a>' . "\n";
	$new_content .= '<a href="' . esc_url('http://twitter.com/home?status=') . get_the_title() . esc_attr('%0D%0A') . get_permalink() . '" target="_blank"><img src="' . plugin_dir_url(__FILE__) . 'images/icon-tw.png' . '" alt="" /></a>' . "\n";
	$new_content .= '</div>' . "\n";
    $custom_content = $new_content . $content . $new_content;
	if ( is_single() ) {
    	return $custom_content;
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'sswpds_filter_the_content' );


?>