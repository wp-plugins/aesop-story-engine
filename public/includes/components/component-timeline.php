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

		// let this be used multiple times
		static $instance = 0;
		$instance++;
		$unique = sprintf('%s-%s',get_the_ID(), $instance);

		$datatitle = $atts['title'] ? sprintf('data-title="%s"', esc_attr($atts['title'])) : null;
		// actions
		$actiontop = do_action('aesop_timeline_before'); //action
		$actionbottom = do_action('aesop_timeline_after'); //action

		$out = sprintf('%s<h2 class="aesop-timeline-stop aesop-component" %s %s>%s</h2>%s',$actiontop, $datatitle, aesop_component_data_atts( 'timeline', $unique, $atts ), esc_html($atts['num']), $actionbottom );

		return apply_filters('aesop_timeline_output', $out);
	}
}

if (!function_exists('aesop_timeline_class_loader')){

	add_action('wp','aesop_timeline_class_loader',11); // has to run after components are loaded
	function aesop_timeline_class_loader() {

		global $post;

		$default_location 	= is_single();
		$location 			= apply_filters( 'aesop_timeline_component_appears', $default_location );

		if ( function_exists('aesop_component_exists') && aesop_component_exists('timeline_stop') && ( $location ) )  {

			new AesopTimelineComponent;

		}
	}

}

class AesopTimelineComponent {

	function __construct(){

		// call our method in the footer
		add_action('wp_footer', array($this,'aesop_timeline_loader'),21);

		// add a body class if timeline is active
		add_filter('body_class',		array($this,'body_class'));

	}

	function aesop_timeline_loader(){

		// allow theme developers to determine the offset amount
		$timelineOffset = apply_filters('aesop_timeline_scroll_offset', 0 );

		// filterable content class
		$contentClass = apply_filters('aesop_timeline_scroll_container', '.aesop-entry-content');

		// filterable target class
		$appendTo    = apply_filters('aesop_timeline_scroll_nav', '.aesop-timeline');

		?>
			<!-- Aesop Timeline -->
			<script>
			jQuery(document).ready(function(){

				jQuery('body').append('<div class="aesop-timeline"></div>');

				jQuery('<?php echo esc_attr($contentClass);?>').scrollNav({
				    sections: '.aesop-timeline-stop',
				    arrowKeys: true,
				    insertTarget: '<?php echo esc_attr($appendTo);?>',
				    insertLocation: 'appendTo',
				    showTopLink: false,
				    showHeadline: false,
				    scrollOffset: <?php echo (int) $timelineOffset;?>,
				});

				jQuery('.aesop-timeline-stop').each(function(){
					var label = jQuery(this).attr('data-title');
					jQuery(this).text(label);
				});

			});

			</script>

		<?php 
	}


	function body_class($classes) {

	    $classes[] = 'has-timeline';

	    return $classes;

	}
}





