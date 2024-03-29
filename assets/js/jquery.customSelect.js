/*!
 * jquery.customSelect() - v0.5.1
 * http://adam.co/lab/jquery/customselect/
 * 2014-03-19
 *
 * Copyright 2013 Adam Coulombe
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @license http://www.gnu.org/licenses/gpl.html GPL2 License 
 */

(function ($) {
	'use strict';
	
	$.fn.extend({
		customSelect: function (options) {
			// filter out <= IE6
			if (typeof document.body.style.maxHeight === 'undefined') {
				return this;
			}
			var defaults = {
					customClass: 'customSelect',
					mapClass   : true,
					mapStyle   : true
				},
				options = $.extend(defaults, options),
				prefix = options.customClass,
				changed = function ($select, customSelectSpan) {
					var currentSelected = $select.find(':selected'),
						customSelectSpanInner = customSelectSpan.children(':first'),
						html = currentSelected.html() || '&nbsp;';
					
					customSelectSpanInner.html(html);
					
					if (currentSelected.attr('disabled')) {
						customSelectSpan.addClass(getClass('DisabledOption'));
					} else {
						customSelectSpan.removeClass(getClass('DisabledOption'));
					}
					
					setTimeout(function () {
						customSelectSpan.removeClass(getClass('Open'));
						$(document).off('mouseup.customSelect');
					}, 60);
				},
				getClass = function (suffix) {
					return prefix + suffix;
				};
			
			return this.each(function () {
				var $select = $(this),
					customSelectInnerSpan = $('<span />').addClass(getClass('Inner')),
					customSelectSpan = $('<span />'),
					is_width_100 = $select.hasClass('j-width-100');
				
				customSelectSpan.addClass('h-invisible');
				customSelectSpan.addClass(prefix);
				if (options.mapClass) {
					customSelectSpan.addClass($select.attr('class'));
				}
				if (options.mapStyle) {
					customSelectSpan.attr('style', $select.attr('style'));
				}
				$select.after(customSelectSpan.append(customSelectInnerSpan));
				
				$select
					.addClass('hasCustomSelect')
					.on('clear.customSelect', function () {
						customSelectSpan.addClass('h-invisible');
						$select.css('width', '');
						$select.css('position', '');
					})
					.on('render.customSelect', function () {
						customSelectSpan.addClass('h-invisible');
						changed($select, customSelectSpan);
						var selectBoxWidth;
						$select.css('width', '');
						$select.css('position', '');
						selectBoxWidth = parseInt($select.outerWidth(), 10);
						
						customSelectSpan.css({
							display: is_width_100 ? 'block' : 'inline-block'
						});
						
						var selectBoxHeight = customSelectSpan.outerHeight();
						
						if ($select.attr('disabled')) {
							customSelectSpan.addClass(getClass('Disabled'));
						} else {
							customSelectSpan.removeClass(getClass('Disabled'));
						}
						
						if (is_width_100) {
							customSelectInnerSpan.css({
								width      : selectBoxWidth,
								display    : 'block'
							});
						} else {
							customSelectInnerSpan.css({
								width  : selectBoxWidth,
								display: 'inline-block'
							});
						}
						
						
						$select.css({
							'-webkit-appearance': 'menulist-button',
							width               : is_width_100 ? '100%' : customSelectSpan.outerWidth(),
							position            : 'absolute',
							opacity             : 0,
							height              : selectBoxHeight,
							fontSize            : customSelectSpan.css('font-size')
						});
						
						customSelectSpan.removeClass('h-invisible');
						
					})
					.on('change.customSelect', function () {
						customSelectSpan.addClass(getClass('Changed'));
						changed($select, customSelectSpan);
					})
					.on('keyup.customSelect', function (e) {
						if (!customSelectSpan.hasClass(getClass('Open'))) {
							$select.trigger('blur.customSelect');
							$select.trigger('focus.customSelect');
						} else {
							if (e.which == 13 || e.which == 27) {
								changed($select, customSelectSpan);
							}
						}
					})
					.on('mousedown.customSelect', function () {
						customSelectSpan.removeClass(getClass('Changed'));
					})
					.on('mouseup.customSelect', function (e) {
						
						if (!customSelectSpan.hasClass(getClass('Open'))) {
							// if FF and there are other selects open, just apply focus
							if ($('.' + getClass('Open')).not(customSelectSpan).length > 0 && typeof InstallTrigger !== 'undefined') {
								$select.trigger('focus.customSelect');
							} else {
								customSelectSpan.addClass(getClass('Open'));
								e.stopPropagation();
								$(document).one('mouseup.customSelect', function (e) {
									if (e.target != $select.get(0) && $.inArray(e.target, $select.find('*').get()) < 0) {
										$select.trigger('blur.customSelect');
									} else {
										changed($select, customSelectSpan);
									}
								});
							}
						}
					})
					.on('focus.customSelect', function () {
						customSelectSpan.removeClass(getClass('Changed')).addClass(getClass('Focus'));
					})
					.on('blur.customSelect', function () {
						customSelectSpan.removeClass(getClass('Focus') + ' ' + getClass('Open'));
					})
					.on('mouseenter.customSelect', function () {
						customSelectSpan.addClass(getClass('Hover'));
					})
					.on('mouseleave.customSelect', function () {
						customSelectSpan.removeClass(getClass('Hover'));
					})
					.trigger('render.customSelect');
			});
		}
	});
})(jQuery);
