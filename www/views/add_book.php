<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (file_exists("book_lib.php")) {
	require_once "book_lib.php";
} else {
	require_once "../book_lib.php";
}

if (file_exists("vars.php")) {
	include "vars.php";
} else {
	include "../vars.php";
}

$conn = db_connect();

$boxes = get_boxes($conn);
$occasions = get_occasions($conn);

if (!$boxes) {
    echo "<h2>Boxes not found</h2>";
    exit;
}
if (!$occasions) {
    echo "<h2>Themes not found... continuing without them.</h2>";
}
?>

<h3>Add Book</h3>
<form method="post" action="api/process_book.php" class="ajax-form">
    <table class="add-book-table">
        <tr>
            <th><label for="Title">Title</label></th>
            <td><input type="text" name="title" id="Title" required></td>
        </tr>

        <tr>
            <th><label for="Author">Author</label></th>
            <td><input type="text" name="author" id="Author"></td>
        </tr>

        <tr>
            <th><label for="Box">Box</label></th>
            <td>
                <select id="Box" name="box" required>
                    <option value="" selected>Select Box</option>
                    <?php foreach ($boxes as $id => $label): ?>
                        <option value="<?= htmlspecialchars($id) ?>">
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th>Themes</th>
            <td>
                <div class="occasions-grid">
                    <?php foreach ($occasions as $id => $label): ?>
                        <label>
                            <input type="checkbox" name="occasions[]" value="<?= htmlspecialchars($id) ?>">
                            <?= htmlspecialchars($label) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type="reset" value="Clear">
                <input class='add-book' type="submit" value="Add Book">
            </td>
        </tr>
    </table>
</form>

<?php db_disconnect ($conn); ?>

