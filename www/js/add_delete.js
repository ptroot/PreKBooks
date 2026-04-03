console.log("add_delete.js loaded");
document.addEventListener("DOMContentLoaded", function () {

	const button = document.getElementById("Selector");
	const dropdown = document.getElementById("myDropdown");
	const detailPanel = document.getElementById("detailPanel");

	function updatePanel(html) {
		detailPanel.innerHTML = html;
	}

	function reloadTree() {
		fetch("views/book_tree.php")
		.then(res => res.text())
		.then(html => {
			document.getElementById("treePanel").innerHTML = html;

			// reapply highlight after reload
			if (window.loadBookDetails && window.currentBookId) {
				loadBookDetails(currentBookId);
			}
		})
		.catch(err => console.error("Tree reload failed:", err));
	}

	// Toggle dropdown on button click
	button.addEventListener("click", function (e) {
		e.stopPropagation();
		dropdown.classList.toggle("show");
	});

	// Close dropdown if clicking outside
	window.addEventListener("click", function () {
		dropdown.classList.remove("show");
	});

	// Helpers
	// 	For a POST method
	function post(url, data) {
		return fetch(url, {
			method: "POST",
			body: data
		}).then(r => r.text());
	}

	//	For loading into the inner HTML panel
	function loadPanel(page) {

		fetch("panel_loader.php?page=" + page)
			.then(r => r.text())
			.then(html => detailPanel.innerHTML = html);
	}

	function reloadPanel() {
		const params = new URLSearchParams(window.location.search);
		const page  = params.get("panel") || "add_book";

		loadPanel(page);
	}
	
	document.getElementById("treePanel").addEventListener("click", function(e) {
		const toggle = e.target.closest(".box-label");
		if (!toggle) return;

		const box = toggle.closest(".box");
		if (!box) return;

		box.classList.toggle("open"); // 👈 must match CSS
	});	

	// Handle menu item clicks
	document.querySelectorAll("#myDropdown a").forEach(item => {
		item.addEventListener("click", function (e) {
			e.preventDefault();

			const page = this.dataset.page;

			fetch("panel_loader.php?page=" + page)
				.then(r => r.text())
				.then(data => {
					detailPanel.innerHTML = data;
					history.pushState({}, "", "?panel=" + page);
				});

			dropdown.classList.remove("show");
		});
	});

	// Handle submit calls in forms
	document.addEventListener("submit", function(e) {
		console.log("Submit handler");
		const form = e.target.closest(".ajax-form");
		if (!form) return;

		e.preventDefault();

		fetch(form.action, {
			method: "POST",
			body: new FormData(form)
		})
		.then(res => res.text())
		.then(html => {
			updatePanel(html);
			// refresh the tree panel
			reloadTree();
		});
	});

	// Handle update/delete buttons in box table
	document.addEventListener("click", function(e) {
		// Update box
		const updateBox = e.target.closest(".update-box");
		if (updateBox) {
			e.preventDefault();
			const id = updateBox.dataset.id;
			const input = document.querySelector(`input.box-label-input[data-id='${id}']`);
			const label = input.value;
	
			if (!label.trim()) {
				alert("Label cannot be empty");
				return;
			}
	
			const formData = new FormData();
			formData.append("id", id);
			formData.append("label", label);
			formData.append("action", "update");
	
			post("api/modify_box.php", formData)
				.then(html => {
					updatePanel(html);
					// refresh the tree panel
					reloadTree();
				})
				.catch(err => console.error("Error:", err));
			return;
		}

		// Update occasion
		const updateOcc = e.target.closest(".update-occ");
		if (updateOcc) {
			e.preventDefault();
			const id = updateOcc.dataset.id;
			const input = document.querySelector(`input.occ-label-input[data-id='${id}']`);
			const label = input.value;

			console.log("updateOcc");
			if (!label.trim()) {
				alert("Label cannot be empty");
				return;
			}
	
			const formData = new FormData();
			formData.append("id", id);
			formData.append("label", label);
			formData.append("action", "update");
	
			post("api/modify_occ.php", formData)
				.then(html => {
					updatePanel(html);
				})
				.catch(err => console.error("Error:", err));
			return;
		}

		// Delete box
		const deleteBox = e.target.closest(".delete-box");
		if (deleteBox) {
			e.preventDefault();

			console.log("deleteBox");
			if (!confirm("Delete this box? Books in the box will not be deleted.")) return;
	
			const id = deleteBox.dataset.id;
			const formData = new FormData();
			formData.append("id", id);
			formData.append("action", "delete");
	
			post("api/modify_box.php", formData)
				.then(html => {
					updatePanel(html);

					// refresh the tree panel
					reloadTree();
				})
				.catch(err => console.error("Error:", err));
			return;
		}

		// Delete occasion 
		const deleteOcc = e.target.closest(".delete-occ");
		if (deleteOcc) {
			e.preventDefault();

			console.log("deleteOcc");
			if (!confirm("Delete this theme?")) return;
	
			const id = deleteOcc.dataset.id;
			const formData = new FormData();
			formData.append("id", id);
			formData.append("action", "delete");
	
			post("api/modify_occ.php", formData)
				.then(html => {
					updatePanel(html);
				})
				.catch(err => console.error("Error:", err));
			return;
		}

		// Delete book
		const deleteBook = e.target.closest(".delete-book");
		if (deleteBook) {
			e.preventDefault();

			console.log ("deleteBook");
			if (!confirm("Delete this book? All links with themes will be deleted")) return;

			const formData = new FormData();
			formData.append("id", deleteBook.dataset.id);

			post("api/delete_book.php", formData)
				.then(html => {
					updatePanel(html);

					// refresh the tree panel
					reloadTree();
				})
				.catch(err => console.error("Error:", err));
			return;
		}
	});
});
