<?php
require_once "../book_lib.php";
$conn = db_connect();

$id = $_POST['id'] ?? null;
$book = get_book ($conn, $id);

echo "<h2>Deleting Book</h2>";
if (!$book) {
	echo "<p>Book not found";
	exit;
}

echo "<h3>Title: " . htmlspecialchars($book['Title']) . "</h3>";
//echo "<br>" . htmlspecialchars($id);
$res = delete_book ($conn, $id);
if ($res === 0) {
	echo "<br>Delete succeeded";
} else {
	echo "<br>Delete failed: " . $res;
}

db_disconnect ($conn);
exit;
?>
