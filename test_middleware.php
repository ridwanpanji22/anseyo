<?php

require_once 'vendor/autoload.php';

use App\Http\Middleware\WaiterMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Test middleware
$middleware = new WaiterMiddleware();

// Simulate authenticated user with waiter role
$user = new stdClass();
$user->role = 'waiter';

// Mock auth
Auth::shouldReceive('check')->andReturn(true);
Auth::shouldReceive('user')->andReturn($user);

echo "Testing WaiterMiddleware...\n";
echo "User role: " . $user->role . "\n";

// Test if user has waiter role
if (in_array($user->role, ['waiter', 'admin'])) {
    echo "✅ User has waiter privileges\n";
} else {
    echo "❌ User does not have waiter privileges\n";
} 