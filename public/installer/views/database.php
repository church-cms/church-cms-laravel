<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-green-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5v5m0-5c0 2.21-3.582 4-8 4s-8-1.79-8-4m0-5v5m0-5c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Database Configuration</h2>
        <p class="text-gray-600">Configure your MySQL database connection</p>
    </div>

    <form method="POST" id="installForm" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="db_host" class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
                <input type="text" id="db_host" name="db_host" required value="<?php echo htmlspecialchars($db_config['host'] ?? 'localhost'); ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    placeholder="localhost">
            </div>

            <div>
                <label for="db_port" class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
                <input type="number" id="db_port" name="db_port" required value="<?php echo htmlspecialchars($db_config['port'] ?? '3306'); ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    placeholder="3306">
            </div>
        </div>

        <div>
            <label for="db_name" class="block text-sm font-medium text-gray-700 mb-2">Database Name</label>
            <input type="text" id="db_name" name="db_name" required value="<?php echo htmlspecialchars($db_config['database'] ?? ''); ?>"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                placeholder="church_cms">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="db_user" class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
                <input type="text" id="db_user" name="db_user" required value="<?php echo htmlspecialchars($db_config['username'] ?? ''); ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    placeholder="root">
            </div>

            <div>
                <label for="db_password" class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
                <input type="password" id="db_password" name="db_password" value="<?php echo htmlspecialchars($db_config['password'] ?? ''); ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    placeholder="Leave empty if no password">
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-1">Database Connection</h4>
                    <p class="text-sm text-blue-700">
                        The database will be created automatically if it doesn't exist. Make sure your database user has the necessary privileges.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <a href="?step=2" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Back
            </a>

            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
                Continue
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
