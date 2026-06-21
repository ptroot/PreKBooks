<h3>Process URL</h3>
<?php

$id = $_POST['id'];
$group = $_POST ["group"];
$label = $_POST ["label"];
$url = $_POST ["url"];
$action = $_POST ["action"];


require_once 'tree_lib.php';

	
$file = 'tree.csv';

$tree = read_csv ($file);

$needSave = false;

if ($action === 'add') {
	$tree [$group][$label] = $url;
	$needSave = true;
}

if ($action === 'update') {
	$current = $tree[$group][$label] ?? '';

	if ($current !== '' && $current !== $url) {
		echo "<br>Updating url for $label";
		$tree [$group][$label] = $url;
		$needSave = true;
	} else {
		foreach	($tree[$group] as $tlabel => $turl) {
			if ($turl === $url) {
				// Found the url in the right group
				if ($tlabel !== $label) {
					echo "<br>Updating the label";
					unset ($tree [$group][$tlabel]);
					$tree [$group][$label] = $url;
					$needSave = true;
					break;
				}
			}
		}
		if (!$needSave) {
			// Didn't find the url or the label changed
			// So either it's the group or the group and label
			foreach ($tree as $tgroup => $turls) {
				//echo "<br>element " . htmlspecialchars ($tgroup) . ' ' . htmlspecialchars ($turls[$label]);
				if ($turls [$label] === $url) {
					// group changed
					echo "<br>Updating the group";
					unset ($tree [$tgroup][$label]);
					$tree [$group][$label] = $url;
					$needSave = true;
					break;
				} else {
					//echo "<br> looping labels";
					foreach ($turls as $tlabel => $turl) {
						if ($turl === $url) {
							// both group and label changed
							echo "<br>Updating the group and label";
							unset ($tree [$tgroup][$tlabel]);
							$tree [$group][$label] = $url;
							$needSave = true;
							break;
						} else if ($tlabel === $label) {
							// both group and url changed
							echo "<br>Updating the group and url";
							unset ($tree [$tgroup][$label]);
							$tree [$group][$label] = $url;
							echo "<br>update made";
							$needSave = true;
							break;
						}
					}
				}

				if ($needSave) {
					break;
				}
			}
		}
		if (!$needSave) {
			// Could be changing group and URL
			echo "<br>Could not find anything to change";
		}
	}
}

if ($action === 'delete') {
	if ($tree [$group][$label] !== $url) {
		echo "<h3>ERROR: " . htmlspecialchars ($url) . "not found where expected</h3>";
	} else {
		unset($tree [$group][$label]);
		$needSave = true;
	}
}

if ($needSave) {
	echo "<h4>Backing up and saving " . htmlspecialchars ($file) . "</h4>";
	if (!backup_csv ($file)) {
		echo '<h3>Backup failure for ' . htmlspecialchars ($file) . "</h3>";
	}

	if (!writing_csv ($file, $tree)) {  
		echo '<h3>Failure to write ' . htmlspecialchars ($file) . '</h3>';
	}
}

//echo "<ul>";
//foreach ($tree as $group => $urls) {
//   echo "<li>" . htmlspecialchars ($group);
//   echo "<ul>";
//   foreach ($urls as $label => $url) {
//	  echo "<li>" . htmlspecialchars ($label) . "<ul><li>" . htmlspecialchars ($url) . "</li></ul>";
//   }
//   echo "</ul>";
//}
//echo "</ul>";

?>
