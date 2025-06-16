/**
 * Main JavaScript file for BloglyPress theme
 */
(function () {
	"use strict";

	// Document ready
	document.addEventListener("DOMContentLoaded", function () {
		// Initialize theme components
		initMobileMenu();
		initScrollToTop();
		initStickyHeader();
		initLazyLoading();
		initDarkMode();
		initTableOfContents();
		initReadingProgressBar();
	});

	/**
	 * Initialize mobile menu
	 */
	function initMobileMenu() {
		const menuToggle = document.querySelector(".menu-toggle");
		const primaryMenuContainer = document.querySelector(
			".primary-menu-container"
		);

		if (!menuToggle || !primaryMenuContainer) return;

		menuToggle.addEventListener("click", function () {
			this.classList.toggle("toggled");
			primaryMenuContainer.classList.toggle("menu-open");

			// Toggle aria-expanded attribute
			const expanded = this.getAttribute("aria-expanded") === "true" || false;
			this.setAttribute("aria-expanded", !expanded);
		});

		// Close menu when clicking outside
		document.addEventListener("click", function (event) {
			if (
				!event.target.closest(".menu-toggle") &&
				!event.target.closest(".primary-menu-container")
			) {
				menuToggle.classList.remove("toggled");
				primaryMenuContainer.classList.remove("menu-open");
				menuToggle.setAttribute("aria-expanded", "false");
			}
		});

		// Add dropdown toggles for submenus
		const subMenuParents = document.querySelectorAll(".menu-item-has-children");

		subMenuParents.forEach(function (menuItem) {
			const dropdownToggle = document.createElement("button");
			dropdownToggle.classList.add("dropdown-toggle");
			dropdownToggle.setAttribute("aria-expanded", "false");
			dropdownToggle.innerHTML =
				'<span class="screen-reader-text">Toggle submenu</span>';

			menuItem.appendChild(dropdownToggle);

			dropdownToggle.addEventListener("click", function (event) {
				event.preventDefault();
				const parent = this.parentNode;
				const subMenu = parent.querySelector(".sub-menu");

				// Toggle submenu
				parent.classList.toggle("submenu-open");
				subMenu.classList.toggle("submenu-open");

				// Toggle aria-expanded attribute
				const expanded = this.getAttribute("aria-expanded") === "true" || false;
				this.setAttribute("aria-expanded", !expanded);
			});
		});
	}

	/**
	 * Initialize scroll to top button
	 */
	function initScrollToTop() {
		const backToTop = document.getElementById("back-to-top");

		if (!backToTop) return;

		// Show/hide button based on scroll position
		window.addEventListener("scroll", function () {
			if (window.pageYOffset > 300) {
				backToTop.classList.add("show");
			} else {
				backToTop.classList.remove("show");
			}
		});

		// Scroll to top when button is clicked
		backToTop.addEventListener("click", function (event) {
			event.preventDefault();
			window.scrollTo({
				top: 0,
				behavior: "smooth",
			});
		});
	}

	/**
	 * Initialize sticky header
	 */
	function initStickyHeader() {
		const header = document.querySelector(".sticky-header");

		if (!header) return;

		let lastScrollTop = 0;
		const scrollThreshold = 50;

		window.addEventListener("scroll", function () {
			const currentScrollTop =
				window.pageYOffset || document.documentElement.scrollTop;

			if (
				currentScrollTop > lastScrollTop &&
				currentScrollTop > scrollThreshold
			) {
				// Scrolling down
				header.classList.add("header-hidden");
			} else {
				// Scrolling up
				header.classList.remove("header-hidden");
			}

			lastScrollTop = currentScrollTop;
		});
	}

	/**
	 * Initialize lazy loading for images
	 */
	function initLazyLoading() {
		// Check if the browser supports IntersectionObserver
		if ("IntersectionObserver" in window) {
			const lazyImages = document.querySelectorAll('img[loading="lazy"]');

			const imageObserver = new IntersectionObserver(function (
				entries,
				observer
			) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						const image = entry.target;
						image.src = image.dataset.src;

						if (image.dataset.srcset) {
							image.srcset = image.dataset.srcset;
						}

						image.classList.add("loaded");
						observer.unobserve(image);
					}
				});
			});

			lazyImages.forEach(function (image) {
				imageObserver.observe(image);
			});
		}
	}

	/**
	 * Initialize dark mode
	 */
	function initDarkMode() {
		const darkModeToggle = document.querySelector(".dark-mode-toggle");
		const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

		if (!darkModeToggle) return;

		// Check for saved dark mode preference
		const savedDarkMode = localStorage.getItem("darkMode");

		// Set initial dark mode state
		if (
			savedDarkMode === "true" ||
			(savedDarkMode === null && prefersDarkScheme.matches)
		) {
			document.body.classList.add("dark-mode");
			darkModeToggle.setAttribute("aria-pressed", "true");
		} else {
			document.body.classList.remove("dark-mode");
			darkModeToggle.setAttribute("aria-pressed", "false");
		}

		// Toggle dark mode
		darkModeToggle.addEventListener("click", function () {
			const isDarkMode = document.body.classList.toggle("dark-mode");
			localStorage.setItem("darkMode", isDarkMode);
			this.setAttribute("aria-pressed", isDarkMode);
		});
	}

	/**
	 * Initialize table of contents
	 */
	function initTableOfContents() {
		const toc = document.querySelector(".bloglypress-table-of-contents");

		if (!toc) return;

		// Add IDs to headings if they don't have one
		const content = document.querySelector(".entry-content");
		if (!content) return;

		const headings = content.querySelectorAll("h2, h3, h4, h5, h6");

		headings.forEach(function (heading, index) {
			if (!heading.id) {
				const id = "heading-" + index;
				heading.id = id;
			}
		});

		// Highlight current section in TOC
		const tocLinks = toc.querySelectorAll("a");

		const observerOptions = {
			root: null,
			rootMargin: "0px 0px -70% 0px",
			threshold: 0,
		};

		const headingObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				const id = entry.target.getAttribute("id");
				const tocLink = toc.querySelector(`a[href="#${id}"]`);

				if (tocLink) {
					if (entry.isIntersecting) {
						// Remove active class from all links
						tocLinks.forEach((link) => link.classList.remove("active"));
						// Add active class to current link
						tocLink.classList.add("active");
					}
				}
			});
		}, observerOptions);

		headings.forEach(function (heading) {
			headingObserver.observe(heading);
		});
	}

	/**
	 * Initialize reading progress bar
	 */
	function initReadingProgressBar() {
		const progressBar = document.getElementById("reading-progress-bar");

		if (!progressBar || !document.body.classList.contains("single")) return;

		window.addEventListener("scroll", function () {
			const docElement = document.documentElement;
			const docBody = document.body;
			const scrollTop = docElement.scrollTop || docBody.scrollTop;
			const scrollHeight = docElement.scrollHeight || docBody.scrollHeight;
			const clientHeight = docElement.clientHeight;

			const scrollPercentage =
				(scrollTop / (scrollHeight - clientHeight)) * 100;
			progressBar.style.width = scrollPercentage + "%";
		});
	}
})();
