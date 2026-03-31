<?php
/**
 * Installation Helper Functions
 */

/**
 * Check if a command exists in system PATH
 */
function commandExists($cmd) {
    $return = shell_exec(sprintf("which %s 2>/dev/null", escapeshellarg($cmd)));
    return !empty($return);
}

/**
 * Check system requirements
 */
function checkRequirements() {
    $requirements = [
        'php' => [
            'name' => 'PHP Version',
            'required' => '8.2.0',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'type' => 'php'
        ],
        'pdo' => [
            'name' => 'PDO Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('pdo'),
            'type' => 'extension'
        ],
        'pdo_mysql' => [
            'name' => 'PDO MySQL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('pdo_mysql'),
            'type' => 'extension'
        ],
        'mbstring' => [
            'name' => 'Mbstring Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('mbstring'),
            'type' => 'extension'
        ],
        'openssl' => [
            'name' => 'OpenSSL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('openssl'),
            'type' => 'extension'
        ],
        'tokenizer' => [
            'name' => 'Tokenizer Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('tokenizer'),
            'type' => 'extension'
        ],
        'json' => [
            'name' => 'JSON Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('json'),
            'type' => 'extension'
        ],
        'curl' => [
            'name' => 'cURL Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('curl') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('curl'),
            'type' => 'extension'
        ],
        'fileinfo' => [
            'name' => 'Fileinfo Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('fileinfo'),
            'type' => 'extension'
        ],
        'gd' => [
            'name' => 'GD Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('gd') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('gd'),
            'type' => 'extension'
        ],
        'bcmath' => [
            'name' => 'BCMath Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('bcmath') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('bcmath'),
            'type' => 'extension'
        ],
        'xml' => [
            'name' => 'XML Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('xml'),
            'type' => 'extension'
        ],
        'zip' => [
            'name' => 'Zip Extension',
            'required' => 'Enabled',
            'current' => extension_loaded('zip') ? 'Enabled' : 'Disabled',
            'status' => extension_loaded('zip'),
            'type' => 'extension'
        ],
        'composer' => [
            'name' => 'Composer',
            'required' => 'Installed',
            'current' => commandExists('composer') ? 'Installed' : 'Not Found',
            'status' => commandExists('composer'),
            'type' => 'tool'
        ],
        'npm' => [
            'name' => 'npm (Node.js)',
            'required' => 'Installed',
            'current' => commandExists('npm') ? 'Installed' : 'Not Found',
            'status' => commandExists('npm'),
            'type' => 'tool'
        ],
    ];

    return $requirements;
}

/**
 * Check folder permissions
 */
function checkPermissions() {
    $folders = [
        'storage' => BASE_PATH . '/storage',
        'storage/app' => BASE_PATH . '/storage/app',
        'storage/framework' => BASE_PATH . '/storage/framework',
        'storage/framework/cache' => BASE_PATH . '/storage/framework/cache',
        'storage/framework/sessions' => BASE_PATH . '/storage/framework/sessions',
        'storage/framework/views' => BASE_PATH . '/storage/framework/views',
        'storage/logs' => BASE_PATH . '/storage/logs',
        'bootstrap/cache' => BASE_PATH . '/bootstrap/cache',
        'public/uploads' => PUBLIC_PATH . '/uploads',
    ];

    $permissions = [];
    foreach ($folders as $name => $path) {
        $permissions[$name] = [
            'name' => $name,
            'path' => $path,
            'required' => '755',
            'status' => is_writable($path),
            'current' => is_writable($path) ? 'Writable' : 'Not Writable'
        ];
    }

    return $permissions;
}

/**
 * Test database connection
 */
function testDatabaseConnection($host, $port, $database, $username, $password) {
    try {
        $dsn = "mysql:host={$host};port={$port}";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Try to create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Test connection to the specific database
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return [
            'success' => true,
            'message' => 'Database connection successful'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }
}

/**
 * Save database configuration
 */
function saveDatabaseConfig($post) {
    $host = trim($post['db_host'] ?? 'localhost');
    $port = trim($post['db_port'] ?? '3306');
    $database = trim($post['db_name'] ?? '');
    $username = trim($post['db_user'] ?? '');
    $password = $post['db_password'] ?? '';

    // Validate inputs
    if (empty($database)) {
        return ['success' => false, 'message' => 'Database name is required'];
    }
    if (empty($username)) {
        return ['success' => false, 'message' => 'Database username is required'];
    }

    // Test connection
    $result = testDatabaseConnection($host, $port, $database, $username, $password);
    if (!$result['success']) {
        return $result;
    }

    return [
        'success' => true,
        'config' => [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password
        ]
    ];
}

/**
 * Save church configuration
 */
function saveChurchConfig($post) {
    $name = trim($post['church_name'] ?? '');
    $phone = trim($post['church_phone'] ?? '');
    $email = trim($post['church_email'] ?? '');
    $timezone = trim($post['timezone'] ?? 'UTC');

    // Validate inputs
    if (empty($name)) {
        return ['success' => false, 'message' => 'Church name is required'];
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Valid church email is required'];
    }

    return [
        'success' => true,
        'config' => [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'timezone' => $timezone
        ]
    ];
}

/**
 * Save admin configuration
 */
function saveAdminConfig($post) {
    $email = trim($post['admin_email'] ?? '');
    $password = $post['admin_password'] ?? '';
    $password_confirm = $post['admin_password_confirm'] ?? '';

    // Validate inputs
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Valid admin email is required'];
    }
    if (empty($password) || strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }
    if ($password !== $password_confirm) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }

    return [
        'success' => true,
        'config' => [
            'email' => $email,
            'password' => $password
        ]
    ];
}

/**
 * Create .env file
 */
function createEnvFile($config) {
    $envContent = file_get_contents(BASE_PATH . '/.env.example');

    // Replace database configuration
    $envContent = str_replace('DB_HOST=127.0.0.1', 'DB_HOST=' . $config['db_host'], $envContent);
    $envContent = str_replace('DB_PORT=3306', 'DB_PORT=' . $config['db_port'], $envContent);
    $envContent = str_replace('DB_DATABASE=churchcms', 'DB_DATABASE=' . $config['database'], $envContent);
    $envContent = str_replace('DB_USERNAME=root', 'DB_USERNAME=' . $config['db_user'], $envContent);

    // Handle DB_PASSWORD which might be empty
    if (!empty($config['db_password'])) {
        $envContent = str_replace('DB_PASSWORD=', 'DB_PASSWORD=' . $config['db_password'], $envContent);
    }

    // Generate APP_KEY if not exists
    if (strpos($envContent, 'APP_KEY=base64:') === false) {
        $appKey = 'base64:' . base64_encode(random_bytes(32));
        $envContent = str_replace('APP_KEY=', 'APP_KEY=' . $appKey, $envContent);
    }

    // Set installation flags
    $envContent = str_replace('CHURCH_INSTALLED=false', 'CHURCH_INSTALLED=true', $envContent);

    // Add timezone and primary church ID at the end
    $envContent = rtrim($envContent) . "\n";
    $envContent .= 'APP_TIMEZONE=' . $config['timezone'] . "\n";

    if (!file_put_contents(BASE_PATH . '/.env', $envContent)) {
        return ['success' => false, 'message' => 'Failed to create .env file'];
    }

    return ['success' => true];
}

/**
 * Output installation status update
 */
function outputStatus($step, $status, $message = '') {
    $js = "<script>if (window.updateStatus) window.updateStatus('{$step}', '{$status}', '" . addslashes($message) . "');</script>";
    echo $js;
    ob_flush();
    flush();
}

/**
 * Run Laravel migrations and seeding
 */
function runInstallation($session) {
    try {
        $basePath = BASE_PATH;

        // Create .env file
        $dbConfig = $session['db_config'];
        $churchConfig = $session['church_config'];
        $adminConfig = $session['admin_config'];

        $envResult = createEnvFile([
            'db_host' => $dbConfig['host'],
            'db_port' => $dbConfig['port'],
            'database' => $dbConfig['database'],
            'db_user' => $dbConfig['username'],
            'db_password' => $dbConfig['password'],
            'timezone' => $churchConfig['timezone']
        ]);

        if (!$envResult['success']) {
            return $envResult;
        }

        // Run composer install
        outputStatus('composer', 'processing', 'Installing dependencies...');
        if (!file_exists($basePath . '/vendor/autoload.php')) {
            if (commandExists('composer')) {
                $output = shell_exec('cd ' . escapeshellarg($basePath) . ' && composer install --no-interaction --no-dev 2>&1');
                if (strpos($output, 'error') !== false && strpos($output, 'autoload.php') === false) {
                    // Non-fatal composer warnings are OK
                }
                outputStatus('composer', 'done', 'Dependencies installed');
            }
        } else {
            outputStatus('composer', 'done', 'Already installed');
        }

        // Run npm install
        outputStatus('npm', 'processing', 'Installing npm packages...');
        if (!file_exists($basePath . '/node_modules')) {
            if (commandExists('npm')) {
                $output = shell_exec('cd ' . escapeshellarg($basePath) . ' && npm install 2>&1');
                if (strpos($output, 'ERR!') !== false) {
                    // npm might give warnings, only fail on actual errors
                }
                outputStatus('npm', 'done', 'npm packages installed');
            }
        } else {
            outputStatus('npm', 'done', 'Already installed');
        }

        // Run migrations
        outputStatus('migration', 'processing', 'Running migrations...');
        $output = shell_exec('cd ' . escapeshellarg($basePath) . ' && php artisan migrate --force 2>&1');
        if (strpos(strtolower($output), 'error') !== false || strpos(strtolower($output), 'exception') !== false) {
            // Check if error is actually an error or just info
            if (strpos(strtolower($output), 'migrated successfully') === false &&
                strpos(strtolower($output), 'already') === false &&
                strpos(strtolower($output), 'no migrations') === false) {

                // Provide helpful error message
                $errorMsg = $output;
                if (strpos($output, 'autoload.php') !== false) {
                    $errorMsg = 'Composer dependencies not installed. Please run: composer install';
                }

                return [
                    'success' => false,
                    'message' => 'Migration failed: ' . $errorMsg
                ];
            }
        }
        outputStatus('migration', 'done', 'Migrations completed');

        // Run installer seeder
        outputStatus('seed', 'processing', 'Seeding database...');
        $output = shell_exec('cd ' . escapeshellarg($basePath) . ' && php artisan db:seed --class=InstallerSeeder --force 2>&1');
        outputStatus('seed', 'done', 'Database seeded');

        // Store installation data for creating church and admin
        outputStatus('church', 'processing', 'Creating church & admin...');
        $_SESSION['installation_data'] = [
            'church' => $churchConfig,
            'admin' => $adminConfig,
            'db' => $dbConfig
        ];

        // Create church and admin via artisan command
        $installData = json_encode([
            'church_name' => $churchConfig['name'],
            'church_email' => $churchConfig['email'],
            'church_phone' => $churchConfig['phone'] ?? '',
            'admin_email' => $adminConfig['email'],
            'admin_password' => $adminConfig['password']
        ]);

        // Log the data being sent
        error_log('Church install - JSON data: ' . $installData);

        // Use base64 encoding to avoid quote issues
        $encodedData = base64_encode($installData);
        error_log('Church install - Encoded data length: ' . strlen($encodedData));

        $cmd = 'cd ' . escapeshellarg($basePath) . ' && php artisan church:install-data ' . escapeshellarg($encodedData) . ' --base64 2>&1';
        error_log('Church install - Command: ' . $cmd);

        $output = shell_exec($cmd);
        error_log('Church install - Command output: ' . $output);

        // Check if command executed successfully
        if (strpos($output, 'error') !== false || strpos($output, 'Error') !== false) {
            // Log the error output
            error_log('Church install data error: ' . $output);
        }

        outputStatus('church', 'done', 'Church & admin created');

        return ['success' => true];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Installation error: ' . $e->getMessage()
        ];
    }
}

/**
 * Get timezones
 */
function getTimezones() {
    $timezones = [
        'UTC' => 'UTC',
        'Asia/Kolkata' => 'Asia/Kolkata (IST)',
        'America/New_York' => 'America/New_York (EST)',
        'America/Chicago' => 'America/Chicago (CST)',
        'America/Denver' => 'America/Denver (MST)',
        'America/Los_Angeles' => 'America/Los_Angeles (PST)',
        'Europe/London' => 'Europe/London (GMT)',
        'Europe/Paris' => 'Europe/Paris (CET)',
        'Europe/Berlin' => 'Europe/Berlin (CET)',
        'Australia/Sydney' => 'Australia/Sydney (AEDT)',
        'Asia/Tokyo' => 'Asia/Tokyo (JST)',
        'Asia/Shanghai' => 'Asia/Shanghai (CST)',
        'Asia/Bangkok' => 'Asia/Bangkok (ICT)',
        'Asia/Dubai' => 'Asia/Dubai (GST)',
    ];

    return $timezones;
}

/**
 * Get countries (hardcoded minimal set, extend as needed)
 */
function getCountries() {
    return [
        '1' => 'United States',
        '7' => 'India',
        '44' => 'United Kingdom',
        '91' => 'India',
        '61' => 'Australia',
        '86' => 'China',
        '81' => 'Japan',
        '33' => 'France',
        '49' => 'Germany',
        '39' => 'Italy',
    ];
}

/**
 * Get states (simplified example)
 */
function getStates($country_id) {
    $states = [
        '1' => [ // USA
            '1' => 'Alabama',
            '2' => 'Alaska',
            '3' => 'Arizona',
            '4' => 'Arkansas',
            '5' => 'California',
        ],
        '7' => [ // India
            '24' => 'Tamil Nadu',
            '25' => 'Kerala',
            '26' => 'Karnataka',
            '27' => 'Andhra Pradesh',
            '28' => 'Telangana',
        ],
    ];

    return $states[$country_id] ?? [];
}

/**
 * Get cities (simplified example)
 */
function getCities($state_id) {
    $cities = [
        '24' => [ // Tamil Nadu
            '31' => 'Madurai',
            '32' => 'Coimbatore',
            '33' => 'Chennai',
        ],
        '25' => [ // Kerala
            '34' => 'Kochi',
            '35' => 'Thiruvananthapuram',
        ],
    ];

    return $cities[$state_id] ?? [];
}
