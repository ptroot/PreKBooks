<?php
	function read_csv ($file) {
		$tree = [];
		$handle = fopen ($file, "r");
		if ($handle) {
			while (($line = fgetcsv ($handle, 0, ",", '"', "\\")) !== false) {
				$tree[$line[0]][$line[2]] = $line[1];
			}

			fclose ($handle);
		}

		return $tree;
	}

	function backup_csv ($file) {
		$date   = date("Ymd");
		$backupdir = ".backup";
		
		if (!is_dir("$backupdir")) {
			mkdir ($backupdir, 0775, true);
		}

		// The first backup of a day is its own file
		$dest = "{$backupdir}/{$file}-{$date}";
		if (file_exists ("$dest")) {
			// after the first, we'll just have the latest backup
			$dest = "{$backupdir}/{$file}-{$date}-tmp";
		}

		if (copy ($file, $dest)) {
			return true;
		} else {
			return false;
		}		
	}
	
	function writing_csv ($file, $tree) {
		if (file_exists($file) && !is_writable($file)) {
			unlink($file);
		}

		$handle = fopen ("{$file}", "w");
		if (!$handle) {
			print_r(error_get_last());
		}
		if ($handle) {
			foreach ($tree as $group => $urls) {
				foreach ($urls as $label => $http) {
					$data = [$group, $http, $label];
					fputcsv($handle, $data, ",", '"', "\\");
				}
			}
			if (fclose ($handle)) {
				return true;
			} else {
				return false;
			}
		} else {
			echo "<h2>Failed to open " . htmlspecialchars($file) . " for writing</h2>";
			return false;
		}
	}
?>
