<?php
ini_set('display_errors', 1);
if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();

$label = $_POST['label'] ?? null;

//if ("$label" == null) {
//	echo "<br>Input error. Label for box must be provided</br>";
//	exit;
//}

echo "<h3>Addition of " . htmlspecialchars($label) . "</h3>";

$res = add_box ($conn, $label);
if ($res !== 0) {
	echo "<br>Insert failed: " . $res . "</br>";
} else {
	echo "<br>Insert of <bold>" . htmlspecialchars($label) . "</bold> succeeded</br>";
}

db_disconnect ($conn);

exit;
?>
