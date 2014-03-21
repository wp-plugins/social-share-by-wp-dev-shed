<?php
/*/
Plugin Name: Social Share by WP Dev Shed
Plugin URI: http://wordpress.org/plugins/social-share-by-wp-dev-shed/
Description: Adds Facebook and Twitter social share buttons to your blog posts.
Version: 1.0
Author: WP Dev Shed
Author URI: http://wpdevshed.com/
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
/*/

/**
 * Facebook Open Graph meta to correctly display the share preview
 */
add_action('wp_head', 'sswpds_fb_open_graph_tags');
function sswpds_fb_open_graph_tags() {
	if (is_single()) {
		global $post;
		if(get_the_post_thumbnail($post->ID, 'thumbnail')) {
			$thumbnail_id = get_post_thumbnail_id($post->ID);
			$thumbnail_object = get_post($thumbnail_id);
			$image = $thumbnail_object->guid;
		} else {	
			$image = '';
		}
		$description = sswpds_excerpt( $post->post_content, $post->post_excerpt );
		$description = strip_tags($description);
		$description = str_replace("\"", "'", $description);
?>
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
<meta property="og:title" content="<?php echo get_the_title(); ?>" />
<meta property="og:image" content="<?php echo $image; ?>" />
<meta property="og:description" content="<?php echo $description ?>" />
<?php 	}
}

/**
 * Custom Excerpt for the og:description
 */
function sswpds_excerpt($text, $excerpt){
	
    if ($excerpt) return $excerpt;

    $text = strip_shortcodes( $text );
	if(!isset($raw_excerpt)) {
		$raw_excerpt = '';
	}
    $text = apply_filters($post->post_content, $text);
    $text = str_replace(']]>', ']]&gt;', $text);
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
	$new_content .= '<a href="' . esc_url('http://www.facebook.com/share.php?u=') . get_permalink() . '" class="sswpds-social-fb" target="_blank">' . __('Share on Facebook', 'sswpds') . '</a>' . "\n";
	$new_content .= '<a href="' . esc_url('http://twitter.com/home?status=') . get_the_title() . esc_attr('%0D%0A') . get_permalink() . '" class="sswpds-social-tw" target="_blank">' . __('Share on Twitter', 'sswpds') . '</a>' . "\n";
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