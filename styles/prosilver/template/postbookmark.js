/* global phpbb */
;var postbookmark = {};
(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	function loadBookmarksPage(url, title) {
		phpbb.loadingIndicator();
		$.ajax({
			url: url,
			error: function(e, text, ee) {
				/* No handling currently =( */
			},
			success: function(res, x) {
				postbookmark.modal = phpbb.alert(title, res);
				$(document).off('keydown.phpbb.alert').on('keydown.phpbb.alert', function(e) {
					if (e.keyCode === 27) {
						phpbb.alert.close(postbookmark.modal, true);
						e.preventDefault();
						e.stopPropagation();
					}
				});
				handleDrops(postbookmark.modal);
				handlePageJump(postbookmark.modal, title);
				$(postbookmark.modal).find("[data-page-url]").click(function(event) {
					event.preventDefault();
					loadBookmarksPage($(this).attr("data-page-url"), title);
				});
			},
			cache: false
		});
	}

	function loadBookmarkAction(url, resultFunction) {
		phpbb.loadingIndicator();
		$.ajax({
			url: url,
			method: 'POST',
			error: function(e, text, ee) {
				/* No handling currently =( */
			},
			success: function(res, x) {
				if (res.title && res.message) {
					phpbb.alert(res.title, res.message);
				}
				if (typeof resultFunction === "function") {
					resultFunction();
				}
			},
			cache: false
		});
	}

	$(".postbookmark_trigger").click(function() {
		var $this = $(this);
		loadBookmarksPage($this.attr("data-url"), $this.val());
	});

	postbookmark.closeModal = function() {
		phpbb.alert.close(postbookmark.modal, true);
	};

	$(".postbookmark-icon, .postbookmark-icon-delete").click(function() {
		var $this = $(this);
		if ($this.hasClass("postbookmark-icon-delete")) {
			$this.each(phpbb.toggleDropdown);
			loadBookmarkAction($this.attr("data-bookmark-url") + '&mode=delete', function() {
				var bookmarkText = $this.attr("data-bookmark-text");
				$this.toggleClass("postbookmark-icon postbookmark-icon-delete").attr("title", bookmarkText);
				$this.children("span").html(bookmarkText);
			});
		}
	});

	$("#bookmark_form").submit(function(e) {
		var $this = $(this), $trigger = $this.closest(".dropdown-container").find(".dropdown-trigger");
		e.preventDefault();
		$trigger.each(phpbb.toggleDropdown);
		loadBookmarkAction($trigger.attr("data-bookmark-url") + '&mode=insert&book=1&' + $("#bookmark_desc").serialize(), function() {
			var bookmarkText = $trigger.attr("data-bookmark-remove-text");
			$trigger.toggleClass("postbookmark-icon postbookmark-icon-delete").attr("title", bookmarkText);
			$trigger.children("span").html(bookmarkText);
		});
	});

	function handlePageJump(container, title) {
		container.find('.pagination .page-jump-form :button').click(function() {
			var $input = $(this).siblings('input.inputbox');
			modalPageJump($input, title);
		});

		container.find('.pagination .page-jump-form input.inputbox').on('keypress', function(event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				event.stopPropagation();
				modalPageJump($(this), title);
			}
		});

		container.find('.pagination .dropdown-trigger').click(function() {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function() {
				if ($dropdownContainer.hasClass('dropdown-visible')) {
					$dropdownContainer.find('input.inputbox').focus();
				}
			}, 100);
		});

	}

	/**
	 * pageJump function for PostBookmark.
	 *
	 * @param {jQuery} item
	 * @param {string} title
	 */
	function modalPageJump(item, title) {
		var page = parseInt(item.val());

		if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
			var perPage = item.attr('data-per-page'),
				baseUrl = item.attr('data-base-url'),
				startName = item.attr('data-start-name');

			if (baseUrl.indexOf('?') === -1) {
				loadBookmarksPage(baseUrl + '?' + startName + '=' + ((page - 1) * perPage), title);
			} else {
				loadBookmarksPage(baseUrl.replace(/&amp;/g, '&') + '&' + startName + '=' + ((page - 1) * perPage), title);
			}
		}
	}

	/**
	 * Handles dropdowns for the specified container.
	 *
	 * @param {jQuery} container
	 */
	function handleDrops(container) {
		/**
		 * Dropdowns
		 */
		container.find('.dropdown-container').each(function() {
			var $this = $(this),
				trigger = $this.find('.dropdown-trigger:first'),
				contents = $this.find('.dropdown'),
				options = {
					direction: 'auto',
					verticalDirection: 'auto'
				},
				data;

			if (!trigger.length) {
				data = $this.attr('data-dropdown-trigger');
				trigger = data ? $this.children(data) : $this.children('a:first');
			}

			if (!contents.length) {
				data = $this.attr('data-dropdown-contents');
				contents = data ? $this.children(data) : $this.children('div:first');
			}

			if (!trigger.length || !contents.length) {
				return;
			}

			if ($this.hasClass('dropdown-up')) {
				options.verticalDirection = 'up';
			}
			if ($this.hasClass('dropdown-down')) {
				options.verticalDirection = 'down';
			}
			if ($this.hasClass('dropdown-left')) {
				options.direction = 'left';
			}
			if ($this.hasClass('dropdown-right')) {
				options.direction = 'right';
			}

			phpbb.registerDropdown(trigger, contents, options);
		});
	}
})(jQuery, window, document);
