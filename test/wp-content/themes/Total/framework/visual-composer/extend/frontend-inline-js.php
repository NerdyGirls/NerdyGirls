<?php
/**
 * Outputs inline JS for the front-end JS composer
 *
 * @package		Total
 * @subpackage	Visual Composer
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.5.0
 */

/**
 * Outputs front end JS for masonry/isotope
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_grid_js' ) ) {
	function vcex_front_end_grid_js( $style = '' ) {
		// Only output on front-end composer
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
			jQuery(function($){
				<?php if ( 'isotope' == $style ) { ?>
					function wpexIsotopeGrid(){$(".vcex-isotope-grid").each(function(){var e=$(this);e.imagesLoaded(function(){e.isotope({itemSelector:".vcex-isotope-entry"})});var t=e.prev("ul.vcex-filter-links");var n=t.find("a");n.each(function(){var t=$(this).data("filter");if(t!=="*"&&!e.find(t).length){$(this).parent().hide("100")}});n.css({opacity:1});n.click(function(){var t=$(this).attr("data-filter");e.isotope({filter:t});$(this).parents("ul").find("li").removeClass("active");$(this).parent("li").addClass("active");return false})})}if($.fn.isotope!=undefined){wpexIsotopeGrid();var isIE8=$.browser.msie&&+$.browser.version===8;if(isIE8){document.body.onresize=function(){wpexIsotopeGrid()}}else{$(window).resize(function(){wpexIsotopeGrid()});window.addEventListener("orientationchange",function(){wpexIsotopeGrid()})}}
				<?php } ?>
			});
		</script>
	<?php }
}

/**
 * Outputs front end JS for image sliders
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_slider_js' ) ) {
	function vcex_front_end_slider_js() {
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
			jQuery(function($){
				if($.fn.imagesLoaded!=undefined&&$.fn.flexslider!=undefined){$(".vcex-flexslider, .vcex-galleryslider").each(function(){var e=$(this);e.imagesLoaded(function(){var t=e.data("animation"),n=e.data("randomize"),r=e.data("direction"),i=e.data("slideshow-speed"),s=e.data("animation-speed"),o=e.data("direction-nav"),u=e.data("pause"),a=e.data("smooth-height"),f=e.data("control-nav");e.flexslider({slideshow:false,animation:t,randomize:n,direction:r,slideshowSpeed:i,animationSpeed:s,directionNav:o,pauseOnHover:u,smoothHeight:a,controlNav:f,prevText:'<i class=fa fa-chevron-left"></i>',nextText:'<i class="fa fa-chevron-right"></i>'})})})}
			});
		</script>
	<?php
	}
}

/**
 * Outputs front end JS for carousel
 *
 * @since Total 1.5.0
 */
if ( ! function_exists( 'vcex_front_end_carousel_js' ) ) {
	function vcex_front_end_carousel_js() {
		// Only output on front-end
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
		jQuery(function($){
			if($.fn.owlCarousel!=undefined){$(".wpex-carousel").each(function(){var e=$(this);e.owlCarousel({dots:false,items:e.data("items"),slideBy:e.data("slideby"),center:e.data("center"),loop:e.data("loop"),margin:e.data("margin"),nav:e.data("nav"),autoplay:e.data("autoplay"),autoplayTimeout:e.data("autoplay-timeout"),navText:['<span class="fa fa-chevron-left"><span>','<span class="fa fa-chevron-right"></span>'],responsive:{0:{items:e.data("items-mobile-portrait")},480:{items:e.data("items-mobile-landscape")},768:{items:e.data("items-tablet")},960:{items:e.data("items")}}})})}
		});
		</script>
	<?php
	}
}

/**
 * Data hovers front-end editor js
 *
 * @since Total 1.6.2
 */
if ( ! function_exists( 'vcex_data_hover_js' ) ) {
	function vcex_data_hover_js() {
		// Only output on front-end
		if ( ! wpex_is_front_end_composer() ) {
			return;
		} ?>
		<script type="text/javascript">
		jQuery(function($){
			$(".wpex-data-hover").each(function(){var e=$(this),t=$(this).css("backgroundColor"),n=$(this).css("color"),r=$(this).attr("data-hover-background"),i=$(this).attr("data-hover-color");e.hover(function(){if(r){e.css("background-color",r)}if(i){e.css("color",i)}},function(){if(r&&t){e.css("background-color",t)}if(i&&n){e.css("color",n)}})})
		});
		</script>
	<?php
	}
}