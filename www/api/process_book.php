<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();

$occ = get_occasions($conn);

$title = $_POST['title'] ?? null;
$author = $_POST['author'] ?? null;
$box_id = $_POST['box'] ?? null;

if ($title == null || $title === '') {
	echo "<br>Input error. Title of book must be provided</br>";
	exit;
}

echo "<h3>Addition of Book: " . htmlspecialchars($title) . "</h3>";

list ($res, $value) = add_book ($conn, $title, $author, $box_id);
if ($res === 0) {
	$insert_id = $value;
} else {
	echo "<br>Insert failed: " . $value . "</br>";
	exit;
}

echo "<table class='add-book-table'><tr><th>Title</th><td>" . htmlspecialchars($title) . "</td></tr>";
echo "<tr><th>Author</th><td>" . htmlspecialchars($author) . "</td></tr>";
echo "<tr><th>Box ID</th><td>" . htmlspecialchars($box_id) . "</td></tr>";
echo "</table>";

$occasions = $_POST['occasions'] ?? [];
echo "<table class='add-book-table'><tr><th>Themes</th><th>Result</td></tr>";
if (!empty($occasions)) {
	list ($success, $failed) = add_book_occasions ($conn, $insert_id, $occasions);
	foreach ($occasions as $occasion) {
		$theme = htmlspecialchars($occ[$occasion] ?? 'Unknown');
		if (strpos($success, $occasion) !== false) {
			echo "<tr><th>$theme</th><td>Success</td></tr>";
		} elseif (strpos($failed, $idx) !== false) {
			echo "<tr><th>$theme</th><td>Insert failed</td></tr>";
		} else {
			echo "<tr><th>$theme</th><td>Unknown result</td></tr>";
		}
	}
} else {
	echo "<tr><th>Themes</th><td>No themes selected</td></tr>";
}

echo "</table>";

db_disconnect ($conn);
exit;
?>
