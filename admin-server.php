<?php

/**
 * Admin Server - Runs on separate port for admin access
 * Usage: php admin-server.php [port]
 * Default port: 8001
 */

$port = $argv[1] ?? 8001;
$host = '127.0.0.1';

echo "=== McDonald's Admin Server ===\n";
echo "Starting admin server on http://{$host}:{$port}\n";
echo "Admin login: admin@mcd.com / admin123\n";
echo "Press Ctrl+C to stop\n\n";

// Start Laravel development server with custom configuration
$command = "php artisan serve --host={$host} --port={$port}";

// Set environment variable to indicate this is admin server
putenv('ADMIN_SERVER=true');

// Execute the command
passthru($command);