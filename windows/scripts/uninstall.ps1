
$InsLoc = "C:\PrekBooks"

if (-not (Test-Path -Path $InsLoc -PathType container)) {
	Write-Host "$InsLoc either does not exist or is not a directory"
	exit 1
} 

if ($PWD.Path.ToLower() -ne $InsLoc.ToLower()) {
	Write-Host "The current directory is not $InsLoc"
	$ans = Read-Host "Are you sure you want to remove everything in $InsLoc (y/n default NO)"
	if (-not ($ans -match "^(y|yes)"))	{
		exit 1
	}
}

Write-Host "Removing application from $InsLoc"
start-sleep -Seconds 3

cd $InsLoc
if ($PWD.Path.ToLower() -ne $InsLoc.ToLower()) {
	Write-Host "Cannot find $InsLoc. Cannot continue."
	exit 1
}

$InsLen = $InsLoc.length

$finalDump = $false
if ((Get-Process -Name mysqld -ErrorAction SilentlyContinue) -or 
	(Get-Process -Name mariadbd -ErrorAction SilentlyContinue)) {
	$MDUMP = $InsLoc + "\apps\mysql\bin\mariadb-dump.exe"
	if (Test-Path -Path "$MDUMP") {
		Write-Host "Doing a final backup of the prekbooks database"
		$lineText = (Select-String -Path "apps\admin\index.php" -Pattern "root" | Select-Object -First 1).Line
		if ($lineText) {
			$parts = $lineText -split '\s+' # Split by whitespace
			$pwd = $($parts[5]) -replace '''', '' -replace ',', ''    # remove 
			& $MDUMP -u root --password="$pwd" prekbooks | Set-Content "$InsLoc\final-prekbooks-dump.sql"
		} else {
			Write-Line "Please provide the root password to backup the database before removal"
			& $MDUMP -u root -p prekbooks | Set-Content "$InsLoc\final-prekbooks-dump.sql"
		}
		$finalDump = $true
	} else {
		Write-Host "$MDUMP not found. Cannot create a final dump of the prekbooks database"
	}
} else {
	Write-Host "mysqld was not running. Cannot create a final dump of the prekbooks database"
	
Write-Host "Stopping XAMPP-Lite processes"

$processNames = @("httpd", "mariadbd", "mysqld", "XL-Control-Panel.x64")

foreach ($name in $processNames) {
	# Check if the process is running
	$process = Get-Process -Name $name -ErrorAction SilentlyContinue
   
	if ($process) {
		$ppath = $process.Path.ToLower().SubString(0,$InsLen)
		if ($ppath -eq $InsLoc.ToLower()) {
			Write-Host "Stopping $name"
			Stop-Process $process -Force
		} else {
			Write-Host "$name not started from $InsLoc. Not stopping"
		}
	}
}

if (Test-Path -Path "$Home\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup\$ControlPanel.lnk") {
	Remove-Item -Path "$Home\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup\$ControlPanel.lnk" -Force |Out-Null
}

if (Test-Path -Path "www") {
	$ans = Read-Host "Should the www directory structure be removed? (y/n default n)"
	if ($ans -match "^(y|yes)$") {
		attrib -s -h -r ".\www\index.php"
		Remove-Item -Path www -Recurse
	}
}

if (Test-Path -Path "apps") {
	Get-ChildItem -Path apps |ForEach-Object {
		$Dir = $_.FullName
		Write-Host "Removing $Dir"
		Remove-Item -Path $Dir -Recurse -Force
	}

	Write-Host "Removing $InsLoc\apps"
	Remove-Item -Path apps
}

if (Test-Path -Path "tmp") {
	Write-Host "Removing $InsLoc\tmp"
	Remove-Item -Path tmp -Recurse -Force
}

if (Test-Path -Path "LICENSE") {
	Remove-Item -Path LICENSE -Recurse -Force
}

Remove-Item -Path "XL-Control*"

if ($finalDump -eq $true) {
	Write-Host
	Write-Host "The final backup (dump) of the prekbooks database can be found in $InsLoc\final-prekbooks-dump.sql"
	Write-Host "This can be imported into another installation of the maria (mysql) database."
}

Write-Host
