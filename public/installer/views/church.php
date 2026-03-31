<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Church Information</h2>
        <p class="text-gray-600">Configure your church details</p>
    </div>

    <form method="POST" id="installForm" class="space-y-8">
        <!-- Church Basic Info -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Basic Information
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="church_name" class="block text-sm font-medium text-gray-700 mb-2">Church Name</label>
                    <input type="text" id="church_name" name="church_name" required value="<?php echo htmlspecialchars($church_config['name'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Your Church Name">
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="church_email" class="block text-sm font-medium text-gray-700 mb-2">Church Email</label>
                        <input type="email" id="church_email" name="church_email" required value="<?php echo htmlspecialchars($church_config['email'] ?? ''); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="church@example.com">
                    </div>

                    <div>
                        <label for="church_phone" class="block text-sm font-medium text-gray-700 mb-2">Church Phone</label>
                        <input type="tel" id="church_phone" name="church_phone" value="<?php echo htmlspecialchars($church_config['phone'] ?? ''); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="+1 (555) 123-4567">
                    </div>
                </div>


            </div>
        </div>



        <!-- Timezone -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Timezone
            </h3>

            <select id="timezone" name="timezone" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <?php foreach (getTimezones() as $value => $label): ?>
                <option value="<?php echo htmlspecialchars($value); ?>" <?php echo ($church_config['timezone'] ?? 'UTC') === $value ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-purple-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-purple-800 mb-1">Church Details</h4>
                    <p class="text-sm text-purple-700">
                        These details will be used to identify your church in the system. You can update them later.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <a href="?step=3" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
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
