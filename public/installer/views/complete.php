<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Installation Complete!</h2>
        <p class="text-gray-600 max-w-md mx-auto">
            Church CMS has been successfully installed and configured.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-green-50 rounded-xl p-6 border border-green-200">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="font-semibold text-green-800">System Setup</h3>
            </div>
            <ul class="text-sm text-green-700 space-y-2">
                <li>✓ Database migrations completed</li>
                <li>✓ System data seeded</li>
                <li>✓ Church information configured</li>
                <li>✓ Admin user created</li>
            </ul>
        </div>

        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 0-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM14 2a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0V6h-1a1 1 0 110-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="font-semibold text-blue-800">What's Next?</h3>
            </div>
            <ul class="text-sm text-blue-700 space-y-2">
                <li>• Log in to the admin dashboard</li>
                <li>• Configure church settings</li>
                <li>• Add church staff members</li>
                <li>• Start using Church CMS</li>
            </ul>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-6 mb-8">
        <h3 class="font-semibold text-gray-800 mb-4">Login Credentials</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-xs text-gray-600 mb-1">Email</p>
                <p class="font-mono text-sm text-gray-800 font-semibold">admin@yourchurch.com</p>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-xs text-gray-600 mb-1">Password</p>
                <p class="text-sm text-gray-600">•••••••• (As you set during installation)</p>
            </div>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-amber-800 mb-1">Important Security Notes</h4>
                <ul class="text-sm text-amber-700 space-y-1">
                    <li>• Change your admin password on your first login</li>
                    <li>• Enable HTTPS for your domain</li>
                    <li>• Regularly update Church CMS and plugins</li>
                    <li>• Remove the /installer directory for security</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button
            id="completeBtn"
            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all transform hover:scale-105 shadow-lg"
            onclick="completeInstallation()">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            Complete Setup &amp; Go to Dashboard
        </button>
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Installation ID: <code class="bg-gray-100 px-2 py-1 rounded"><?php echo date('YmdHis'); ?></code></p>
    </div>
</div>

<script>
function completeInstallation() {
    const btn = document.getElementById('completeBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle></svg> Finalizing...';

    // Call AJAX to write the installed marker
    fetch('?action=finalize_installation', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                window.location.href = '/login';
            }, 1000);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'Complete Setup &amp; Go to Dashboard';
            alert('Error finalizing installation: ' + data.message);
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = 'Complete Setup &amp; Go to Dashboard';
        alert('Error finalizing installation: ' + error);
    });
}
