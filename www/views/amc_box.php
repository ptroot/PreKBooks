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
?>

<h3>Add Box</h3>
<form method="post" action="api/add_box.php" class="ajax-form">
	<table class="amc-table">
        <tr>
            <th>Box Name</th>
            <td><input type='text' name='label'></td>
            <td>
                <input type='reset' value='Clear'>
                <input type='submit' value='Add Box'>
            </td>
        </tr>
    </table>
</form>

<h4>Modify/Delete Boxes</h4>
<table class="amc-table">
    <tr>
        <th>Box Name</th>
		<th>Book Count</th>
        <th>Actions</th>
    </tr>

<?php
$boxes = get_boxes_with_counts ($conn);
$book_count = 0;
foreach ($boxes as $id => $array) {
    echo "<tr>";
    echo "<td>
            <input type='text' name='label' value='{$array[0]}' data-id='{$id}' class='box-label-input'>
          </td>";
	echo "<td>"  . htmlspecialchars ((int)$array[1]) . "</td>";
	$book_count += (int)$array[1];
    echo "<td>
            <button class='update-box' data-id='{$id}'>Update</button>
            <button class='delete-box' data-id='{$id}'>Delete</button>
          </td>";
    echo "</tr>";
}

echo "<tr><th>Book Total</th><td>" . htmlspecialchars ($book_count) . "</td></tr>";
?>

</table>

<?php db_disconnect ($conn); ?>
