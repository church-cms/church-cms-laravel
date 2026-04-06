<?php

namespace Database\Seeders;

/**
 * Shim for Laravel 10 compatibility.
 * The actual seeder logic lives in database/seeds/DatabaseSeeder.php (global namespace).
 * This class simply extends it so that Laravel 10's db:seed command can resolve
 * Database\Seeders\DatabaseSeeder correctly.
 */
class DatabaseSeeder extends \DatabaseSeeder
{
}
