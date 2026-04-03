<?php

if (file_exists("book.lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();

$bookId = $_POST['book_id'] ?? null;
$todo = $_POST['action'] ?? '';

$book = get_book ($conn, $bookId);
$boxes = get_boxes($conn);
?>

<h2>Book Updates</h2>
<?php
$updates = [];

$data = $_POST;

$title = $_POST['Title'] ?? '';
$title = trim ($title);
$dbtitle = trim ($book['Title']);
if ($title !== $dbtitle ) {
	$updates['Title'] = "$title";	
}

$author = $_POST['Author'];
$author = trim ($author);
$dbauthor = trim ($book ['Author']);
if ($author !== $dbauthor ) {
	$updates['Author'] = "$author"; 
}

$box_id = $_POST['box']; 
$box_id = (int) $box_id;
$box_name = $boxes[$box_id] ?? "FAILED";

if ($box_name  !== $book['Box']) {
	$updates['box_id'] = $box_id;
}

echo "<table class='add-book-table'>";
if (!empty($updates)) {
	if (!array_key_exists('Title', $updates)) {
		echo "<tr><th>Title</th><td>" . $title . "</td></tr>";
	}
	$res = update_book ($conn, $bookId,  $updates);

	foreach ($updates as $key => $value) {
		if ($key === 'box_id') {
			echo "<tr><th>Box</th><td>" . htmlspecialchars($boxes[$value]) . "</td></tr>";
		} else {	
			echo "<tr><th>" . htmlspecialchars($key) . "</th><td>" . htmlspecialchars($value) . "</td></tr>";
		}
	}

	if ($res) {
		echo "<br>Change failed";
	} else {
		echo "<br>Change suceeded";
	}
} else {
	echo "<tr><th>Title</th><td>" . $title . "</td></tr>";
}

echo "<tr><th>Themes</th><td></td></tr>";

$occasions = array_map('intval', $_POST['occasions'] ?? []);
update_occasions($conn, $bookId, $occasions);

$occ = get_occasions ($conn);
foreach ($occ as $oid => $label) {
	if (in_array($oid, $occasions)) {
		echo "<tr><th></th><td>$label</td></tr>";
	}
}

echo "</table>";

db_disconnect ($conn);
exit;
?>
