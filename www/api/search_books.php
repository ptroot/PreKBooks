<?php
if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();
header('Content-Type: application/json');

if (isset($_GET['q']) && trim($_GET['q']) !== '') {
	$query = trim($_GET['q']);
	$results = search_book($query, $conn);
	echo json_encode($results);
} else {
	echo json_encode([]);
}

db_disconnect ($conn);
?>

