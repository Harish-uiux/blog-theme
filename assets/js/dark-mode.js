/**
 * Dark mode functionality for Hariharan theme
 */
(function () {
	"use strict";

	// Get dark mode toggle buttons
	const darkModeToggle = document.getElementById("dark-mode-toggle");
	const mobileDarkModeToggle = document.getElementById(
		"mobile-dark-mode-toggle"
	);

	// Check for system preference
	const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

	// Function to set theme
	function setTheme(theme) {
		document.documentElement.setAttribute("data-theme", theme);
		localStorage.setItem("hariharan-theme", theme);

		// Update aria-pressed on both toggles
		[darkModeToggle, mobileDarkModeToggle].forEach(function (toggle) {
			if (toggle) {
				toggle.setAttribute("aria-pressed", theme === "dark");
			}
		});
	}

	// Function to toggle theme
	function toggleTheme() {
		const currentTheme =
			document.documentElement.getAttribute("data-theme") || "light";
		const newTheme = currentTheme === "light" ? "dark" : "light";
		setTheme(newTheme);
	}

	// Initialize theme
	function initializeTheme() {
		// Check for saved preference
		const savedTheme = localStorage.getItem("hariharan-theme");

		if (savedTheme) {
			// Use saved preference
			setTheme(savedTheme);
		} else if (
			window.hariharanSettings &&
			window.hariharanSettings.defaultDarkMode === "true"
		) {
			// Use default setting from theme options
			setTheme("dark");
		} else if (prefersDarkScheme.matches) {
			// Use system preference
			setTheme("dark");
		} else {
			// Default to light
			setTheme("light");
		}
	}

	// Make toggleTheme available globally for mobile toggle
	window.hariharanSetTheme = toggleTheme;

	// Initialize on load
	document.addEventListener("DOMContentLoaded", function () {
		initializeTheme();

		// Add event listeners to toggle buttons
		[darkModeToggle, mobileDarkModeToggle].forEach(function (toggle) {
			if (toggle) {
				toggle.addEventListener("click", toggleTheme);
			}
		});

		// Listen for system preference changes
		prefersDarkScheme.addEventListener("change", function (e) {
			// Only apply if user hasn't set a preference
			if (!localStorage.getItem("hariharan-theme")) {
				setTheme(e.matches ? "dark" : "light");
			}
		});
	});
})();
