jQuery(function($){
	$(window).load(function() {
		var $container = $('#blog-entries');
		$container.infinitescroll({
			loading: {
				msg: null,
				finishedMsg : null,
				msgText : null,
				msgText: '<div class="infinite-scroll-loader">'+ wpexInfiniteScroll.msgText +'</div>',
			},
			navSelector  : 'div.infinite-scroll-nav',
			nextSelector : 'div.infinite-scroll-nav div.older-posts a',
			itemSelector : '.blog-entry',
		}, function( newElements ) {
			var $newElems = $( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function() {
				$newElems.animate({ opacity: 1 });
				$container.isotope( 'appended', $newElems );
				$('.gallery-format-post-slider').flexslider({
					animation: 'fade',
					animationSpeed: 500,
					slideshow: true,
					smoothHeight: false,
					controlNav: false,
					directionNav: true,
					controlNav : "thumbnails",
					prevText : '<span class="fa fa-chevron-left"></span>',
					nextText : '<span class="fa fa-chevron-right"></span>'
				});
				$('.wpex-gallery-lightbox li a').click( function() {
					return false;
				});
			});
		});
	});
});