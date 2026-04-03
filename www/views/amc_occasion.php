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

<h3>Add Theme</h3>
<form method="post" action="api/add_occasion.php" class="ajax-form">
	<table class="amc-table">
        <tr>
            <th>Theme</th>
            <td><input type='text' name='label'></td>
            <td>
                <input type='reset' value='Clear'>
                <input type='submit' value='Add Theme'>
            </td>
        </tr>
    </table>
</form>

<h4>Modify/Delete Themes</h4>
<table class="amc-table">
    <tr>
        <th>Theme</th>
        <th>Actions</th>
    </tr>

<?php
$occasions = get_occasions($conn);
foreach ($occasions as $id => $label) {
    echo "<tr>";
    echo "<td>
            <input type='text' name='label' value='{$label}' data-id='{$id}' class='occ-label-input'>
          </td>";
    echo "<td>
            <button class='update-occ' data-id='{$id}'>Update</button>
            <button class='delete-occ' data-id='{$id}'>Delete</button>
          </td>";
    echo "</tr>";
}
?>

</table>

<?php db_disconnect ($conn); ?>
