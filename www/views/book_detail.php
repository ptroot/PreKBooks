<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

$conn = db_connect();

if (!isset($_GET['id'])) {
	echo "<p>No book selected</p>";
	exit;
//	$bookId = 354;			// debugging
} else {
	$bookId = (int)$_GET['id'];
}

$book = get_book($conn, $bookId);
if (!$book) {
	echo "<p>Book not found.</p>";
	exit;
}
//var_dump($book);

$occ = get_occasions($conn);
//var_dump($occ);

$boxes = get_boxes($conn);
//var_dump($boxes);
?>

<h3>Book Details</h3>
<form method='post' action='api/update_occ_book.php' class='ajax-form'>
<table class="add-book-table">

<?php
echo "<input type='hidden' name='book_id' value='$bookId'>";
foreach ($book as $field => $value) {
	$lowerKey = strtolower($field);
	
	if (str_ends_with($lowerKey, 'id')) continue;

	echo "<tr>";
	echo "<th class='label'><strong>" . htmlspecialchars($field) . ":</strong></th>";
	echo "<td class='value'>";

	if ("$field" === 'Box') {
		echo "<select id='Box' name='box'>";
		foreach ($boxes as $id => $label) {
			if ($label == $value) {
				echo "<option value='" . htmlspecialchars($id) . "' selected>";
			} else {
				echo "<option value='" . htmlspecialchars($id) . "'>";
			}
			echo htmlspecialchars("$label") . "</option>";
		}
		echo "</select>";
	} else {
		echo "<input type='text' name='" .  htmlspecialchars($field) . "' value='" . htmlspecialchars($value) . "'>";
	}

	echo "</td></tr>";
}

$occasions = get_book_occasions ($conn, $bookId); 
//var_dump($occasions);
?>

<tr>
	<th><strong>Themes</strong></th>
	<td><div class="occasions-grid">

	<?php foreach ($occ as $id => $label): ?> 
		<label>
			<input
				type="checkbox"
				name="occasions[]"
				value="<?= htmlspecialchars($id) ?>"
				<?= in_array($id, $occasions ?? []) ? 'checked' : '' ?>
			>	
			<?= htmlspecialchars($label) ?>
		</label>
	<?php endforeach; ?>
	</div></td>
</tr>
<tr><th></th><td><input class='add-book' type='submit' value='Update'>
<?php echo "<button type='button' class='delete-book' data-id='" . htmlspecialchars($bookId) . "'>Delete</button>"; ?>
</td></tr>
</table>
</form>

<?php db_disconnect ($conn); ?>

