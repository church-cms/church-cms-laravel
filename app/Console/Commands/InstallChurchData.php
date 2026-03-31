<?php

namespace App\Console\Commands;

use App\Models\Church;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class InstallChurchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:install-data {data} {--base64 : Data is base64 encoded}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial church and admin user from installation data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return integer
     */
    public function handle()
    {
        try {
            // Log to file for debugging
            $logPath = storage_path('logs/installer.log');
            $logDir = dirname($logPath);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $logMsg = "=== Starting church:install-data command ===\n";
            $logMsg .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";

            // Get installation data from command argument
            $jsonData = $this->argument('data');
            $logMsg .= "Received data (first 100 chars): " . substr($jsonData, 0, 100) . "\n";
            $logMsg .= "Base64 flag: " . ($this->option('base64') ? 'YES' : 'NO') . "\n";

            // Decode if base64 encoded
            if ($this->option('base64')) {
                $decoded = base64_decode($jsonData, true);
                if ($decoded === false) {
                    $logMsg .= "ERROR: Base64 decode failed\n";
                    file_put_contents($logPath, $logMsg, FILE_APPEND);
                    $this->error('Base64 decoding failed');
                    return 1;
                }
                $jsonData = $decoded;
                $logMsg .= "Successfully decoded base64\n";
            }

            $logMsg .= "JSON data: " . $jsonData . "\n";

            $data = json_decode($jsonData, true);

            if (!$data) {
                $logMsg .= "ERROR: JSON decode failed: " . json_last_error_msg() . "\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                $this->error('Invalid installation data: ' . json_last_error_msg());
                return 1;
            }

            $logMsg .= "Successfully decoded JSON\n";
            $logMsg .= "Church name: " . ($data['church_name'] ?? 'MISSING') . "\n";
            $logMsg .= "Church email: " . ($data['church_email'] ?? 'MISSING') . "\n";
            $logMsg .= "Admin email: " . ($data['admin_email'] ?? 'MISSING') . "\n";

            // Create the church
            $logMsg .= "Creating church...\n";
            try {
                // Generate slug from church name
                $slug = strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9\s]/', '', $data['church_name'])));

                $church = Church::create([
                    'name' => $data['church_name'],
                    'email' => $data['church_email'],
                    'phone_number' => $data['church_phone'] ?? '',
                    'address' => '', // Not collected in simplified installer form
                    'state_id' => null, // Not collected in simplified installer form
                    'city_id' => null, // Not collected in simplified installer form
                    'pincode' => '', // Not collected in simplified installer form
                    'slug' => $slug,
                    'status' => 1,
                ]);
                $logMsg .= "✓ Church created: " . $church->name . " (ID: " . $church->id . ")\n";
                $this->info('Church created: ' . $church->name . ' (ID: ' . $church->id . ')');
            } catch (\Exception $e) {
                $logMsg .= "✗ Church creation failed: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                $this->error('Failed to create church: ' . $e->getMessage());
                return 1;
            }

            // Get admin user group ID (Admin = ID 3)
            $logMsg .= "Looking for Admin usergroup...\n";
            $adminGroupId = \App\Models\Usergroup::where('name', 'Admin')->first()?->id ?? 3;
            $logMsg .= "Admin usergroup ID: " . $adminGroupId . "\n";

            // Create the admin user
            $logMsg .= "Creating admin user...\n";
            try {
                $user = User::create([
                    'name' => explode('@', $data['admin_email'])[0],
                    'email' => $data['admin_email'],
                    'password' => Hash::make($data['admin_password']),
                    'mobile_no' => '',
                    'church_id' => $church->id,
                    'usergroup_id' => $adminGroupId,
                ]);
                $logMsg .= "✓ Admin user created: " . $user->email . " (ID: " . $user->id . ")\n";
                $this->info('Admin user created: ' . $user->email . ' (ID: ' . $user->id . ')');
            } catch (\Exception $e) {
                $logMsg .= "✗ User creation failed: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                $this->error('Failed to create user: ' . $e->getMessage());
                return 1;
            }

            // Create userprofile
            $logMsg .= "Creating userprofile...\n";
            try {
                Userprofile::create([
                    'user_id' => $user->id,
                    'church_id' => $church->id,
                    'firstname' => explode('@', $data['admin_email'])[0],
                    'lastname' => 'Administrator',
                    'profession' => 'Church Administrator',
                ]);
                $logMsg .= "✓ User profile created\n";
                $this->info('User profile created');
            } catch (\Exception $e) {
                $logMsg .= "✗ Userprofile creation failed: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                // Don't fail here - userprofile is not critical
                $this->warn('Userprofile creation warning: ' . $e->getMessage());
            }

            // Set environment variables
            $logMsg .= "Updating .env file...\n";
            try {
                $this->updateEnvFile($church->id);
                $logMsg .= "✓ .env file updated with PRIMARY_CHURCH_ID=" . $church->id . "\n";
            } catch (\Exception $e) {
                $logMsg .= "✗ .env update failed: " . $e->getMessage() . "\n";
            }

            $logMsg .= "=== Church install completed successfully ===\n\n";
            file_put_contents($logPath, $logMsg, FILE_APPEND);

            $this->info('Installation data processed successfully!');
            return 0;
        } catch (\Exception $e) {
            $logPath = storage_path('logs/installer.log');
            $errMsg = "FATAL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
            @file_put_contents($logPath, $errMsg, FILE_APPEND);

            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Update .env file with PRIMARY_CHURCH_ID
     */
    private function updateEnvFile($churchId)
    {
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);

            // Add or update PRIMARY_CHURCH_ID
            if (strpos($env, 'PRIMARY_CHURCH_ID=') === false) {
                $env .= "\nPRIMARY_CHURCH_ID={$churchId}";
            } else {
                $env = preg_replace('/PRIMARY_CHURCH_ID=.*/', 'PRIMARY_CHURCH_ID=' . $churchId, $env);
            }

            file_put_contents($envPath, $env);
        }
    }
}
