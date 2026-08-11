<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome to Church CMS</h2>
        <p class="text-gray-600 max-w-md mx-auto">
            Thank you for choosing Church CMS. This wizard will guide you through the installation process to get your church management system up and running.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Check Requirements</h3>
            <p class="text-sm text-gray-500">Verify your server meets all requirements</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-green-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Setup Database</h3>
            <p class="text-sm text-gray-500">Configure your MySQL database</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <div class="w-12 h-12 mx-auto bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Configure Church</h3>
            <p class="text-sm text-gray-500">Set up your church information</p>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-amber-800 mb-1">Before you begin</h4>
                <ul class="text-sm text-amber-700 space-y-1">
                    <li>• Make sure you have your database credentials ready</li>
                    <li>• Ensure your server meets the minimum PHP 8.2 requirement</li>
                    <li>• The storage and bootstrap/cache directories must be writable</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="?step=2" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
            Let's Get Started
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</div>
