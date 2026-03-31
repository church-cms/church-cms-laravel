<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Admin Account Setup</h2>
        <p class="text-gray-600">Create your primary administrator account</p>
    </div>

    <form method="POST" id="installForm" class="space-y-8">
        <!-- Admin Account -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Administrator Credentials
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                    <input type="email" id="admin_email" name="admin_email" required value="<?php echo htmlspecialchars($admin_config['email'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="admin@yourchurch.com">
                    <p class="mt-1 text-xs text-gray-500">This will be your main login email</p>
                </div>

                <div>
                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="admin_password" name="admin_password" required minlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Minimum 8 characters">
                    <p class="mt-1 text-xs text-gray-500">Use a strong password with uppercase, lowercase, numbers and symbols</p>
                </div>

                <div>
                    <label for="admin_password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="admin_password_confirm" name="admin_password_confirm" required minlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Re-enter your password">
                </div>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-indigo-800 mb-1">Admin Account</h4>
                    <p class="text-sm text-indigo-700">
                        This is your primary administrator account. You'll use this email and password to log in to the Church CMS dashboard. Keep your credentials safe and change your password regularly.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <a href="?step=4" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Back
            </a>

            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
                Install Church CMS
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <script>
        document.getElementById('installForm').addEventListener('submit', function(e) {
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('admin_password_confirm').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters');
                return false;
            }
        });
    </script>
</div>
