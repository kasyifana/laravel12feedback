# Script to enable required PHP extensions for Laravel 12
# This script modifies the php.ini file to enable necessary extensions

$phpIniPath = "C:\xampp\php\php.ini"

# Check if php.ini exists
if (-not (Test-Path $phpIniPath)) {
    Write-Error "php.ini not found at $phpIniPath"
    exit 1
}

# Read the php.ini file
$phpIni = Get-Content -Path $phpIniPath

# Function to uncomment extension lines or add if missing
function Enable-Extension {
    param (
        [string]$extension,
        [string[]]$content
    )
    
    $pattern = "^;extension=$extension"
    $activePattern = "^extension=$extension"
    $newContent = @()
    $found = $false
    $alreadyEnabled = $false
    
    foreach ($line in $content) {
        if ($line -match $pattern) {
            $newContent += $line.Replace(";extension=$extension", "extension=$extension")
            $found = $true
            Write-Host "Enabled $extension extension"
        } elseif ($line -match $activePattern) {
            $newContent += $line
            $alreadyEnabled = $true
            Write-Host "$extension extension is already enabled"
        } else {
            $newContent += $line
        }
    }
    
    if (-not $found -and -not $alreadyEnabled) {
        Write-Host "$extension extension not found, adding it"
        # Add the extension to the extension section
        $extensionSection = $false
        $resultContent = @()
        
        foreach ($line in $newContent) {
            $resultContent += $line
            
            if ($line -match "^\[extension]" -or $line -match "^; Dynamic Extensions ;") {
                $extensionSection = $true
                $resultContent += "extension=$extension"
                Write-Host "Added $extension extension"
            }
        }
        
        if (-not $extensionSection) {
            # If no extension section found, just append it at the end
            $resultContent += "extension=$extension"
            Write-Host "Added $extension extension at the end"
        }
        
        return $resultContent
    }
    
    return $newContent
}

# Enable required extensions for Laravel
$phpIni = Enable-Extension -extension "pdo_mysql" -content $phpIni
$phpIni = Enable-Extension -extension "mysqli" -content $phpIni
$phpIni = Enable-Extension -extension "fileinfo" -content $phpIni
$phpIni = Enable-Extension -extension "openssl" -content $phpIni
$phpIni = Enable-Extension -extension "mbstring" -content $phpIni

# Save the modified php.ini file
try {
    $phpIni | Set-Content -Path $phpIniPath -Force
    Write-Host "php.ini updated successfully. Please restart your Apache server."
} catch {
    Write-Error "Failed to update php.ini: $_"
    exit 1
}

# Additional help for manually enabling extensions
Write-Host "`n======== MANUAL INSTRUCTIONS ========="
Write-Host "If the script didn't fix the issue, follow these steps:"
Write-Host "1. Open php.ini file in C:\xampp\php\"
Write-Host "2. Search for 'extension=pdo_mysql' (without quotes)"
Write-Host "3. Remove the semicolon (;) at the beginning of the line to uncomment it"
Write-Host "4. Do the same for extension=mysqli"
Write-Host "5. Save the file and restart Apache"
Write-Host "========================================"