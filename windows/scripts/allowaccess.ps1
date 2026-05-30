function AllowAccess ()
{
	param (
		[Parameter(Mandatory=$true)]
		[string]$file
	)
	
	# Give everyone access to write file
	$acl = Get-Acl -Path $file
	$rule = New-Object System.Security.AccessControl.FileSystemAccessRule("Everyone", "Modify", "Allow")
	$acl.SetAccessRule($rule)
	Set-Acl -Path $file -AclObject $acl   
}

AllowAccess "$HOME\AppData\Roaming\Prekbooks\xampp\xampp-control.ini"
New-Item -Path "$HOME\AppData\Roaming\Prekbooks\xampp\phpMyAdmin\config.inc" | Out-Null
AllowAccess "$HOME\AppData\Roaming\Prekbooks\xampp\phpMyAdmin\config.inc"
AllowAccess "C:\Users\ptroo\AppData\Roaming\Prekbooks\xampp\htdocs\prek\book_lib.php"