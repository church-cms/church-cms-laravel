<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church CMS - Installation Wizard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Church CMS</h1>
                <p class="text-gray-600">Installation Wizard</p>
            </div>

            <!-- Step Indicator -->
            <div class="mb-12">
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 1 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">1</div>
                        <p class="text-xs font-medium text-gray-600">Welcome</p>
                    </div>
                    <div class="flex-1 h-1 mx-2 <?php echo $step > 1 ? 'bg-purple-600' : 'bg-gray-300'; ?>"></div>
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 2 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">2</div>
                        <p class="text-xs font-medium text-gray-600">Requirements</p>
                    </div>
                    <div class="flex-1 h-1 mx-2 <?php echo $step > 2 ? 'bg-purple-600' : 'bg-gray-300'; ?>"></div>
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 3 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">3</div>
                        <p class="text-xs font-medium text-gray-600">Database</p>
                    </div>
                    <div class="flex-1 h-1 mx-2 <?php echo $step > 3 ? 'bg-purple-600' : 'bg-gray-300'; ?>"></div>
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 4 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">4</div>
                        <p class="text-xs font-medium text-gray-600">Church</p>
                    </div>
                    <div class="flex-1 h-1 mx-2 <?php echo $step > 4 ? 'bg-purple-600' : 'bg-gray-300'; ?>"></div>
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 5 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">5</div>
                        <p class="text-xs font-medium text-gray-600">Admin</p>
                    </div>
                    <div class="flex-1 h-1 mx-2 <?php echo $step > 5 ? 'bg-purple-600' : 'bg-gray-300'; ?>"></div>
                    <div class="flex-1 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full <?php echo $step >= 6 ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-600'; ?> font-semibold mb-2">6</div>
                        <p class="text-xs font-medium text-gray-600">Complete</p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-800 mb-1">Error</h4>
                        <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (!empty($success)): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-green-800 mb-1">Success</h4>
                        <p class="text-sm text-green-700"><?php echo htmlspecialchars($success); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Main Content Area -->
