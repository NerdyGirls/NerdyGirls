// For mobile: http://stackoverflow.com/questions/18753367/jquery-live-scroll-event-on-mobile-work-around

// Adapted from https://gist.github.com/paulirish/1579671 which derived from
// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating

// requestAnimationFrame polyfill by Erik Möller.
// Fixes from Paul Irish, Tino Zijdel, Andrew Mao, Klemen Slavič, Darius Bacon

// MIT license
/*
if ( ! Date.now ) {
    Date.now = function() {
        return new Date().getTime();
    };
}

(function() {
    'use strict';

    var vendors = ['webkit', 'moz'];
    for (var i = 0; i < vendors.length && !window.requestAnimationFrame; ++i) {
        var vp = vendors[i];
        window.requestAnimationFrame = window[vp+'RequestAnimationFrame'];
        window.cancelAnimationFrame = (window[vp+'CancelAnimationFrame']
                                   || window[vp+'CancelRequestAnimationFrame']);
    }
    if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) // iOS6 is buggy
        || !window.requestAnimationFrame || !window.cancelAnimationFrame) {
        var lastTime = 0;
        window.requestAnimationFrame = function(callback) {
            var now = Date.now();
            var nextTime = Math.max(lastTime + 16, now);
            return setTimeout(function() { callback(lastTime = nextTime); },
                              nextTime - now);
        };
        window.cancelAnimationFrame = clearTimeout;
    }
}());
*/

/*
 * Project: Scrolly2 - Background Image Parallax
 * Originally based on Scrolly by Victor C. / Octave & Octave web agency
 * Rewritten and heavily adjusted by Benjamin Intal / Gambit
 */
(function ( $, window, document, undefined ) {
    var pluginName = 'scrolly2';

    function Plugin( element, options ) {
        this.$element = $(element);
        this.init();
    }

    Plugin.prototype.init = function () {
        var self = this;
        this.startPosition = 0;
        this.offsetTop = this.$element.offset().top;
        this.height = this.$element.outerHeight(true);
        this.velocity = this.$element.attr('data-velocity');
        this.direction = this.$element.attr('data-direction');

        // Bind so that we don't refresh everytime
        $(window).bind('scroll', function() {
            // window.requestAnimationFrame( function(){
                self.scrolly2();
            // });
        });
    };

    Plugin.prototype.scrolly2 = function() {
        // Check if the element is inside our viewport, if it's not, don't do anything
        var viewTop = $(window).scrollTop() - 20; // with leeway
        var viewBottom = $(window).scrollTop() + $(window).height() + 20; // with leeway
        var elemTop = this.$element.offset().top;
        var elemBottom = this.$element.offset().top + this.$element.height();

        if ( elemTop >= viewBottom || elemBottom <= viewTop ) {
            return;
        }

        // If the element is below the fold, then we need to
        // make sure that when we first see the element,
        // our background image should be in the starting position
        if ( this.$element.offset().top > $(window).height() ) {
            if ( this.direction !== 'none' ) {
                this.startPosition = (this.$element.offset().top - $(window).height()) * Math.abs(this.velocity);
            }
        }

        // Calculate position
        var position = this.startPosition + $(window).scrollTop() * this.velocity;

        // Adjust position
        var xPos = "50%";
        var yPos = "50%";
        if ( this.direction === 'left' ) {
            xPos = position + 'px';
        } else if ( this.direction === 'right' ) {
            xPos = 'calc(100% + ' + -position + 'px)';
        } else if ( this.direction === 'down' ) {
            // yPos = 'calc(100% + ' + (-position) + 'px)';
            // Use this one for background-attachment: fixed
            var offset = - ( $(window).height() -
                         this.$element.offset().top -
                         this.$element.height() -
                         parseInt( this.$element.css('paddingTop') ) -
                         parseInt( this.$element.css('paddingBottom') ) );
            yPos = 'calc(100% + ' + ( offset - $(window).scrollTop() - position ) + 'px)';
        } else { // up
            // yPos = position + 'px';
            // Use this one for background-attachment: fixed
            yPos = ( this.$element.offset().top - $(window).scrollTop() + position ) + 'px';
        }
        this.$element.css( { backgroundPosition: xPos + ' ' + yPos } );
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
            }
        });
    };

})(jQuery, window, document);