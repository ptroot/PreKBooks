$InsLoc = "C:\PrekBooks"
$ControlPanel = "XL-Control-Panel.x64"
$length = $InsLoc.length

$processNames = @($ControlPanel, "httpd", "mariadbd", "mysqld")

foreach ($name in $processNames) {
	Write-Host "$name"
    # Check if the process is running
    $process = Get-Process -Name $name -ErrorAction SilentlyContinue
    
    if ($process) {
		$ppath = $process.Path.ToLower().SubString(0,$length)
		
		#write-host $ppath
		#write-host $InsLoc.ToLower()
		if ($ppath.ToLower() -eq $InsLoc.ToLower()) {
			Write-Host "got one - $ppath"
		}
    }
}
