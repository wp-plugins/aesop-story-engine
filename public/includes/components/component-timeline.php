<?php

/**
 	* Creates an interactive timeline component that works similar to chapter headings
 	*
 	* @since    1.0.0
*/
if (!function_exists('aesop_timeline_stop_shortcode')){

	function aesop_timeline_stop_shortcode($atts, $content = null) {

		$defaults = array(
			'num' => '2007',
			'title'	=> ''
		);
		$atts = apply_filters('aesop_timeline_defaults',shortcode_atts($defaults, $atts));

		$datatitle = $atts['title'] ? sprintf('data-title="%s"', $atts['title']) : null;
		// actions
		$actiontop = do_action('aesop_timeline_before'); //action
		$actionbottom = do_action('aesop_timeline_after'); //action

		$out = sprintf('%s<h2 class="aesop-timeline-stop" %s>%s</h2>',$actiontop, $datatitle, $atts['num']);

		return apply_filters('aesop_timeline_output',$out);
	}
}

if (!function_exists('aesop_timeline_class_loader')){

	add_action('wp','aesop_timeline_class_loader',11); // has to run after components are loaded
	function aesop_timeline_class_loader() {

		global $post;

		if( isset($post) && is_single() && has_shortcode( $post->post_content, 'aesop_timeline_stop') )  {

			new AesopTimelineComponent;

		}
	}

}

class AesopTimelineComponent {

	function __construct(){
		add_action('wp_footer', array($this,'aesop_timeline_loader'),21);
		add_action('aesop_inside_body_top', array($this,'draw_timeline'));
		add_filter('body_class',		array($this,'body_class'));
	}

	function aesop_timeline_loader(){

		?>
			<!-- Aesop Timeline -->
			<script>
			jQuery(document).ready(function(){

				jQuery('.aesop-entry-content,.entry-content').scrollNav({
				    sections: '.aesop-timeline-stop',
				    arrowKeys: true,
				    insertTarget: '.aesop-timeline',
				    insertLocation: 'appendTo',
				    showTopLink: false,
				    showHeadline: false,
				    scrollOffset: 80,
				});

				jQuery('.aesop-timeline-stop').each(function(){
					var label = jQuery(this).attr('data-title');
					jQuery(this).text(label);
				});

			});

			</script>

		<?php 
	}

	function draw_timeline(){

		?><div class="aesop-timeline"></div><?php

	}

	function body_class($classes) {

	    $classes[] = 'has-timeline';

	    return $classes;

	}
}





