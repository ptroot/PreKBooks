#
#		PreK Books installer for Windows
#
#	This script will do a complete install of a web server, database, and all the files for the Preschool Book catalog.  
#	It will:
#			Download the XAMPP package, if needed, and verify the file matches
#			Install the XAMPP package.  This provides the Maria database, Apache web server, Php web page environment, and Filezilla (not configured)
#			Create the database instance for the book catalog
#			Create database users to access the book catalog
#			Start up the database and web server
#
#	Prompts:
#		While installing, you will be asked to answer a few questions. Yes or no questions will provide a default response. Simply pressing Enter will take the default.
#			Otherwise, enter y, yes, Yes, YES, n, no, No, NO  to answer. 
#

# Set the location to install XAMPP-Lite and the book database/webpages
$InsLoc = & ./scripts/get-location.ps1

if ($InsLoc -eq $false) {
	Write-Host "Invalid install location, or could not write in that folder"
	Write-Host "Cannot continue"

	exit 1
}

$env:InsLoc = $InsLoc

# Download XAMPP-Lite if needed
$XAMPP_INSTALLER = & ./scripts/download-XL.ps1

if (-not (Test-Path -Path $XAMPP_INSTALLER)) {
	Write-Host "Could not download the XAMPP installation media"
	Write-Host "Cannot continue"
	
	exit 1
}

$ret = & ./scripts/extract-XL.ps1 $XAMPP_INSTALLER
if ($ret -eq $true) {
	Write-Host "XAMPP-Lite extracted into $InsLoc"
} else {
	Write-Host "Failed to extract XAMPP-Lite. Cannot continue"
	exit 1
}

# Add XAMPP Control Panel to startup items
$AppControl="XL-Control-Panel.x64"

$WshShell = New-Object -ComObject WScript.Shell
$Shortcut = $WshShell.CreateShortcut("$Home\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup\$AppControl.lnk")
$Shortcut.TargetPath = "$InsLoc/$AppControl.exe"
$Shortcut.WorkingDirectory = "$InsLoc"
$Shortcut.Save()

$env:Path += ";$InsLoc\apps\mysql\bin;$InsLoc\apps\php"

# The XAMPP_LITE_ROOT is the Installation location
$env:XAMPP_LITE_ROOT="$InsLoc"
$ControlPanel = "XL-Control-Panel.x64"
$ControlPanelPath = $env:XAMPP_LITE_ROOT + '\' + $ControlPanel + '.exe'

# Start the XAMPP process
Write-Host "Starting XAMPP Control Panel. You need do nothing in it at this time."
Write-Host "Just minimize it and continue in this window"
sleep -Seconds 5
start-Process "$ControlPanelPath"
sleep -Seconds 2

$upw = & ./scripts/create-database-and-users.ps1

# Restart the XAMPP process
Write-Host
Write-Host  "The database is installed."
Write-Host
Write-Host "Restarting the Xampp Control panel now."
Write-Host "Again, just minimize it and continue in this window."

sleep -sleep 5
Get-Process $ControlPanel -ErrorAction SilentlyContinue |Stop-Process -Force
sleep -sleep 2
start-Process "$ControlPanelPath"

& ./scripts/populate-www.ps1 $upw
	
# Note phpMyAdmin could be used for running bulk
# provide examples for adding in bulk and commandline to run
Write-Host "The file 'add-data.sql' found in the mariadb folder contains examples of bulk entering of books, boxes, and themes to the database."
Write-Host "The commands found in it can be run using the mariadb.exe (mysql.exe) command or with the phpMyAdmin interface into the prekbooks database." 
Write-Host "However, this is more advanced, requiring a basic understanding of database commands. Using the web pages is recommended"


