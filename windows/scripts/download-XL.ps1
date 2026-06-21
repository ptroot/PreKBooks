# Get the installation media, if it doesn't already exist in the current folder

# These first 3 variables are linked together, and are changed when upgrading the XAMPP-Lite package 
$XAMPP_INSTALLER="./XAMPP-Lite-8.5.5.1-x64-php-man-tr-portable.7z"
$XAMPP_VERSION="8.5/8.5.5"
$XAMPP_SHA1_CS="84383a7bcdc84cf1fb82eea589ff75fe1593d51f"
$XAMPP_URL="https://sourceforge.net/projects/xampplite/files/${XAMPP_VERSION}/x64/php-man-tr/$XAMPP_INSTALLER" 

# The SHA1 value for the download file is found in the information icon for the file on the website.
function sha1sum ()
{
	param (
		[Parameter(Mandatory=$true)]
		[string]$file,
		[string]$expected
	)
		
	$res = (Get-FileHash $file -Algorithm SHA1).Hash
	if ($res -eq $expected) {
		Write-Host "$file checksum is verified"
		return $true
	} else {
		Write-Host "$file checksum failed"
		return $false
	}
}

# See if the file is already downloaded
if ( Test-Path "$XAMPP_INSTALLER" ) {
	# Test the SHA1 value to confirm the file was correctly downloaded
	$ret = sha1sum $XAMPP_INSTALLER $XAMPP_SHA1_CS
	if ($ret -eq $true) {
		$dl = $false
	} else {
		Write-Host "Downloading $XAMPP_INSTALLER"
		Remove-Item $XAMPP_INSTALLER
		$dl = $true
	}
} else {
	Write-Host "Downloading $XAMPP_INSTALLER"
	$dl = $true
}

if ($dl -eq $true) {
	# Download the installer (compressed archive) to current folder
	curl.exe -L $XAMPP_URL -o $XAMPP_INSTALLER

	# Test to make sure the downloaded file is complete
	$ret = sha1sum $XAMPP_INSTALLER $XAMPP_SHA1_CS
	if ($ret -eq $false) {
		Write-Host "$XAMPP_INSTALLER does not exist or failed checksuum. Cannot contine"
		return $false
	}
}

# Return the file name
return $XAMPP_INSTALLER
