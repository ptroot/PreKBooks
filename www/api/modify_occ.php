<?php
require_once "../book_lib.php";
$conn = db_connect();

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? '';

if ($action === 'update') {
	$label = $_POST['label'] ?? '';
	if ($label === null || trim($label) === "") {
		echo "Label cannot be empty";
		exit;
	}

	$res = modify_occasion ($conn, $label, $id);
	if ($res == 0) {
		echo "Update succeeded";
	} else {
		echo "Update failed: " . $res;
	}
}

if ($action === 'delete') {
	$res = delete_occasion ($conn, $id);
	if ($res === 0) {
		echo "Delete succeeded";
	} else {
		echo "Delete failed: " . $res;
	}
}

db_disconnect ($conn);
exit;
?>
