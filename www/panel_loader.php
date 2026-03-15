<?php

$page = $_GET['page'] ?? '';

$allowed_pages = [
	"amc_box" => "views/amc_box.php",
	"add_book" => "views/add_book.php",
	"amc_occasion" => "views/amc_occasion.php",
];


if (array_key_exists($page, $allowed_pages)) {
	include $allowed_pages[$page];
} else {
	echo "Invalid selection.";
}
?>
