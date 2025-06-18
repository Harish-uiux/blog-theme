/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./*.php",
		"./template-parts/**/*.php",
		"./inc/**/*.php",
		"./assets/js/**/*.js",
	],
	theme: {
		extend: {
			colors: {
				primary: "var(--hariharan-primary-color)",
				secondary: "var(--hariharan-secondary-color)",
				background: "var(--hariharan-bg-color)",
				text: "var(--hariharan-text-color)",
			},
		},
	},
	plugins: [],
	corePlugins: {
		preflight: false,
	},
	darkMode: ["class", '[data-theme="dark"]'],
};
