<?php

// Test-specific bootstrap file that runs migrations
// and seeds. Should only be used while testing.

require __DIR__.'/autoload.php';

echo exec('php artisan migrate:refresh --seed --database=test');