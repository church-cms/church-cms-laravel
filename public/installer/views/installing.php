<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl card-shadow p-8 max-w-2xl w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-100 to-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-purple-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Installing Church CMS</h2>
            <p class="text-gray-600">Setting up your application...</p>
        </div>

        <!-- Progress Steps -->
        <div class="space-y-4 mb-8">
            <!-- Composer Install -->
            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div id="composer-status" class="flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-gray-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="15.7 47.1" style="animation: spin 0.8s linear infinite;"></circle>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Installing Composer Dependencies</p>
                    <p id="composer-text" class="text-sm text-gray-600">Preparing...</p>
                </div>
            </div>

            <!-- npm Install -->
            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div id="npm-status" class="flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Installing npm Packages</p>
                    <p id="npm-text" class="text-sm text-gray-600">Waiting...</p>
                </div>
            </div>

            <!-- Migrations -->
            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div id="migration-status" class="flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Running Database Migrations</p>
                    <p id="migration-text" class="text-sm text-gray-600">Waiting...</p>
                </div>
            </div>

            <!-- Seeding -->
            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div id="seed-status" class="flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Seeding Database</p>
                    <p id="seed-text" class="text-sm text-gray-600">Waiting...</p>
                </div>
            </div>

            <!-- Church Creation -->
            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div id="church-status" class="flex-shrink-0 mr-4">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-800">Creating Church & Admin</p>
                    <p id="church-text" class="text-sm text-gray-600">Waiting...</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-sm text-blue-700">
                <strong>Please don't refresh the page</strong> while installation is in progress. This may take a few minutes.
            </p>
        </div>
    </div>

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .check-icon {
            animation: checkAnimation 0.5s ease;
        }

        @keyframes checkAnimation {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }
    </style>

    <script>
        // Update status during installation
        function updateStatus(step, status, message) {
            const statusMap = {
                'composer': { elem: 'composer-status', text: 'composer-text' },
                'npm': { elem: 'npm-status', text: 'npm-text' },
                'migration': { elem: 'migration-status', text: 'migration-text' },
                'seed': { elem: 'seed-status', text: 'seed-text' },
                'church': { elem: 'church-status', text: 'church-text' }
            };

            if (statusMap[step]) {
                const elem = document.getElementById(statusMap[step].elem);
                const textElem = document.getElementById(statusMap[step].text);

                if (status === 'processing') {
                    elem.innerHTML = '<svg class="w-6 h-6 text-purple-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="15.7 47.1" style="animation: spin 0.8s linear infinite;"></circle></svg>';
                } else if (status === 'done') {
                    elem.innerHTML = '<svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                } else if (status === 'error') {
                    elem.innerHTML = '<svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                    textElem.classList.add('text-red-600');
                }

                if (message) {
                    textElem.textContent = message;
                }
            }
        }

        // Expose function globally for PHP to use
        window.updateStatus = updateStatus;
    </script>
</div>
