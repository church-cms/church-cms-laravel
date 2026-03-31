<?php

namespace App\Console\Commands;

use App\Models\Church;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupChurchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup your church and create the first admin user';

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

            $logMsg = "=== Starting church:setup command ===\n";
            $logMsg .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
            $logMsg .= "User: " . (auth()->check() ? auth()->user()->email : 'CLI') . "\n\n";

            $this->line('');
            $this->info('╔════════════════════════════════════════════════════════════╗');
            $this->info('║                                                            ║');
            $this->info('║     Church CMS - Church & Administrator Setup              ║');
            $this->info('║                                                            ║');
            $this->info('╚════════════════════════════════════════════════════════════╝');
            $this->line('');

            // Check if church already exists
            if (Church::exists()) {
                $this->warn('⚠ Church is already configured in this installation!');
                $this->line('');

                if (!$this->confirm('Do you want to create another church?')) {
                    $this->info('Setup cancelled.');
                    return 0;
                }
                $this->line('');
            }

            // Collect church information
            $this->line('<fg=cyan>Church Information</>');
            $this->line('─────────────────────────────────────────────────────────────');

            $church_name = $this->ask('Church Name');
            while (empty($church_name)) {
                $this->error('Church name is required');
                $church_name = $this->ask('Church Name');
            }

            $church_email = $this->ask('Church Email');
            while (!filter_var($church_email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Please enter a valid email address');
                $church_email = $this->ask('Church Email');
            }

            $church_phone = $this->ask('Church Phone (optional)', '');

            $timezone = $this->ask('Timezone', 'UTC');

            // Display summary
            $this->line('');
            $this->info('Summary:');
            $this->line('  Name:     ' . $church_name);
            $this->line('  Email:    ' . $church_email);
            $this->line('  Phone:    ' . ($church_phone ?: 'Not provided'));
            $this->line('  Timezone: ' . $timezone);
            $this->line('');

            if (!$this->confirm('Is this correct?')) {
                $this->info('Setup cancelled.');
                return 0;
            }

            // Collect admin information
            $this->line('');
            $this->line('<fg=cyan>Administrator Account</>');
            $this->line('─────────────────────────────────────────────────────────────');

            $admin_email = $this->ask('Admin Email');
            while (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Please enter a valid email address');
                $admin_email = $this->ask('Admin Email');
            }

            // Get password
            $admin_password = '';
            while (empty($admin_password) || strlen($admin_password) < 8) {
                $admin_password = $this->secret('Password (minimum 8 characters)');
                if (strlen($admin_password) < 8) {
                    $this->error('Password must be at least 8 characters');
                }
            }

            // Confirm password
            while (true) {
                $admin_password_confirm = $this->secret('Confirm Password');
                if ($admin_password === $admin_password_confirm) {
                    break;
                }
                $this->error('Passwords do not match');
            }

            // Display summary
            $this->line('');
            $this->info('Summary:');
            $this->line('  Email: ' . $admin_email);
            $this->line('');

            if (!$this->confirm('Is this correct?')) {
                $this->info('Setup cancelled.');
                return 0;
            }

            // Create the church
            $this->line('');
            $this->info('⏳ Creating church...');
            $logMsg .= "Creating church: $church_name\n";

            try {
                // Generate slug from church name
                $slug = strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9\s]/', '', $church_name)));

                $church = Church::create([
                    'name' => $church_name,
                    'email' => $church_email,
                    'phone_number' => $church_phone,
                    'address' => '',
                    'state_id' => null,
                    'city_id' => null,
                    'pincode' => '',
                    'slug' => $slug,
                    'status' => 1,
                ]);

                $logMsg .= "✓ Church created: $church_name (ID: {$church->id})\n";
                $this->line('✓ Church created successfully');
            } catch (\Exception $e) {
                $logMsg .= "✗ Church creation failed: {$e->getMessage()}\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                $this->error('Failed to create church: ' . $e->getMessage());
                return 1;
            }

            // Get admin user group ID
            $logMsg .= "Looking for Admin usergroup...\n";
            $adminGroupId = \App\Models\Usergroup::where('name', 'Admin')->first()?->id ?? 3;
            $logMsg .= "Admin usergroup ID: $adminGroupId\n";

            // Create the admin user
            $this->info('⏳ Creating admin user...');
            $logMsg .= "Creating admin user: $admin_email\n";

            try {
                $user = User::create([
                    'name' => explode('@', $admin_email)[0],
                    'email' => $admin_email,
                    'password' => Hash::make($admin_password),
                    'mobile_no' => '',
                    'church_id' => $church->id,
                    'usergroup_id' => $adminGroupId,
                ]);

                $logMsg .= "✓ Admin user created: $admin_email (ID: {$user->id})\n";
                $this->line('✓ Administrator created successfully');
            } catch (\Exception $e) {
                $logMsg .= "✗ User creation failed: {$e->getMessage()}\n";
                file_put_contents($logPath, $logMsg, FILE_APPEND);
                $this->error('Failed to create user: ' . $e->getMessage());
                return 1;
            }

            // Create userprofile
            $this->info('⏳ Creating user profile...');
            $logMsg .= "Creating userprofile...\n";

            try {
                Userprofile::create([
                    'user_id' => $user->id,
                    'church_id' => $church->id,
                    'firstname' => explode('@', $admin_email)[0],
                    'lastname' => 'Administrator',
                    'profession' => 'Church Admin',
                ]);

                $logMsg .= "✓ User profile created\n";
                $this->line('✓ User profile created');
            } catch (\Exception $e) {
                $logMsg .= "⚠ User profile creation warning: {$e->getMessage()}\n";
                $this->warn('User profile creation had a warning (non-critical)');
            }

            // Update .env with PRIMARY_CHURCH_ID
            $this->info('⏳ Updating environment configuration...');
            $logMsg .= "Updating .env file with PRIMARY_CHURCH_ID={$church->id}\n";

            try {
                $this->updateEnvFile($church->id);
                $logMsg .= "✓ .env file updated\n";
                $this->line('✓ Environment updated');
            } catch (\Exception $e) {
                $logMsg .= "⚠ .env update warning: {$e->getMessage()}\n";
                $this->warn('Environment update had a warning');
            }

            // Create installation marker
            $this->info('⏳ Finalizing installation...');
            $logMsg .= "Creating installation marker...\n";

            try {
                $storageDir = storage_path();
                if (!is_dir($storageDir)) {
                    mkdir($storageDir, 0755, true);
                }

                $markerFile = $storageDir . '/installed';
                file_put_contents($markerFile, date('Y-m-d H:i:s'));
                @chmod($markerFile, 0644);

                $logMsg .= "✓ Installation marker created\n";
                $this->line('✓ Installation completed');
            } catch (\Exception $e) {
                $logMsg .= "⚠ Installation marker warning: {$e->getMessage()}\n";
                $this->warn('Installation marker creation had a warning');
            }

            // Log completion
            $logMsg .= "=== Church setup completed successfully ===\n\n";
            file_put_contents($logPath, $logMsg, FILE_APPEND);

            // Display success message
            $this->line('');
            $this->line('╔════════════════════════════════════════════════════════════╗');
            $this->line('║                                                            ║');
            $this->info('║      ✓ Church CMS Setup Complete!                        ║');
            $this->line('║                                                            ║');
            $this->line('╚════════════════════════════════════════════════════════════╝');
            $this->line('');

            $this->table(['Item', 'Value'], [
                ['Church Name', $church_name],
                ['Church ID', $church->id],
                ['Admin Email', $admin_email],
                ['Admin ID', $user->id],
                ['Status', 'Ready'],
            ]);

            $this->line('');
            $this->info('Next Steps:');
            $this->line('  1. Start the development server:');
            $this->line('     <comment>php artisan serve</comment>');
            $this->line('');
            $this->line('  2. Access the admin dashboard:');
            $this->line('     <comment>http://localhost:8000/admin</comment>');
            $this->line('');
            $this->line('  3. Log in with:');
            $this->line('     Email:    <comment>' . $admin_email . '</comment>');
            $this->line('     Password: <comment>(the password you just set)</comment>');
            $this->line('');

            $this->warn('Security Reminders:');
            $this->line('  • Remove installer: <comment>rm -rf public/installer</comment>');
            $this->line('  • Change password on first login');
            $this->line('  • Enable HTTPS in production');
            $this->line('  • Set up regular backups');
            $this->line('');

            $this->info('Happy serving! 🙏');
            $this->line('');

            return 0;
        } catch (\Exception $e) {
            $logPath = storage_path('logs/installer.log');
            $errMsg = "FATAL ERROR in church:setup: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
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
