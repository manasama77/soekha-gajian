import { Modal } from "flowbite";

// set the modal menu element
const $targetEl = document.getElementById("modalEl");

// options with default values
const options = {
	placement: "center-center",
	backdrop: "dynamic",
	backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
	closable: true,
	onHide: () => {
		console.log("modal is hidden");
	},
	onShow: () => {
		console.log("modal is shown");
	},
	onToggle: () => {
		console.log("modal has been toggled");
	},
};

// instance options object
const instanceOptions = {
	id: "modalEl",
	override: true,
};

modal = new Modal($targetEl, options, instanceOptions);

modal.show();
console.log("1");
