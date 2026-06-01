# Unpack the compressed archive into the Installation location.  

# Must provide the installation media to this script
param (
	[Parameter(Mandatory=$true)]
	[string]$XAMPP_INSTALLER
)

# The installation location must be set in the global environment variable $Env:InsLoc
if (-not "$env:InsLoc") {
	Write-Host "\$env:InsLoc not set. It needs to be the top level directory to install the application"
	Write-Host "Cannot continue"
	return false
}

# Create the installation location if it doesn't exist
if (-not (Test-Path "$InsLoc")) {
	New-Item -Path "$InsLoc" -ItemType Container | Out-Null
	if (-not (Test-Path "$InsLoc")) {
		Write-Host "Could not create $InsLoc. Cannot continue."
		return $false
	}
}

# Extract the media
if (Test-Path "$XAMPP_INSTALLER") {
	Write-Host "Extracting the XAMPP-Lite web and database servers"
	Write-Host "This will take a few minutes (and quite a few if installing on a USB thumb drive) ..."
	tar -xf $XAMPP_INSTALLER -C $InsLoc
	$ret = $?
	
	# The compressed archive starts with the xampp_lite_version but we don't need that for this install
	# The path will need to be adjusted when upgrading.
	if (Test-Path -Path "${env:InsLoc}\xampp_lite_8_5") {
		Move-Item -Path "${InsLoc}\xampp_lite_8_5\*" -Destination "$InsLoc"
		Remove-Item -Path "$InsLoc\xampp_lite_8_5"
	}
	return $ret
} else {
	Write-Host "XAMPP-Lite archive file not found"
	return $false
}
