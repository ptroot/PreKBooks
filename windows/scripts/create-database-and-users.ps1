# Set the root password
# Create an application user and optional additional user to access the database
# Create the database and populate the schema
# Update admin, phpMyAdmin, Adminer, and backup pages to include the root password. 

if (-not "$env:InsLoc") {
	Write-Host "\$env:InsLoc not set. It needs to be the top level directory to install the application"
	Write-Host "Cannot continue"
	return false
}

$env:Path += ";$InsLoc\apps\mysql\bin;$InsLoc\apps\php"
$env:XAMPP_LITE_ROOT="$env:InsLoc"

# set password for root
echo "WARNING: Currently, there is no password for the main account into the database."
Write-Host "You need to remember the following passwords:"
Write-Host "Please provide the following passwords using regular password guidelines"
$rpw = Read-Host -AsSecureString  "Enter password for the main database account"
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($rpw)
$urpw = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)

& mysql.exe -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '$urpw'"
& mysql.exe -u root --password="$urpw" -e "ALTER USER 'root'@'127.0.0.1' IDENTIFIED BY '$urpw'"
& mysql.exe -u root --password="$urpw" -e "ALTER USER 'root'@'::1' IDENTIFIED BY '$urpw'"
& mysql.exe -u root --password="$urpw" -e "FLUSH PRIVILEGES"

# run create-prek.sql
& mysql.exe -u root --password="$urpw" -e "source ../mariadb/create-prek.sql"

# Create a mariadb user for use by the webpage
$upw = Read-Host -AsSecureString  "Enter password for the database account controlling the book database"
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($upw)
$uupw = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)

$upw| ConvertFrom-SecureString | Out-File "$Env:InsLoc\tmp\secure_pass.txt"

& mysql.exe -u root --password="$urpw" -e "CREATE USER 'prek-app'@'%' IDENTIFIED BY '$uupw'"
$uupw = "";
& mysql.exe -u root --password="$urpw" -e "GRANT SELECT, INSERT, UPDATE, DELETE ON prekbooks.* TO 'prek-app'@'%';"

# Update phpMyAdmin to use the new password
$file = $env:InsLoc + "\apps\admin\phpMyAdmin\config.inc.php"
Add-Content -Path $file -Value "`$cfg['Servers'][`$i]['password'] = '$urpw';"

# Update admin page
$file = $env:InsLoc + "\apps\admin\index.php"
$content = Get-Content $file -Raw
$content = $content -replace `
	"('root'\s*,\s*')[^']*(')",
	"`$1$urpw`$2"
Set-Content $file $content

# Update adminer page
$file = $env:InsLoc + "\apps\admin\adminer\index.php"
(Get-Content $file) -replace `
"('password'\s*=>\s*')[^']*(',)",
"`$1$urpw`$2" | Set-Content $file

# prompt for adding another user (optional)
$ans = Read-Host "Do you want an additional user with permissions into the book database (y/n default no)"

if ($ans -match "^(y|yes)$") {
	$user = Read-Host "Enter username"
	$npw = Read-Host -AsSecureString  "Enter password for the $user account"
	$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($npw)
	$unpw = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)

	& mysql.exe -u root --password="$urpw" -e "CREATE USER '$user'@'%' IDENTIFIED BY '$unpw'"
	$unpw = "";
	& mysql.exe -u root --password="$urpw" -e "GRANT SELECT, INSERT, UPDATE, DELETE ON prekbooks.* TO '$user'@'%'"
} else {
	Write-Host "No additional user will be created"
}

& mysql.exe -u root --password="$urpw" -e "FLUSH PRIVILEGES"

return $upw