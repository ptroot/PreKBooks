# Must provide the encrypted application password to this script
param (
	[Parameter(Mandatory=$true)]
	[string]$upw
)

# copy web page files into $InsLoc\www\prek
$PREK_WWW = $env:InsLoc + "\www\prek"
Copy-Item -Path "..\www" -Destination "$PREK_WWW" -Recurse

# Create a random key for encrypting
$Phase   = hostname
$Phrase += Get-Date -Format 'yyMMdd'
$Phrase += $Env:USERNAME

$file = "$PREK_WWW\book_lib.php"

# AllowAccess $file

(Get-Content $file) `
	-replace 'account_details', "$Phrase" |
	Set-Content $file

# encrypt application-user password
$BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($upw)
$uupw = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
$enc = & php ./encrypt.php "$uupw" "$Phrase"
$uupw = ""

# update vars.php to have
$file = "$PREK_WWW\vars.php"
#AllowAccess $file

(Get-Content $file) `
	-replace 'host = ".*"', 'host = "localhost"' `
	-replace 'username = ".*"', 'username = "prek-app"' `
	-replace 'enc = ".*"', 'enc = "' + $enc + '"' |
	Set-Content $file

#update $InsLoc\htdocs\index.php  to go to prek rather than dashboard.
$file = "$InsLoc\htdocs\index.php"
# AllowAccess $file

(Get-Content $file) `
	-replace 'dashboard', 'prek' |
	Set-Content $file

Move-Item -Path "${PREK_WWW}/cover" -Destination "${PREK_WWW}/.."
Move-Item -Path "${PREK_WWW}/index.php" -Destination "${PREK_WWW}/../index.orig"
Move-Item -Path "${PREK_WWW}/cover/index.php" -Destination "${PREK_WWW}/index.php"
