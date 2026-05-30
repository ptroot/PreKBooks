<?php
	require_once 'tree_lib.php';

	
	$source = 'tree.csv';
	$dest = 'new-tree.csv';
	
	$tree = read_csv ($source);
	if (backup_csv ($source)) {
		echo "looks good";
	}

	if (writing_csv ($dest, $tree)) {
		echo "write looks good";
	} else {
		echo "write failed";
	}
	
?>

