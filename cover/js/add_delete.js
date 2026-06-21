console.log("add_delete.js loaded");
document.addEventListener("DOMContentLoaded", function () {
	const detailPanel = document.getElementById("detailPanel");

	// Helpers
	//      For a POST method
	function post(url, data) {
		return fetch(url, {
			method: "POST",
			body: data
		}).then(r => r.text());
	}

	//	For a GET method
	function get(url, data) {
		return fetch(url, {
			method: "GET",
			body: data
		}).then(r => r.text());
	}

	//      For loading into the inner HTML panel
	function loadPanel(page) {
		const panel = document.getElementById("detailPanel");

		fetch("panel_loader.php?page=" + page)
			.then(r => r.text())
			.then(html => panel.innerHTML = html);
	}

	function reloadPanel() {
		const params = new URLSearchParams(window.location.search);
		const page  = params.get("panel") || "amc_dest";

		loadPanel(page);
	}

	// Handle update/delete buttons in box table
	document.addEventListener("click", function(e) {
		console.log("click functions");

		const groupFilter = e.target.closest(".group-filter");
		const updateURL = e.target.closest(".update-url");
		const deleteURL = e.target.closest(".delete-url");
		const addURL    = e.target.closest(".add-url");

		if (groupFilter) {
			console.log("group filter");
			e.preventDefault();

			const select = document.querySelector('.fgroup');
			const fGroup = select.value;

			const formData = new FormData();
			formData.append('fGroup', fGroup);

			post ("amc_dest.php", formData)
			.then (html => {
				document.getElementById("detailPanel").innerHTML = html;
			});
		}
		
		if (updateURL || deleteURL || addURL) {
			console.log("action triggered");
			e.preventDefault();

			const trigger = updateURL || deleteURL || addURL;
			const id = trigger.dataset.id;

			const input = document.querySelector(`input.group-input[data-id='${id}']`);
			const group = input.value;

			const input2 = document.querySelector(`input.label-input[data-id='${id}']`);
			const label = input2.value;

			const input3 = document.querySelector(`input.url-input[data-id='${id}']`);
			const url = input3.value;

			if (!url.trim()) {
				alert("URL cannot be empty");
				return;
			}
			if (!group.trim()) {
				alert("Group cannot be empty");
				return;
			}
			if (!label.trim()) {
				alert("Label cannot be empty");
				return;
			}

			const formData = new FormData();
			formData.append("id", id);
			formData.append("group", group);
			formData.append("label", label);
			formData.append("url", url);

			if (updateURL) {
				formData.append("action", "update");
			} else if (deleteURL) {
				formData.append("action", "delete");
			} else if (addURL) {
				formData.append("action", "add");
			}

			post("process_dest.php", formData)
			.then(html => {
				document.getElementById("detailPanel").innerHTML = html;

				// refresh the tree panel
				reloadTree();
			});
		}

		function reloadTree() {
			fetch("tree_panel.php")
			.then(res => res.text())
			.then(html => {
				document.getElementById("treePanel").innerHTML = html;
			});
		}
	});
});

