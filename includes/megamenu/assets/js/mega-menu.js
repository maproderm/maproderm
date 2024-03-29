(function ($, root, undefined) {
	$(function () {
		"use strict";
		
		var ideapark_check_megamenu = function () {
			$('.ip-field-custom--active').removeClass('ip-field-custom--active');
			$('.menu-item-depth-0 .ip-field-custom--depth-0').addClass('ip-field-custom--active');
			if ($("#locations-primary:checked").length) {
				$('.ip-field-custom__option-html-block').prop('disabled', false);
				$('.menu-item-depth-0 .ip-field-custom--primary-0').addClass('ip-field-custom--active');
			} else {
				$('.ip-field-custom__option-html-block').prop('disabled', true);
			}
		}
		
		ideapark_check_megamenu();
		$('.menu-settings [name^="menu-locations"]').click(ideapark_check_megamenu);
		
		var ideapark_select_content = function () {
			$('.menu-item-depth-0 .ip-field-custom__content').each(
				function () {
					var $this = $(this);
					var content_type = $this.val();
					var $menu_item = $this.closest('.menu-item');
					$('.ip-field-custom--html_block,.ip-field-custom--product_category,.ip-field-custom--product_attr', $menu_item).removeClass('active');
					$('.ip-field-custom--' + content_type, $menu_item).addClass('active');
				}
			)
		}
		
		ideapark_select_content();
		
		$(document)
			.on('sortstop menu-item-added', function () {
				setTimeout(function () {
					$('.menu-item:not(.menu-item-depth-0) .ip-field-custom--active').removeClass('ip-field-custom--active');
					$('.menu-item-depth-0 .ip-field-custom--depth-0').addClass('ip-field-custom--active');
					if ($("#locations-primary:checked").length) {
						$('.ip-field-custom__option-html-block').prop('disabled', false);
						$('.menu-item-depth-0 .ip-field-custom--primary-0').addClass('ip-field-custom--active');
					} else {
						$('.ip-field-custom__option-html-block').prop('disabled', true);
					}
				}, 100);
			})
			.on('change', '.ip-field-custom__content', ideapark_select_content)
			.on('click', '.ip-field-custom__loader', function (e) {
				e.preventDefault();
				var $this = $(this);
				var $parent = $this.closest('.ip-field-custom');
				var $spinner = $parent.find('.spinner');
				
				if ($parent.hasClass('loaded')) {
					return false;
				}
				
				if ($spinner.hasClass('is-active')) {
					return false;
				}
				
				$spinner.addClass('is-active');
				
				var $value = $parent.find('.ip-field-custom__val');
				var value = $value.val();
				var type = $parent.data('type');
				var item_id = $parent.data('item-id');
				var $container = $parent.find('.ip-field-custom__container');
				var $selected = $parent.find('.ip-field-custom__loader');
				
				$.ajax({
					url    : ajaxurl,
					type   : 'POST',
					data   : {
						action : 'ideapark_load_mega_menu',
						value  : value,
						type   : type,
						item_id: item_id,
					},
					success: function (html) {
						if (type == 'icon') {
							$container.html(html);
							$container.find('.ip-field-custom__icon-select')
								.select2({
									placeholder   : ideapark_wp_vars_mega_menu.placeholderIcon,
									allowClear    : true,
									templateResult: function (state) {
										if (!state.id) {
											return state.text;
										}
										var $state = $(
											'<span><span class="ip-field-custom__option-ico ' + state.id + '"></span> ' + state.text + '</span>'
										);
										return $state;
									}
								})
								.on('select2:select select2:clear', function (e) {
									var data = e.params.data;
									$selected.html(data.id ? '<span class="ip-field-custom__icon ' + data.id + '"></span>' : '');
									$value.val(data.id);
								});
						} else {
							$selected.replaceWith(html);
						}
						$parent.addClass('loaded');
					}
				}).always(function () {
					$spinner.removeClass('is-active');
				});
				
				return false;
			});
	});
	
})(jQuery, this);
