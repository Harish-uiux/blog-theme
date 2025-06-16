/**
 * BloglyPress Admin JavaScript
 */
(function ($) {
	"use strict";

	// Initialize when document is ready
	$(document).ready(function () {
		// Initialize color pickers
		initColorPickers();

		// Initialize image choices
		initImageChoices();

		// Initialize color schemes
		initColorSchemes();

		// Initialize typography preview
		initTypographyPreview();

		// Initialize save functionality
		initSaveOptions();

		// Initialize reset section functionality
		initResetSection();

		// Initialize reset all functionality
		initResetOptions();

		// Initialize export functionality
		initExportOptions();

		// Initialize import functionality
		initImportOptions();

		// Initialize file upload
		initFileUpload();
	});

	/**
	 * Initialize color pickers
	 */
	function initColorPickers() {
		$(".bloglypress-admin-color-picker").wpColorPicker({
			change: function (event, ui) {
				// Update preview in real-time for typography
				if (
					$(this).closest("#bloglypress-options-form").data("section") ===
					"typography"
				) {
					updateTypographyPreview();
				}
			},
		});
	}

	/**
	 * Initialize image choices
	 */
	function initImageChoices() {
		$(".bloglypress-admin-image-choice input").on("change", function () {
			$(this)
				.closest(".bloglypress-admin-image-choices")
				.find(".bloglypress-admin-image-choice")
				.removeClass("active");

			$(this).closest(".bloglypress-admin-image-choice").addClass("active");
		});
	}

	/**
	 * Initialize color schemes
	 */
	function initColorSchemes() {
		$(".bloglypress-admin-color-scheme").on("click", function () {
			var primaryColor = $(this).data("primary");
			var secondaryColor = $(this).data("secondary");

			$("#primary_color").val(primaryColor).trigger("change");
			$("#secondary_color").val(secondaryColor).trigger("change");

			// Also update the color picker
			$("#primary_color").wpColorPicker("color", primaryColor);
			$("#secondary_color").wpColorPicker("color", secondaryColor);
		});
	}

	/**
	 * Initialize typography preview
	 */
	function initTypographyPreview() {
		if ($("#bloglypress-options-form").data("section") === "typography") {
			// Update preview when fonts change
			$("#body_font, #heading_font, #base_font_size, #line_height").on(
				"change",
				function () {
					updateTypographyPreview();
				}
			);

			// Initialize preview
			updateTypographyPreview();
		}
	}

	/**
	 * Update typography preview
	 */
	function updateTypographyPreview() {
		var bodyFont = $("#body_font").val();
		var headingFont = $("#heading_font").val();
		var baseFontSize = $("#base_font_size").val() + "px";
		var lineHeight = $("#line_height").val();

		// Ensure Google Fonts are loaded
		if (bodyFont || headingFont) {
			loadGoogleFonts(bodyFont, headingFont);
		}

		// Update preview styles
		$(".preview-body").css({
			"font-family": "'" + bodyFont.replace(/\+/g, " ") + "', sans-serif",
			"font-size": baseFontSize,
			"line-height": lineHeight,
		});

		$(".preview-heading").css({
			"font-family": "'" + headingFont.replace(/\+/g, " ") + "', serif",
		});
	}

	/**
	 * Load Google Fonts
	 */
	function loadGoogleFonts(bodyFont, headingFont) {
		var fonts = [];

		if (bodyFont) {
			fonts.push(bodyFont);
		}

		if (headingFont && headingFont !== bodyFont) {
			fonts.push(headingFont);
		}

		if (fonts.length > 0) {
			var fontUrl =
				"https://fonts.googleapis.com/css?family=" +
				fonts.join("|") +
				"&display=swap";

			if ($("#google-fonts-preview").length === 0) {
				$("head").append(
					'<link id="google-fonts-preview" rel="stylesheet" href="' +
						fontUrl +
						'">'
				);
			} else {
				$("#google-fonts-preview").attr("href", fontUrl);
			}
		}
	}

	/**
	 * Initialize save options
	 */
	function initSaveOptions() {
		$(".bloglypress-save-options").on("click", function () {
			var $form = $("#bloglypress-options-form");
			var section = $form.data("section");
			var formData = $form.serialize();

			// Add loading state
			var $button = $(this);
			$button
				.prop("disabled", true)
				.html('<span class="mdi mdi-loading mdi-spin"></span> Saving...');

			// Clear any existing notices
			$(".bloglypress-admin-notices").empty();

			// Save options via AJAX
			$.ajax({
				url: bloglyPressAdmin.ajaxUrl,
				type: "POST",
				data: {
					action: "bloglypress_save_options",
					nonce: bloglyPressAdmin.nonce,
					section: section,
					formData: formData,
				},
				success: function (response) {
					if (response.success) {
						showNotice(
							"success",
							response.data.message || bloglyPressAdmin.saveSuccess
						);
					} else {
						showNotice(
							"error",
							response.data.message || bloglyPressAdmin.saveFail
						);
					}
				},
				error: function () {
					showNotice("error", bloglyPressAdmin.saveFail);
				},
				complete: function () {
					// Reset button state
					$button
						.prop("disabled", false)
						.html('<span class="mdi mdi-content-save"></span> Save Changes');
				},
			});
		});
	}

	/**
	 * Initialize reset section
	 */
	function initResetSection() {
		$(".bloglypress-reset-section").on("click", function () {
			if (
				confirm(
					"Are you sure you want to reset this section to default settings? This action cannot be undone."
				)
			) {
				var $form = $("#bloglypress-options-form");
				var section = $form.data("section");

				// Add loading state
				var $button = $(this);
				$button
					.prop("disabled", true)
					.html('<span class="mdi mdi-loading mdi-spin"></span> Resetting...');

				// Clear any existing notices
				$(".bloglypress-admin-notices").empty();

				// Reset options via AJAX
				$.ajax({
					url: bloglyPressAdmin.ajaxUrl,
					type: "POST",
					data: {
						action: "bloglypress_reset_options",
						nonce: bloglyPressAdmin.nonce,
						section: section,
					},
					success: function (response) {
						if (response.success) {
							showNotice(
								"success",
								response.data.message || bloglyPressAdmin.resetSuccess
							);
							// Reload page to show default values
							setTimeout(function () {
								window.location.reload();
							}, 1500);
						} else {
							showNotice(
								"error",
								response.data.message || bloglyPressAdmin.resetFail
							);
							// Reset button state
							$button
								.prop("disabled", false)
								.html('<span class="mdi mdi-refresh"></span> Reset Section');
						}
					},
					error: function () {
						showNotice("error", bloglyPressAdmin.resetFail);
						// Reset button state
						$button
							.prop("disabled", false)
							.html('<span class="mdi mdi-refresh"></span> Reset Section');
					},
				});
			}
		});
	}

	/**
	 * Initialize reset all options
	 */
	function initResetOptions() {
		$("#bloglypress-reset-options").on("click", function () {
			if (confirm(bloglyPressAdmin.resetConfirm)) {
				// Add loading state
				var $button = $(this);
				$button
					.prop("disabled", true)
					.html('<span class="mdi mdi-loading mdi-spin"></span> Resetting...');

				// Clear any existing notices
				$(".bloglypress-admin-notices").empty();

				// Reset options via AJAX
				$.ajax({
					url: bloglyPressAdmin.ajaxUrl,
					type: "POST",
					data: {
						action: "bloglypress_reset_options",
						nonce: bloglyPressAdmin.nonce,
					},
					success: function (response) {
						if (response.success) {
							showNotice(
								"success",
								response.data.message || bloglyPressAdmin.resetSuccess
							);
							// Reload page to show default values
							setTimeout(function () {
								window.location.reload();
							}, 1500);
						} else {
							showNotice(
								"error",
								response.data.message || bloglyPressAdmin.resetFail
							);
							// Reset button state
							$button
								.prop("disabled", false)
								.html(
									'<span class="mdi mdi-refresh"></span> Reset All Settings'
								);
						}
					},
					error: function () {
						showNotice("error", bloglyPressAdmin.resetFail);
						// Reset button state
						$button
							.prop("disabled", false)
							.html('<span class="mdi mdi-refresh"></span> Reset All Settings');
					},
				});
			}
		});
	}

	/**
	 * Initialize export options
	 */
	function initExportOptions() {
		$("#bloglypress-export-options").on("click", function () {
			// Add loading state
			var $button = $(this);
			$button
				.prop("disabled", true)
				.html('<span class="mdi mdi-loading mdi-spin"></span> Exporting...');

			// Export options via AJAX
			$.ajax({
				url: bloglyPressAdmin.ajaxUrl,
				type: "POST",
				data: {
					action: "bloglypress_export_options",
					nonce: bloglyPressAdmin.nonce,
				},
				success: function (response) {
					if (response.success) {
						// Create a download link
						var dataStr =
							"data:text/json;charset=utf-8," +
							encodeURIComponent(JSON.stringify(response.data));
						var downloadAnchorNode = document.createElement("a");
						downloadAnchorNode.setAttribute("href", dataStr);
						downloadAnchorNode.setAttribute(
							"download",
							"bloglypress-settings-" + getFormattedDate() + ".json"
						);
						document.body.appendChild(downloadAnchorNode);
						downloadAnchorNode.click();
						downloadAnchorNode.remove();

						showNotice("success", "Settings exported successfully!");
					} else {
						showNotice("error", "Error exporting settings. Please try again.");
					}

					// Reset button state
					$button
						.prop("disabled", false)
						.html(
							'<span class="mdi mdi-download"></span> Export Theme Settings'
						);
				},
				error: function () {
					showNotice("error", "Error exporting settings. Please try again.");
					// Reset button state
					$button
						.prop("disabled", false)
						.html(
							'<span class="mdi mdi-download"></span> Export Theme Settings'
						);
				},
			});
		});
	}

	/**
	 * Initialize import options
	 */
	function initImportOptions() {
		$("#bloglypress-import-options-form").on("submit", function (e) {
			e.preventDefault();

			// Check if file is selected
			var fileInput = $("#bloglypress-import-file")[0];
			if (fileInput.files.length === 0) {
				showNotice("error", "Please select a file to import.");
				return;
			}

			// Create FormData object
			var formData = new FormData(this);
			formData.append("action", "bloglypress_import_options");
			formData.append("nonce", bloglyPressAdmin.nonce);

			// Add loading state
			var $button = $(this).find('button[type="submit"]');
			$button
				.prop("disabled", true)
				.html('<span class="mdi mdi-loading mdi-spin"></span> Importing...');

			// Import options via AJAX
			$.ajax({
				url: bloglyPressAdmin.ajaxUrl,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (response) {
					if (response.success) {
						showNotice(
							"success",
							response.data.message || bloglyPressAdmin.importSuccess
						);
						// Reload page to show imported values
						setTimeout(function () {
							window.location.reload();
						}, 1500);
					} else {
						showNotice(
							"error",
							response.data.message || bloglyPressAdmin.importFail
						);
						// Reset button state
						$button
							.prop("disabled", false)
							.html(
								'<span class="mdi mdi-import"></span> Import Theme Settings'
							);
					}
				},
				error: function () {
					showNotice("error", bloglyPressAdmin.importFail);
					// Reset button state
					$button
						.prop("disabled", false)
						.html('<span class="mdi mdi-import"></span> Import Theme Settings');
				},
			});
		});
	}

	/**
	 * Initialize file upload
	 */
	function initFileUpload() {
		$("#bloglypress-import-file").on("change", function () {
			var fileName = $(this).val().split("\\").pop();
			if (fileName) {
				$("#bloglypress-import-file-name").text(fileName);
			} else {
				$("#bloglypress-import-file-name").text("");
			}
		});
	}

	/**
	 * Show notice
	 */
	function showNotice(type, message) {
		var noticeClass = "notice-success";

		if (type === "error") {
			noticeClass = "notice-error";
		} else if (type === "warning") {
			noticeClass = "notice-warning";
		}

		var $notice = $(
			'<div class="notice ' +
				noticeClass +
				' is-dismissible"><p>' +
				message +
				"</p></div>"
		);
		$(".bloglypress-admin-notices").html($notice);

		// Auto dismiss after 5 seconds
		setTimeout(function () {
			$notice.fadeOut(function () {
				$(this).remove();
			});
		}, 5000);
	}

	/**
	 * Get formatted date for file name
	 */
	function getFormattedDate() {
		var date = new Date();
		var year = date.getFullYear();
		var month = String(date.getMonth() + 1).padStart(2, "0");
		var day = String(date.getDate()).padStart(2, "0");

		return year + month + day;
	}
})(jQuery);
