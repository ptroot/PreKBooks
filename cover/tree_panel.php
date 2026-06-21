<?php
	require_once "tree_lib.php";

	$tree = read_csv("tree.csv");

	echo "<ul>";
	foreach ($tree as $group => $urls) {
		echo "<li class='collapsed'><span class='group'>" . htmlspecialchars ($group) . "</span>\n";
		echo "<ul>\n";
		foreach ($urls as $label => $http) {
			echo "<li><a href='" . htmlspecialchars ($http) . "'>" . htmlspecialchars ($label)  . "</a>\n";
		}
		echo "</ul></li>\n";
	}
	echo "</ul>";
 ?>
