<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    // Check if user exists
    $user = User::where('email', 'admin@example.com')->first();
    
    if ($user) {
        $user->update([
            'password' => Hash::make('admin123'),
            'is_admin' => true
        ]);
        echo "Admin password updated to 'admin123' and is_admin set to true.\n";
    } else {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true
        ]);
        echo "Admin user created with password 'admin123' and is_admin set to true.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
