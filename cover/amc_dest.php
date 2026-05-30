<?php
	
require_once 'tree_lib.php';
$file = 'tree.csv';
$tree = read_csv ($file);

$fGroup = $_POST['fGroup'] ?? null;

?>

<h4>Filter by Group</h4>
<form method='post' action='amc_dest.php' class='ajax-form'>
   <select name='Group' data-id='fGroup' class='fgroup'> 
	  <?php
		  if ($fGroup === '') {
			  echo "<option value='' selected>Select Group</option>";
		  } else {
			  echo "<option value=''>Select Group</option>";
		  }
		  foreach ($tree as $group => $urls) {
			  if ($group === $fGroup) {
				  echo "<option value='" . htmlspecialchars ($group) . "' selected>";
			  } else {
				  echo "<option value='" . htmlspecialchars ($group) . "'>";
			  }
			  echo htmlspecialchars ($group) . "</option>";
		  }
		  echo "</select>";
		  echo "<input type='submit' class='group-filter' data-id='fGroup' value='Filter'>";
	  ?>
<h3>Add/Move/Delete Destinations</h3>
<table class='amc-table'>
	<tr>
		<th>Tree Group</th>
		<th>Label</th>
		<th>URL</th>
		<th>Action</th>
	</tr>

	<tr>
		<td><input type='txt' name='group' data-id='0' class='group-input'></td>
		<td><input type='txt' name='label' data-id='0' class='label-input'></td>
		<td><input type='txt' name='url' data-id='0' class='url-input'></td>
		<td><button class='add-url' data-id='0'>Add</button></td>
	</tr>
	</tr>
	<?php
		$idx = 1;
		foreach ($tree as $group => $urls) {
			if ("$fGroup" !== '') {
				if ($fGroup !== $group) {
					continue;
				}
			}
			foreach ($urls as $label => $url) {
				echo "<tr><td><input type='txt' name='group' value='" . htmlspecialchars($group) . "'"; 
				echo "data-id='" . htmlspecialchars($idx) . "' class='group-input'></td>";

				echo "<td><input type='txt' name='label' value='" . htmlspecialchars($label) . "'"; 
				echo "data-id='" . htmlspecialchars($idx) . "' class='label-input'></td>";

				echo "<td><input type='txt' name='url' value='" . htmlspecialchars($url) . "'"; 
				echo "data-id='" . htmlspecialchars($idx) . "' class='url-input'></td>";

  				echo "<td><button class='update-url' data-id='" . htmlspecialchars($idx) . "'>Update</button>";
    			echo "<button class='delete-url' data-id='" . htmlspecialchars($idx) . "'>Delete</button></td></tr>";

				$idx++;
			}
		}
	?>
</table>


