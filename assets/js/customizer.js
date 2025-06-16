/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 */
(function ($) {
	"use strict";

	// Site title and description.
	wp.customize("blogname", function (value) {
		value.bind(function (to) {
			$(".site-title a").text(to);
		});
	});

	wp.customize("blogdescription", function (value) {
		value.bind(function (to) {
			$(".site-description").text(to);
		});
	});

	// Header text color.
	wp.customize("header_textcolor", function (value) {
		value.bind(function (to) {
			if ("blank" === to) {
				$(".site-title, .site-description").css({
					clip: "rect(1px, 1px, 1px, 1px)",
					position: "absolute",
				});
			} else {
				$(".site-title, .site-description").css({
					clip: "auto",
					position: "relative",
				});
				$(".site-title a").css({
					color: to,
				});
			}
		});
	});

	// Primary color.
	wp.customize("bloglypress_primary_color", function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty(
				"--bloglypress-primary-color",
				to
			);
		});
	});

	// Secondary color.
	wp.customize("bloglypress_secondary_color", function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty(
				"--bloglypress-secondary-color",
				to
			);
		});
	});

	// Text color.
	wp.customize("bloglypress_text_color", function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty(
				"--bloglypress-text-color",
				to
			);
		});
	});

	// Link color.
	wp.customize("bloglypress_link_color", function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty(
				"--bloglypress-link-color",
				to
			);
		});
	});

	// Copyright text.
	wp.customize("bloglypress_copyright_text", function (value) {
		value.bind(function (to) {
			$(".copyright").html(to);
		});
	});

	// Custom CSS.
	wp.customize("bloglypress_custom_css", function (value) {
		value.bind(function (to) {
			$("#bloglypress-custom-css").html(to);
		});
	});

	// Custom JavaScript.
	wp.customize("bloglypress_custom_js", function (value) {
		value.bind(function (to) {
			$("#bloglypress-custom-js").html(to);
		});
	});
})(jQuery);
