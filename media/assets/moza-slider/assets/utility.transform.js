/**
 * A part of Flux Slider v1.4.4
 * http://www.joelambert.co.uk/flux
 *
 * Copyright 2011, Joe Lambert
 * 			 2014,  Vu Doan Thang 	
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
var utility = {};
(function($) {
	$.fn.css3 = function(props) {
		var css = {};
		var prefixes = ['webkit', 'moz', 'ms', 'o'];

		for(var prop in props)
		{
			// Add the vendor specific versions
			for(var i=0; i<prefixes.length; i++)
				css['-'+prefixes[i]+'-'+prop] = props[prop];
			
			// Add the actual version	
			css[prop] = props[prop];
		}
		
		this.css(css);
		return this;
	};
	
	$.fn.transitionEnd = function(callback) {
		var _this = this;
		
		var events = ['webkitTransitionEnd', 'transitionend', 'oTransitionEnd'];
		
		for(var i=0; i < events.length; i++)
		{
			this.bind(events[i], function(event){
				// Automatically stop listening for the event
				for(var j=0; j<events.length;j++)
					$(this).unbind(events[j]);

				// Perform the callback function
				if(callback)
					callback.call(this, event);
			});
		}
		
		return this;
	};
	
	utility.transform = {
		init: function() {
			// Have we already been initialised?
			if(utility.transform.supportsTransitions !== undefined)
				return;

			var div = document.createElement('div'),
				prefixes = ['-webkit', '-moz', '-o', '-ms'],
				domPrefixes = ['Webkit', 'Moz', 'O', 'Ms'];

			// Does the current browser support CSS Transitions?
			if(window.Modernizr && Modernizr.csstransitions !== undefined)
				utility.transform.supportsTransitions = Modernizr.csstransitions;
			else
			{
				utility.transform.supportsTransitions = this.supportsCSSProperty('Transition');
			}

			// Does the current browser support 3D CSS Transforms?
			if(window.Modernizr && Modernizr.csstransforms3d !== undefined)
				utility.transform.supports3d = Modernizr.csstransforms3d;
			else
			{
				// Custom detection when Modernizr isn't available
				utility.transform.supports3d = this.supportsCSSProperty("Perspective");
				
				if ( utility.transform.supports3d && 'webkitPerspective' in $('body').get(0).style ) {
					// Double check with a media query (similar to how Modernizr does this)
					var div3D = $('<div id="csstransform3d"></div>');
					var mq = $('<style media="(transform-3d), ('+prefixes.join('-transform-3d),(')+'-transform-3d)">div#csstransform3d { position: absolute; left: 9px }</style>');

					$('body').append(div3D);
					$('head').append(mq);

					utility.transform.supports3d = div3D.get(0).offsetLeft == 9;

					div3D.remove();
					mq.remove();	
				}
			}

		},
		supportsCSSProperty: function(prop) {
			var div = document.createElement('div'),
				prefixes = ['-webkit', '-moz', '-o', '-ms'],
				domPrefixes = ['Webkit', 'Moz', 'O', 'Ms'];
				
			var support = false;
			for(var i=0; i<domPrefixes.length; i++)
			{
				if(domPrefixes[i]+prop in div.style)
					support = support || true;
			}
			
			return support;
		},
		translate: function(x, y, z) {
			x = (x != undefined) ? x : 0;
			y = (y != undefined) ? y : 0;
			z = (z != undefined) ? z : 0;

			return 'translate' + (utility.transform.supports3d ? '3d(' : '(') + x + 'px,' + y + (utility.transform.supports3d ? 'px,' + z + 'px)' : 'px)');
		},

		rotateX: function(deg) {
			return utility.transform.rotate('x', deg);
		},

		rotateY: function(deg) {
			return utility.transform.rotate('y', deg);
		},

		rotateZ: function(deg) {
			return utility.transform.rotate('z', deg);
		},

		rotate: function(axis, deg) {
			if(!axis in {'x':'', 'y':'', 'z':''})
				axis = 'z';

			deg = (deg != undefined) ? deg : 0;

			if(utility.transform.supports3d)
				return 'rotate3d('+(axis == 'x' ? '1' : '0')+', '+(axis == 'y' ? '1' : '0')+', '+(axis == 'z' ? '1' : '0')+', '+deg+'deg)';
			else
			{
				if(axis == 'z')
					return 'rotate('+deg+'deg)';
				else
					return '';
			}
		}
	};
	
	utility.getBrowser = function(){
		var _matched, Browser;

		var uaMatch = function() {
			ua = navigator.userAgent.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
				/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
				/(msie) ([\w.]+)/.exec( ua ) ||
				/(trident)/.exec( ua ) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
				[];
			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};
		_matched = uaMatch(  );
		Browser = {};
		if (_matched.browser == 'trident'){
			_matched.browser = 'msie';
		}
		if ( _matched.browser ) {
			Browser[ _matched.browser ] = true;
			Browser.version = _matched.version;
		}

		if ( Browser.webkit ) {
			Browser.safari = true;
		}
		return Browser;
	}

	$(function(){
		// To continue to work with legacy code, ensure that utility.transform is initialised on document ready at the latest
		utility.transform.init();
	});
})(jQuery);