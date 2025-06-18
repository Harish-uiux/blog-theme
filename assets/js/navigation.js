/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
	"use strict";

	// Wait for DOM to be ready
	document.addEventListener("DOMContentLoaded", function () {
		const siteNavigation = document.getElementById("site-navigation");
		const menuToggle = document.querySelector(".menu-toggle");
		const searchToggle = document.getElementById("search-toggle");
		const searchFormContainer = document.getElementById(
			"search-form-container"
		);

		// Return early if the navigation doesn't exist.
		if (!siteNavigation || !menuToggle) {
			return;
		}

		const menu = siteNavigation.querySelector("ul");

		// Hide menu toggle button if menu is empty and return early.
		if (!menu) {
			menuToggle.style.display = "none";
			return;
		}

		if (!menu.classList.contains("nav-menu")) {
			menu.classList.add("nav-menu");
		}

		// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
		menuToggle.addEventListener("click", function () {
			siteNavigation.classList.toggle("toggled");

			if (menuToggle.getAttribute("aria-expanded") === "true") {
				menuToggle.setAttribute("aria-expanded", "false");
			} else {
				menuToggle.setAttribute("aria-expanded", "true");
			}
		});

		// Handle search toggle if it exists
		if (searchToggle && searchFormContainer) {
			searchToggle.addEventListener("click", function (e) {
				e.preventDefault();
				searchFormContainer.classList.toggle("active");
				searchToggle.setAttribute(
					"aria-expanded",
					searchFormContainer.classList.contains("active") ? "true" : "false"
				);
			});

			// Close search when clicking outside
			document.addEventListener("click", function (e) {
				if (
					!searchToggle.contains(e.target) &&
					!searchFormContainer.contains(e.target)
				) {
					searchFormContainer.classList.remove("active");
					searchToggle.setAttribute("aria-expanded", "false");
				}
			});
		}

		// Close mobile menu when a menu link is clicked
		menu.querySelectorAll("a").forEach(function (link) {
			link.addEventListener("click", function () {
				if (window.innerWidth < 992) {
					siteNavigation.classList.remove("toggled");
					menuToggle.setAttribute("aria-expanded", "false");
				}
			});
		});

		// Close mobile menu when clicking outside
		document.addEventListener("click", function (e) {
			if (
				window.innerWidth < 992 &&
				!siteNavigation.contains(e.target) &&
				siteNavigation.classList.contains("toggled")
			) {
				siteNavigation.classList.remove("toggled");
				menuToggle.setAttribute("aria-expanded", "false");
			}
		});

		// Handle dropdown menus for touch devices
		const dropdownToggles = siteNavigation.querySelectorAll(
			".menu-item-has-children > a, .page_item_has_children > a"
		);

		if ("ontouchstart" in window) {
			dropdownToggles.forEach(function (link) {
				link.addEventListener("touchstart", function (e) {
					const parent = this.parentNode;

					if (!parent.classList.contains("focus")) {
						e.preventDefault();

						// Close other open dropdowns
						const siblings = Array.from(parent.parentNode.children);
						siblings.forEach(function (sibling) {
							if (sibling !== parent && sibling.classList.contains("focus")) {
								sibling.classList.remove("focus");
							}
						});

						parent.classList.add("focus");
					}
				});
			});

			// Close dropdowns when touching outside
			document.addEventListener("touchstart", function (e) {
				if (!siteNavigation.contains(e.target)) {
					siteNavigation.querySelectorAll(".focus").forEach(function (item) {
						item.classList.remove("focus");
					});
				}
			});
		}

		// Add dropdown toggles for keyboard accessibility
		dropdownToggles.forEach(function (link) {
			// Create dropdown toggle button
			const toggleButton = document.createElement("button");
			toggleButton.classList.add("dropdown-toggle");
			toggleButton.setAttribute("aria-expanded", "false");
			toggleButton.innerHTML =
				'<span class="screen-reader-text">Expand child menu</span>';

			link.parentNode.insertBefore(toggleButton, link.nextSibling);

			// Add event listener to toggle button
			toggleButton.addEventListener("click", function () {
				const parent = this.parentNode;
				parent.classList.toggle("focus");
				this.setAttribute("aria-expanded", parent.classList.contains("focus"));
			});
		});
	});
})();
