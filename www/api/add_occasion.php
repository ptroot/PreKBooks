<?php
if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();

$label = $_POST['label'] ?? null;

if ("$label" == null) {
	echo "<br>Input error. Label for theme must be provided</br>";
	exit;
}

echo "<h3>Addition of " . htmlspecialchars($label) . "</h3>";

$res = add_occasion ($conn, $label);
if ($res === 0 ) {
	echo "<br>Insert of <bold>" . htmlspecialchars($label) . "</bold> succeeded</br>";
} else {
	echo "<br>Insert failed: " . $res. "</br>";
}

db_disconnect ($conn);
exit;
?>
