// -------------------- Collapsible Tree --------------------
document.addEventListener("DOMContentLoaded", function() {

	const toggleButton = document.getElementById("toggleButton");
	const boxes = document.querySelectorAll(".box");

	// Individual box toggle
	document.querySelectorAll('.box-label').forEach(label => {
		label.addEventListener('click', function() {
			this.parentElement.classList.toggle('open');
		});
	});

	let expanded = false;

	toggleButton.addEventListener("click", function() {
		expanded = !expanded;

		boxes.forEach(box => {
			box.classList.toggle("open", expanded);
		});

		toggleButton.textContent = expanded ? "Collapse All" : "Expand All";
	});

	// -------------------- Load Account Details --------------------
	function loadBookDetails(bookId) {
		const detailPanel = document.getElementById('detailPanel');

		// Highlight selected account in tree
		document.querySelectorAll('.tree-book-link').forEach(link => {
			if (link.dataset.id === bookId) {
				link.classList.add('selected-book');

				// Expand parent group so it is visible
				let parentLi = link.closest('li').parentElement.closest('li');
				if (parentLi) {
					parentLi.classList.add('open');
				}
			} else {
				link.classList.remove('selected-book');
			}
		});

		// Load account details via AJAX
		fetch(`views/book_detail.php?id=${bookId}`)
		.then(response => response.text())
		.then(html => {
			detailPanel.innerHTML = html;
		})
		.catch(err => {
			detailPanel.innerHTML = '<p>Error loading account details.</p>';
			console.error(err);
		});
	}


	// -------------------- Tree book Click --------------------
	document.querySelectorAll('.tree-book-link').forEach(link => {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			const bookId = this.dataset.id;
			loadBookDetails(bookId);
		});
	});

	// -------------------- Live Search --------------------
	const searchInput = document.getElementById('searchInput');
	const detailPanel = document.getElementById('detailPanel');
	let searchTimeout = null;

	searchInput.addEventListener('input', function() {
		const query = this.value.trim();
		clearTimeout(searchTimeout);

		if (query === '') {
			detailPanel.innerHTML = '<p>Select a book the tree or search by title or author above.</p>';
			return;
		}

		searchTimeout = setTimeout(() => {
			fetch(`api/search_books.php?q=${encodeURIComponent(query)}`)
				.then(res => res.json())
				.then(data => {
					if (data.length === 0) {
						detailPanel.innerHTML = `<p>No books found for "<strong>${query}</strong>".</p>`;
						return;
					}

					// Build clickable search results
					//const listItems = data.map(book => {
					//	return `<br><a href="#" class="book-link" data-id="${book.id}">${book.title}</a>  ${book.author}`;
					//}).join('');


					const listItems = data.map(book => {
						return `<div class="book-row">
								<a href="#" class="book-link title" data-id="${book.id}">
									${book.title}
								</a>
								<span class="author">${book.author}</span>
							</div>`;
					}).join('');

					detailPanel.innerHTML = `<h3>Search Results</h3>
								<div class="book-header">
									<span class='title'>Title</span>
									<span class='author-head'>Author</span>
								</div>` + listItems;

					// Attach click listeners
					detailPanel.querySelectorAll('.book-link').forEach(link => {
						link.addEventListener('click', function(e) {
							e.preventDefault();
							const bookId = this.dataset.id;
							loadBookDetails(bookId);
						});
					});
				})
				.catch(err => {
					searchResultsDiv.innerHTML = '<p>Error fetching results.</p>';
					console.error(err);
				});
		}, 300); // debounce
	});


	// -------------------- Load initial book if ?book_id=... --------------------
	const params = new URLSearchParams(window.location.search);
	const bookId = params.get('book_id');
	if (bookId) {
		loadAccountDetails(acctId);
	}

	document.addEventListener("submit", function(e) {
		const form = e.target.closest(".ajax-form");
		if (!form) return;

		e.preventDefault();

		fetch(form.action, {
			method: "POST",
			body: new FormData(form)
		})
		.then(res => res.text())
		.then(html => {
			document.getElementById("detailPanel").innerHTML = html;
		});
	});
});

