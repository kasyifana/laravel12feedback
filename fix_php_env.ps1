# This script will manually create a php.ini file for PHP to use when running from the command line
# It ensures the required extensions are loaded regardless of which php.ini file is being used

# Create a simple PHP script to generate information
$phpInfoScript = @'
<?php
echo "Original PHP INI: " . php_ini_loaded_file() . "\n";
echo "Extensions directory: " . ini_get('extension_dir') . "\n";
echo "PDO MySQL loaded: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "\n";
'@

# Save the script to a temporary file
$tempFile = [System.IO.Path]::Combine($env:TEMP, "phpinfo.php")
Set-Content -Path $tempFile -Value $phpInfoScript

# If we're on the command line, create a custom ini file
# Run the PHP script to get the info
$phpInfo = php -f $tempFile
Write-Host $phpInfo

# Get the extension directory
$extDir = ($phpInfo -split "`n" | Where-Object { $_ -like "*Extensions directory*" }).Split(":")[1].Trim()

Write-Host "`nCreating a custom php.ini file"

# Create a custom php.ini file in the current directory
$customPhpIni = @"
[PHP]
extension_dir = "$extDir"
extension=openssl
extension=pdo_mysql
extension=mysqli
extension=fileinfo
extension=mbstring
extension=curl
"@

Set-Content -Path ".\php.ini" -Value $customPhpIni
Write-Host "Custom php.ini file created in the current directory."
Write-Host "Try running 'php artisan migrate' again."
