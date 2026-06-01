
if (-not (Test-Path -Path "$Env:InsLoc\tmp\secure_pass.txt")) {
	$upw = Read-Host -AsSecureString  "Renter password for the database account controlling the book database. This is the first password you provided"
} else {
	$EncryptedText = Get-Content "$Env:InsLoc\tmp\secure_pass.txt"
	$upw = $EncryptedText | ConvertTo-SecureString
}

# copy web page files into $InsLoc\www\prek
$PREK_WWW = $env:InsLoc + "\www\prek"
$COVER = $Env:InsLoc + "\www\cover"

Write-Host "Populating $PREK_WWW"
Copy-Item -Path "..\www" -Destination "$PREK_WWW" -Recurse
Write-Host "Populating $COVER"
Copy-Item -Path "..\cover" -Destination "$COVER" -Recurse

# Create a random key for encrypting
$Phrase  = hostname
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
$enc = & php ./scripts/encrypt.php "$uupw" "$Phrase"
$uupw = ""

# update vars.php to have
$file = "$PREK_WWW\vars.php"
#AllowAccess $file

$encString = 'enc = "' + $enc + '"'
Write-Host
(Get-Content $file) `
	-replace 'host = ".*"', 'host = "localhost"' `
	-replace 'username = ".*"', 'username = "prek-app"' `
	-replace 'enc = ".*"', $encString |
	Set-Content $file

#update index.php  to go to prek rather than dashboard.
$file = $Env:InsLoc + "\www\index.php"
attrib -s -h -r $file

# Annoyingly, restarting XAMPP-Lite overwrites the index.php to the original. But the backend isn't changed.
# So do a redirect in there.
$file = $Env:InsLoc + "\apps\admin\home\.index.php"

attrib -s -h -r $file
Copy-Item -Path "$file" -Destination "${PREK_WWW}/../apps/admin/home/.index.orig"

#(Get-Content $file) `
#	-replace "include\(__DIR__ \. '/../apps/admin/home/.index.php'\);", "header(`"Location: http://localhost/cover`");" |
#	Set-Content $file

(Get-Content $file) `
	-replace "<head>", "<head><?php header(`"Location: http://localhost/cover`"); ?>" |
	Set-Content $file

