<?php

// Load the application
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Get the Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Run the seeders directly
$seeders = [
    'Database\Seeders\UsersSeeder',
    'Database\Seeders\ProgramStudiSeeder',
    'Database\Seeders\FeedbackSeeder',
    // 'Database\Seeders\LaporanSeeder'
];

echo "Running seeders...\n";

foreach ($seeders as $seeder) {
    echo "Seeding: " . $seeder . "\n";
    try {
        $instance = new $seeder();
        $instance->run();
        echo "Completed: " . $seeder . "\n";
    } catch (Exception $e) {
        echo "Error in " . $seeder . ": " . $e->getMessage() . "\n";
    }
}

echo "All seeders completed!\n";
