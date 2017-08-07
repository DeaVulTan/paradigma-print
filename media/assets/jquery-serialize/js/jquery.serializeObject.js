/*
serializeObject jQuery plugin.
https://github.com/hongymagic/jQuery.serializeObject
version 2.0.3

The MIT License (MIT)

Copyright (c) 2013 David Hong

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 */
//
// Use internal $.serializeArray to get list of form elements which is
// consistent with $.serialize
//
// From version 2.0.0, $.serializeObject will stop converting [name] values
// to camelCase format. This is *consistent* with other serialize methods:
//
// - $.serialize
// - $.serializeArray
//
// If you require camel casing, you can either download version 1.0.4 or map
// them yourself.
//
(function($) {
	$.fn.serializeObject = function() {
		"use strict";

		var result = {};
		var extend = function(i, element) {
			var node = result[element.name.replace("[]" ,"")]; // Edited by vudoanthang83@gmail.com

			if ('undefined' !== typeof node && node !== null) {
				if ($.isArray(node)) {
					node.push(element.value);
				} else {
					result[element.name.replace("[]" ,"")] = [ node, element.value ]; // Edited by vudoanthang83@gmail.com
				}
			} else {
				if (element.name.indexOf("[]") > 0 ){  // Edited by vudoanthang83@gmail.com
					result[element.name.replace("[]" ,"")] = [element.value]; // Edited by vudoanthang83@gmail.com
				}else{
					result[element.name] = element.value;
				}
			}
		};

		$.each(this.serializeArray(), extend);
		return result;
	};
})(jQuery);