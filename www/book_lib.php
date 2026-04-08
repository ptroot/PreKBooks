<?php

// ===============================
// Database Configuration
// ===============================

function decrypt($data, $key) {
	$cipher = "AES-256-CBC";
	$data = base64_decode($data);
	$ivLength = openssl_cipher_iv_length($cipher);
	$iv = substr($data, 0, $ivLength);
	$encrypted = substr($data, $ivLength);
	return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}


// Connecting to the database
function db_connect() {
	include "vars.php";
	$password = decrypt ($enc,'account_details');

	$conn = new mysqli($host, $username, $password, $database);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	return $conn;
}

function db_disconnect($conn) {
	$conn->close();
}

function box_tree($conn) {

	$sql = "SELECT
			x.id		AS box_id,
			x.label		AS box_name,
			b.title		AS title,
			b.id		AS book_id,
			b.author	AS author
		FROM box x
		LEFT JOIN book b ON x.id = b.box_id
		ORDER BY x.label, b.title
	";

	$result = $conn->query($sql);
	$tree = [];

	while ($row = $result->fetch_assoc()) {
		$xid = $row['box_id'];

		if (!isset($tree[$xid])) {
			$tree[$xid] = [
				'name' => $row['box_name'],
				'children' => []
			];
		}

		if ($row['book_id']) {
			$tree[$xid]['children'][] = [
				'id' => $row['book_id'],
				'title' => $row['title'],
				'author' => $row['author']
			];
		}
	}

	return $tree;
}

function get_book ($conn, $id) {
	$sql= "SELECT 
			b.title  as Title,
			b.author as Author,
			x.label  as Box
		FROM book b 
		LEFT JOIN box x ON b.box_id = x.id
		WHERE b.id = ?";
	$stmt = $conn->prepare($sql);

	$stmt->bind_param("i", $id);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	$stmt->close();

	return $row;
}

// ===============================
// Search book table by title
// ===============================
function search_book($searchString, $conn) {

	// Search for the string in the occasion table (label) and in the book table (title and author)
	$sql = "SELECT DISTINCT b.*,bx.label FROM book b 
			LEFT JOIN occ_book ob ON b.id = ob.book_id
			LEFT JOIN occasion o  ON o.id = ob.occasion_id
			LEFT JOIN box bx      ON b.box_id = bx.id
			WHERE
				o.label  LIKE ?
			     OR b.title  LIKE ?
			     OR b.author LIKE ?";
	$stmt = $conn->prepare($sql);
	$searchParam = "%" . $searchString . "%";

	$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);

	$stmt->execute();
	$result = $stmt->get_result();

	$rows = [];

	while ($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}

	$stmt->close();

	return $rows;
}

function get_book_occasions ($conn, $book_id) {
	$sql = "SELECT occasion_id FROM occ_book WHERE book_id = ?";
	$stmt = $conn->prepare ($sql);

	if (!$stmt) {
		echo "<h2>Search for book themes failed</h2><br>" . $conn->error . "</br>";
	}

	$stmt->bind_param("i", $book_id);
	$stmt->execute();
	$result = $stmt->get_result();

	$rows = [];

	while ($row = $result->fetch_assoc()) {
		$rows[] = (int) $row['occasion_id'];
	}

	$stmt->close();

	return $rows;
}

function add_book ($conn, $title, $author, $box_id) {
	$stmt = $conn->prepare("INSERT INTO book (title, author, box_id) VALUES (?,?,?)");
	$stmt->bind_param("ssi", $title, $author, $box_id);
	if ($stmt->execute()) {
		$insert_id = $conn->insert_id;
		$stmt->close();
		return [0, $insert_id];
	} else {
		$stmt->close();
		return [1, $stmt->error];
	}
}

function update_book ($conn, $id, $updates) {
	$sql = "UPDATE book SET ";
	$next = '';
	foreach ($updates as $key => $value) {
		if (is_array($key)) { continue; }

		$sql .= $next . ' ' . $key . '=\'' . $value . '\'';
		$next  = ',';
	}
	$sql .= ' WHERE id = ' . $id;
	//echo "<br>$sql";
	
	if ($conn->query ($sql)) {
		return 0;
	} else {
		return 1;
	}
}

function delete_book ($conn, $id) {
	$stmt = $conn->prepare ("DELETE FROM book WHERE id = ?");
	$stmt->bind_param ("i", $id);

	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
}

function add_book_occasions ($conn, $book_id, $occasions) {
	$stmt = $conn->prepare("INSERT INTO occ_book (book_id, occasion_id) VALUES (?, ?)");
	if (!$stmt) {
		echo "<tr><th>DB prepare failed</th><td>" . $conn->error . "</td></tr></table>";
		exit;
 	}

	$success = [];
	$failed = [];
	foreach ($occasions as $occasion) {
		$occasion = (int)$occasion; // ensure integer
		// echo "<tr><th>inserting</th><td>" . htmlspecialchars($occasion) . "</td></tr>";

		$stmt->bind_param("ii", $book_id, $occasion);

		if ($stmt->execute()) {
			$success [] = $occasion;
		} else {
			$failed [] = $occasion;
		}
	}

	$stmt->close();
	$s = implode(',', $success);
	$f = implode(',', $failed);
	return [$s, $f];
}

function get_boxes ($conn) {
	$sql = "SELECT * FROM box";
	$result = $conn->query($sql);

	$boxes = [];
	while ($row = $result->fetch_assoc()) {
		$id = (int)$row['id'];
		$boxes[$id] = $row['label'];
	}
	return $boxes;
}

function get_boxes_with_counts ($conn) {
	$sql = "select b.id, b.label as BOX, count(bo.id) as Book_Count from box b left join book bo on b.id = bo.box_id group by b.id order by b.label";
	$result = $conn->query($sql);

	$boxes = [];
	while ($row = $result->fetch_assoc()) {
		$id = (int)$row['id'];
		$boxes[$id] = [ $row['BOX'], (int)$row['Book_Count'] ];
	}
	return $boxes;
}

function get_box ($conn, $id) {
	$stmt = $conn->prepare ("SELECT label FROM box WHERE id = ?");
	$stmt->bind_param ('i', $id);
	$stmt->execute();
	$res = $stmt->get_result();

	$row = $res->fetch_assoc();
	return $row['label'];
}

function add_box ($conn, $label) {
	$stmt = $conn->prepare("INSERT INTO box (label) VALUES (?)");
	$stmt->bind_param("s", $label);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
}

function modify_box ($conn, $label, $id) {
	$stmt = $conn->prepare("UPDATE box SET label=? WHERE id=?");
	$stmt->bind_param("si", $label, $id);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
	$stmt->close();
}

function delete_box ($conn, $id) {
	$stmt = $conn->prepare("DELETE FROM box WHERE id=?");
	$stmt->bind_param("i", $id);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
	$stmt->close();
}

function get_occasions ($conn) {
	$sql = "SELECT * FROM occasion";
	$result = $conn->query($sql);
	$occ = [];

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
 		$occ[$id] = $row['label'];
 	}
	return $occ;
}

function get_occasions_with_counts ($conn) {
	$sql = "SELECT o.id, o.label as Theme, COUNT(ob.occasion_id) as Theme_Count FROM occasion o
		LEFT JOIN occ_book ob ON o.id = ob.occasion_id group by o.id, o.label 
		order by Theme";

	$result = $conn->query($sql);
	$occ = [];

	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
 		$occ[$id] = [ $row['Theme'], $row['Theme_Count'] ];
 	}
	return $occ;
}
function update_occasions ($conn, $bookId, $occasions) {
	if ($occasions) {
		$placeholders = implode(',', array_fill(0, count($occasions), '?')) ?? '';
		$sql = "DELETE FROM occ_book 
			WHERE book_id = ? 
			AND occasion_id NOT IN ($placeholders)";

		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i" . str_repeat("i", count($occasions)), $bookId, ...$occasions);
		$stmt->execute();
	} else {
		$stmt = $conn->prepare("DELETE FROM occ_book WHERE book_id = ?");
		$stmt->bind_param("i", $bookId);
		$stmt->execute();
	}

	$stmt = $conn->prepare("INSERT IGNORE INTO occ_book (book_id, occasion_id) VALUES (?, ?)");

	foreach ($occasions as $occ_id) {
		$stmt->bind_param("ii", $bookId, $occ_id);
		$stmt->execute();
	}
}

function add_occasion ($conn, $label) {
	$stmt = $conn->prepare("INSERT INTO occasion (label) VALUES (?)");
	$stmt->bind_param("s", $label);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
}

function modify_occasion ($conn, $label, $id) {
	$stmt = $conn->prepare("UPDATE occasion SET label=? WHERE id=?");
	$stmt->bind_param("si", $label, $id);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
	$stmt->close();
}

function delete_occasion ($conn, $id) {
	$stmt = $conn->prepare("DELETE FROM occasion WHERE id=?");
	$stmt->bind_param("i", $id);
	if ($stmt->execute()) {
		return 0;
	} else {
		return $stmt->error;
	}
	$stmt->close();
}

?>
