<?php
// Display current PHP info
echo "Checking PHP configuration:\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Loaded extensions:\n";
print_r(get_loaded_extensions());

echo "\n\nChecking for PDO MySQL:\n";
echo "PDO Extension: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "\n";
echo "PDO MySQL Driver: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "\n";
echo "MySQLi Extension: " . (extension_loaded('mysqli') ? 'Yes' : 'No') . "\n";

// Check php.ini locations
echo "\n\nPHP INI Locations:\n";
echo "Loaded php.ini: " . php_ini_loaded_file() . "\n";
echo "Additional .ini files: " . php_ini_scanned_files() . "\n";

// Get current directory of the script
echo "\n\nScript location: " . __DIR__ . "\n";

// Check extension directory
echo "\nExtension directory: " . ini_get('extension_dir') . "\n";

// Check if extensions exist physically
$extDir = ini_get('extension_dir');
echo "\nChecking for extensions in directory:\n";
echo "PDO MySQL extension file exists: " . (file_exists($extDir . '/php_pdo_mysql.dll') ? 'Yes' : 'No') . "\n";
echo "MySQLi extension file exists: " . (file_exists($extDir . '/php_mysqli.dll') ? 'Yes' : 'No') . "\n";
