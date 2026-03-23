<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
App\Models\User::truncate(); // Delete all old users
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

$user = new App\Models\User();
$user->username = 'admin';
$user->email = 'admin@thegioididong.com';
// Let's use bcrypt
$user->password = bcrypt('123456');
$user->role = 'admin';
$user->save();

echo "Deleted old users. Created new Admin:\nEmail: {$user->email}\nPassword: 123456\nUsername: {$user->username}\nRole: {$user->role}\n";
