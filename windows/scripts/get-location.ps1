# Set default installation location
$InsLoc = "C:\PrekBooks"
$ans = Read-Host "Installing web and database servers into $InsLoc. Is that correct? (y/n default yes)"

# Get alternative location choice from user
if ($ans -match "^(n|no)$") {
	$HomeData = "$HOME\AppData\Roaming\PrekBooks\"
	$ans = Read-Host "Would you like to install in your accounts Application space - $HomeData (y/n  default yes)"
	if ($ans -match "^(y|yes)$|^$") {
		$InsLoc = $HomeData
	} else {
		$InsLoc = Read-Host "Enter full path including drive letter of location to install"
	}
}

# Test to ensure that user can write to the chosen location
if (-not (Test-Path -Path $InsLoc -PathType Container)) {
	Write-Host "Attempting to create $InsLoc folder"
	New-Item -ItemType Directory -Path $InsLoc | Out-Null
} else {
	New-Item -Path "$InsLoc\test-file.txt" | Out-Null
	if (-not (Test-Path -Path "$InsLoc\test-file.txt")) {
		Write-Host "The folder exists, but you do not seem to have permissions to write in it. Cannot continue."
		return $false
	} else {
		Remove-Item -Path "$InsLoc\test-file.txt" | Out-Null
	}
}

if (-not (Test-Path -Path $InsLoc -PathType Container)) {
	Write-Host "Could not create the folder $InsLoc. Either it is a file, the drive letter does not exist, or you do not have permission to create it. Cannot continue."
	return $false
}

return $InsLoc
