<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\User;

echo "Checking database status...\n";

if (Schema::hasColumn('users', 'role')) {
    echo "Column 'role' exists.\n";
} else {
    echo "Column 'role' DOES NOT exist.\n";
    exit(1);
}

$user = User::first();
if ($user) {
    $user->role = 'admin';
    $user->save();
    echo "User {$user->email} promoted to admin.\n";
} else {
    echo "No users found.\n";
    // Create one
    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
    echo "Admin user created: admin@example.com / password\n";
}
