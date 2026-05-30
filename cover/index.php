<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Book Catalog</title>
	<link rel="stylesheet" href="cover/styles.css">
	<link rel="stylesheet" href="cover/dropdown.css">
</head>
<body>
<script src="cover/js/tree.js"> </script>
<script src="cover/js/add_delete.js"></script>
<h2>Book Catalog</h2>
<div class='menu'>
	<div class="dropdown">
	    <button id='amcDest' class='dropbtn'>Add/Update/Delete</button>
		<button id="toggleButton" class='dropbtn'>Expand All</button>
	</div>
	<input type="text" id="weatherInput" placeholder="Enter city for weather..." class="dropbtn" />
	<button onclick="showWeather()" class='dropbtn'>Get Weather</button>
</div>
<div class=layout>
<div class=tree-panel id='treePanel'>
   <?php include "cover/tree_panel.php"; ?>
</ul>
</div>
<div class="detail-panel" id="detailPanel">
<img src="https://wttr.in/Buffalo+Minnesota.png?u" alt="Weather for Buffalo">
</div>
</div>
</body>
</html>

