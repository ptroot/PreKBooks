<!DOCTYPE html>
<html>
<head>
	<title>Preschool Books</title>
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="dropdown.css">

	<script src="js/book_tree.js"> </script>
	<script src="js/add_delete.js"> </script>
</head>
<body>

<h2>Preschool Books</h2>
<div> 
	<div class="dropdown">
		<button id="Selector" class='dropbtn'>Add/Delete</button>
			<div id="myDropdown" class="dropdown-content">
				<a href="#" data-page='add_book'>Add Book</a>
				<a href="#" data-page='amc_box'>Add/Delete Box</a>
				<a href="#" data-page='amc_occasion'>Add/Delete Theme</a>
			</div>
		<button id="toggleButton" class='dropbtn'>Expand All</button>
	</div>
	<input type="text" id="searchInput" placeholder="Search books..." class="dropbtn" />
</div>

