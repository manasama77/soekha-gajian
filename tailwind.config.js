import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
module.exports = {
	darkMode: "class",

	content: [
		"./node_modules/flowbite/**/*.js",
		"./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
		"./storage/framework/views/*.php",
		"./resources/views/**/*.blade.php",
		"./resources/js/**/*.js",
	],

	theme: {
		extend: {
			fontFamily: {
				sans: ["Inter", ...defaultTheme.fontFamily.sans],
			},

			colors: {
				dark: {
					"eval-0": "#151823",
					"eval-1": "#222738",
					"eval-2": "#2A2F42",
					"eval-3": "#2C3142",
				},
			},
		},
	},

	plugins: [require("flowbite/plugin"), require("@tailwindcss/forms")],
};
