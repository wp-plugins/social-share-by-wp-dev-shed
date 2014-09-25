<?php
/*/
Plugin Name: Social Share by WP Dev Shed
Plugin URI: http://wordpress.org/plugins/social-share-by-wp-dev-shed/
Description: Adds Facebook and Twitter social share buttons to your blog posts.
Version: 1.2
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
    
	// display social share only in single page
	if ( is_single() ) {
		// display social share both before and after content
		if( (get_theme_mod( 'social_share_display_before_content' )) && (get_theme_mod( 'social_share_display_after_content' )) ) {
			return $new_content . $content . $new_content;
		
		// display social share before content
		} else if( get_theme_mod( 'social_share_display_before_content' ) ) {
			return $new_content . $content;
		
		// display social share after content
		} else if( get_theme_mod( 'social_share_display_after_content' ) ) {
			return $content . $new_content;
		// display on both
		} else {
			return $content;
		}
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'sswpds_filter_the_content' );

/*
 * Load customize object
 */
function rs_social_share_plugin_customizer( $wp_customize ) {
	/* category link in homepage option */
	$wp_customize->add_section( 'social_share_display_section' , array(
		'title'       => __( 'Display Social Share', 'social_share' ),
		'priority'    => 34,
		'description' => __( 'Option to show/hide the social share before content, after content or display on both which is by default.', 'surfarama' ),
	) );
	
	$wp_customize->add_setting( 'social_share_display_before_content', array (
		'default' 	=> 1,
		'sanitize_callback' => 'rs_social_share_sanitize_checkbox',
	) );
	
	$wp_customize->add_control('social_share_display_before_content', array(
		'settings' 	=> 'social_share_display_before_content',
		'label' 	=> __('Show social share before content?', 'social_share'),
		'section' 	=> 'social_share_display_section',
		'type' 		=> 'checkbox',
	));
	
	$wp_customize->add_setting( 'social_share_display_after_content', array (
		'default' 	=> 0,
		'sanitize_callback' => 'rs_social_share_sanitize_checkbox',
	) );
	
	$wp_customize->add_control('social_share_display_after_content', array(
		'settings' 	=> 'social_share_display_after_content',
		'label' 	=> __('Show social share after content?', 'social_share'),
		'section' 	=> 'social_share_display_section',
		'type' 		=> 'checkbox',
	));
}
add_action( 'customize_register', 'rs_social_share_plugin_customizer' );


/**
 * Sanitize checkbox
 */
if ( ! function_exists( 'rs_social_share_sanitize_checkbox' ) ) :
	function rs_social_share_sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}
endif;

?>