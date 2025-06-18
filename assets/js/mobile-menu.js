/**
 * Dropdown mobile menu functionality for Hariharan theme
 */
(function () {
	"use strict";

	document.addEventListener("DOMContentLoaded", function () {
		// Elements
		const mobileToggle = document.getElementById("mobile-menu-toggle");
		const body = document.body;
		const navigationWrapper = document.getElementById("navigation-wrapper");

		// Search toggles (desktop and mobile)
		const searchToggle = document.getElementById("search-toggle");
		const mobileSearchToggle = document.getElementById("mobile-search-toggle");
		const searchContainer = document.getElementById("search-container");
		const searchFormContainer = document.getElementById(
			"search-form-container"
		); // For compatibility

		// Dark mode toggles (desktop and mobile)
		const darkModeToggle = document.getElementById("dark-mode-toggle");
		const mobileDarkModeToggle = document.getElementById(
			"mobile-dark-mode-toggle"
		);

		// Toggle mobile menu
		if (mobileToggle) {
			mobileToggle.addEventListener("click", function (e) {
				e.preventDefault();
				body.classList.toggle("mobile-menu-active");

				// Set ARIA attributes
				const expanded = body.classList.contains("mobile-menu-active");
				mobileToggle.setAttribute("aria-expanded", expanded);
			});

			// Close menu when clicking outside
			document.addEventListener("click", function (e) {
				if (
					body.classList.contains("mobile-menu-active") &&
					navigationWrapper &&
					!navigationWrapper.contains(e.target) &&
					!mobileToggle.contains(e.target)
				) {
					body.classList.remove("mobile-menu-active");
					mobileToggle.setAttribute("aria-expanded", "false");
				}
			});
		}

		// Handle submenu toggles
		const parentMenuItems = document.querySelectorAll(
			".menu-item-has-children, .page_item_has_children"
		);

		parentMenuItems.forEach(function (item) {
			// Only process for mobile view
			item.addEventListener("click", function (e) {
				// Only execute on mobile
				if (window.innerWidth < 992) {
					// If clicked directly on the link
					if (e.target.tagName.toLowerCase() === "a") {
						// If the item has a submenu
						if (item.querySelector("ul")) {
							e.preventDefault();
							e.stopPropagation(); // Prevent event bubbling

							// Toggle submenu
							item.classList.toggle("submenu-open");

							// Close sibling submenus
							const siblings = Array.from(item.parentNode.children);
							siblings.forEach(function (sibling) {
								if (
									sibling !== item &&
									sibling.classList.contains("submenu-open")
								) {
									sibling.classList.remove("submenu-open");
								}
							});
						}
					}
				}
			});
		});

		// Handle search toggle (desktop and mobile)
		function setupSearchToggle(toggleButton) {
			if (toggleButton) {
				const searchTarget = searchContainer || searchFormContainer;

				if (searchTarget) {
					toggleButton.addEventListener("click", function (e) {
						e.preventDefault();

						// Close mobile menu if open
						if (body.classList.contains("mobile-menu-active")) {
							body.classList.remove("mobile-menu-active");
							if (mobileToggle) {
								mobileToggle.setAttribute("aria-expanded", "false");
							}
						}

						// Toggle search
						searchTarget.classList.toggle("active");
						toggleButton.setAttribute(
							"aria-expanded",
							searchTarget.classList.contains("active") ? "true" : "false"
						);

						// If search is being opened, focus the search input
						if (searchTarget.classList.contains("active")) {
							setTimeout(function () {
								const searchInput = searchTarget.querySelector(
									'input[type="search"]'
								);
								if (searchInput) {
									searchInput.focus();
								}
							}, 100);
						}
					});
				}
			}
		}

		// Setup search toggle for both desktop and mobile
		setupSearchToggle(searchToggle);
		setupSearchToggle(mobileSearchToggle);

		// Sync dark mode toggles
		if (darkModeToggle && mobileDarkModeToggle && window.hariharanSetTheme) {
			mobileDarkModeToggle.addEventListener("click", function () {
				// The function is defined in dark-mode.js
				window.hariharanSetTheme();
			});
		}

		// Close search when clicking outside
		document.addEventListener("click", function (e) {
			const searchTarget = searchContainer || searchFormContainer;

			if (
				searchTarget &&
				searchTarget.classList.contains("active") &&
				!searchToggle.contains(e.target) &&
				!mobileSearchToggle.contains(e.target) &&
				!searchTarget.contains(e.target)
			) {
				searchTarget.classList.remove("active");
				searchToggle.setAttribute("aria-expanded", "false");
				if (mobileSearchToggle) {
					mobileSearchToggle.setAttribute("aria-expanded", "false");
				}
			}
		});

		// Reset on window resize
		let resizeTimer;
		window.addEventListener("resize", function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				if (window.innerWidth >= 992) {
					// Remove mobile menu active class
					body.classList.remove("mobile-menu-active");

					if (mobileToggle) {
						mobileToggle.setAttribute("aria-expanded", "false");
					}

					// Close all submenus
					parentMenuItems.forEach(function (item) {
						item.classList.remove("submenu-open");
					});
				}
			}, 250);
		});
	});
})();
