/**
 * Admin JavaScript for Hariharan theme
 */
(function ($) {
	"use strict";

	$(document).ready(function () {
		// Initialize color pickers
		$(".hariharan-color-picker").wpColorPicker();

		// Tab navigation
		$(".hariharan-options-nav a").on("click", function (e) {
			e.preventDefault();

			var target = $(this).attr("href");

			// Update active tab
			$(".hariharan-options-nav a").removeClass("active");
			$(this).addClass("active");

			// Show target tab
			$(".hariharan-options-tab").removeClass("active");
			$(target).addClass("active");
		});

		// Media uploader for images
		$(".hariharan-upload-image").on("click", function (e) {
			e.preventDefault();

			var button = $(this);
			var field = button.siblings('input[type="hidden"]');
			var preview = button.siblings(".hariharan-image-preview");

			var frame = wp.media({
				title: "Select or Upload Image",
				button: {
					text: "Use this image",
				},
				multiple: false,
			});

			frame.on("select", function () {
				var attachment = frame.state().get("selection").first().toJSON();
				field.val(attachment.url);

				// Update preview
				preview.html('<img src="' + attachment.url + '" alt="">');
			});

			frame.open();
		});

		// Remove image
		$(".hariharan-remove-image").on("click", function (e) {
			e.preventDefault();

			var button = $(this);
			var field = button.siblings('input[type="hidden"]');
			var preview = button.siblings(".hariharan-image-preview");

			field.val("");
			preview.empty();
		});
	});
})(jQuery);
