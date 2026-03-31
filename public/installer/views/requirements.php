<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">System Requirements</h2>
        <p class="text-gray-600">Let's verify your server meets all requirements</p>
    </div>

    <?php
    $requirements = checkRequirements();
    $permissions = checkPermissions();

    $allRequirementsMet = true;
    $allPermissionsMet = true;

    foreach ($requirements as $req) {
        if (!$req['status']) $allRequirementsMet = false;
    }

    foreach ($permissions as $perm) {
        if (!$perm['status']) $allPermissionsMet = false;
    }
    ?>

    <!-- PHP Version & Extensions -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            PHP & Extensions
        </h3>

        <div class="space-y-3">
            <?php foreach ($requirements as $key => $req): ?>
            <div class="flex items-center justify-between p-4 border rounded-lg <?php echo $req['status'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'; ?>">
                <div>
                    <p class="font-medium <?php echo $req['status'] ? 'text-green-800' : 'text-red-800'; ?>"><?php echo htmlspecialchars($req['name']); ?></p>
                    <p class="text-sm <?php echo $req['status'] ? 'text-green-700' : 'text-red-700'; ?>">Required: <?php echo htmlspecialchars($req['required']); ?> | Current: <?php echo htmlspecialchars($req['current']); ?></p>
                </div>
                <?php if ($req['status']): ?>
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <?php else: ?>
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Folder Permissions -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            Folder Permissions
        </h3>

        <div class="space-y-3">
            <?php foreach ($permissions as $key => $perm): ?>
            <div class="flex items-center justify-between p-4 border rounded-lg <?php echo $perm['status'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'; ?>">
                <div>
                    <p class="font-medium <?php echo $perm['status'] ? 'text-green-800' : 'text-red-800'; ?>"><?php echo htmlspecialchars($perm['name']); ?></p>
                    <p class="text-sm <?php echo $perm['status'] ? 'text-green-700' : 'text-red-700'; ?>">Status: <?php echo htmlspecialchars($perm['current']); ?></p>
                </div>
                <?php if ($perm['status']): ?>
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <?php else: ?>
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Summary -->
    <?php if ($allRequirementsMet && $allPermissionsMet): ?>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-green-800 mb-1">All requirements met!</h4>
                <p class="text-sm text-green-700">Your server is ready for installation. Click the button below to continue.</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-red-800 mb-1">Some requirements not met</h4>
                <p class="text-sm text-red-700">Please fix the issues above before continuing. Contact your server administrator if you need help.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex justify-between">
        <a href="?step=1" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Back
        </a>
        <?php if ($allRequirementsMet && $allPermissionsMet): ?>
        <form method="POST" style="display: inline;">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all transform hover:scale-105 shadow-lg">
                Continue
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
