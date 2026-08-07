@extends('theme::layout')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 160))

@section('content')
    <div class="">
        <div class="container mx-auto h-full">
            <div class="text-center flex flex-col justify-center items-center py-5 leading-loose tracking-wider h-full">
                <h1 class="text-2xl lg:text-4xl font-bold">Privacy Policy</h1>
            </div>
        </div>
    </div>
    <div class="bg-gray-100 py-5">
   
        <div class="bg-white shadow-lg rounded-xl p-8 md:p-12">
            <div class="border-b pb-6 mb-8">
                <!-- <h1 class="text-4xl font-bold text-gray-800">Privacy Policy</h1> -->
                <p class="text-gray-500 mt-2">
                    Effective Date: <strong>[06-08-2026]</strong><br>
                    Last Updated: <strong>[06-08-2026]</strong>
                </p>
            </div>

            <div class="space-y-10 text-gray-700 leading-8">

                <!-- Introduction -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. Introduction</h2>
                    <p>
                        Welcome to <strong>[Church Name] Church Management System (Church CMS)</strong>.
                        We respect your privacy and are committed to protecting your personal information.
                        This Privacy Policy explains how we collect, use, store, and protect the information
                        you provide when using our website and Church Management System.
                    </p>
                </div>

                <!-- Information We Collect -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. Information We Collect</h2>

                    <h3 class="text-lg font-semibold mb-2">Personal Information</h3>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Full Name</li>
                        <li>Email Address</li>
                        <li>Phone Number</li>
                        <li>Postal Address</li>
                        <li>Date of Birth (optional)</li>
                        <li>Family & Household Information</li>
                        <li>Ministry or Group Membership</li>
                        <li>Attendance Records</li>
                        <li>Donation History</li>
                        <li>Emergency Contact Information</li>
                        <li>Prayer Requests submitted voluntarily</li>
                    </ul>

                    <h3 class="text-lg font-semibold mt-6 mb-2">Account Information</h3>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Username</li>
                        <li>Encrypted Password</li>
                        <li>User Role & Permissions</li>
                        <li>Login History</li>
                    </ul>

                    <h3 class="text-lg font-semibold mt-6 mb-2">Technical Information</h3>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>IP Address</li>
                        <li>Browser Type</li>
                        <li>Operating System</li>
                        <li>Device Information</li>
                        <li>Pages Visited</li>
                        <li>Access Time & Date</li>
                    </ul>
                </div>

                <!-- Use of Information -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. How We Use Your Information</h2>

                    <ul class="list-disc pl-6 space-y-2">
                        <li>Manage church membership records.</li>
                        <li>Communicate announcements and events.</li>
                        <li>Process online donations.</li>
                        <li>Manage volunteer schedules and ministries.</li>
                        <li>Track attendance.</li>
                        <li>Respond to inquiries and prayer requests.</li>
                        <li>Improve website performance and user experience.</li>
                        <li>Protect against unauthorized access.</li>
                        <li>Comply with legal obligations.</li>
                    </ul>
                </div>

                <!-- Cookies -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. Cookies</h2>

                    <p>
                        Our website uses cookies to improve your browsing experience. Cookies help us:
                    </p>

                    <ul class="list-disc pl-6 mt-3 space-y-2">
                        <li>Keep users logged in.</li>
                        <li>Remember user preferences.</li>
                        <li>Improve website performance.</li>
                        <li>Analyze website traffic.</li>
                    </ul>

                    <p class="mt-4">
                        You may disable cookies through your browser settings, although some features
                        may not function correctly.
                    </p>
                </div>

                <!-- Sharing -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. Sharing Your Information</h2>

                    <p>We do <strong>not</strong> sell or rent your personal information.</p>

                    <p class="mt-4">Information may be shared only with:</p>

                    <ul class="list-disc pl-6 mt-3 space-y-2">
                        <li>Trusted service providers.</li>
                        <li>Payment gateway providers.</li>
                        <li>Government authorities when legally required.</li>
                        <li>Hosting and technical support providers.</li>
                    </ul>
                </div>

                <!-- Security -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. Data Security</h2>

                    <p>
                        We use industry-standard security measures including:
                    </p>

                    <ul class="list-disc pl-6 mt-3 space-y-2">
                        <li>SSL (HTTPS) encryption</li>
                        <li>Encrypted password storage</li>
                        <li>Role-based access control</li>
                        <li>Regular software updates</li>
                        <li>Secure server infrastructure</li>
                        <li>Routine backups</li>
                    </ul>
                </div>

                <!-- Retention -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. Data Retention</h2>

                    <p>
                        Personal information is retained only as long as necessary for church operations,
                        legal compliance, historical recordkeeping, and accounting purposes.
                    </p>
                </div>

                <!-- Rights -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. Your Rights</h2>

                    <ul class="list-disc pl-6 space-y-2">
                        <li>Access your personal information.</li>
                        <li>Request corrections.</li>
                        <li>Request deletion where permitted.</li>
                        <li>Withdraw consent when applicable.</li>
                        <li>Request a copy of your personal data.</li>
                    </ul>
                </div>

                <!-- Children -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">9. Children's Privacy</h2>

                    <p>
                        Children's information is collected only with the involvement of parents,
                        guardians, or authorized church representatives where appropriate.
                    </p>
                </div>

                <!-- Third Party -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">10. Third-Party Services</h2>

                    <ul class="list-disc pl-6 space-y-2">
                        <li>Payment Gateway Providers</li>
                        <li>Email Services</li>
                        <li>SMS Notification Providers</li>
                        <li>Cloud Hosting Services</li>
                        <li>Analytics Providers</li>
                    </ul>
                </div>

                <!-- External Links -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">11. External Links</h2>

                    <p>
                        Our website may contain links to third-party websites. We are not responsible
                        for their privacy practices or content.
                    </p>
                </div>

                <!-- Updates -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">12. Changes to This Policy</h2>

                    <p>
                        We may update this Privacy Policy periodically. Any changes will be posted on
                        this page along with the updated revision date.
                    </p>
                </div>

                <!-- Contact -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">13. Contact Us</h2>

                    <p>If you have questions regarding this Privacy Policy, please contact:</p>

                    <div class="mt-4 space-y-2">
                        <p><strong>GegoSoft Technologies (OPC) Private Limited</strong></p>
                        <p>8-3/17, Gangai Nathi Street
                  Mahatma Gandhi Nagar
                 Madurai – 625014 Tamil Nadu, India</p>
                        <p>Email: privacy@gegosoft.com</p>
                        <p>Phone: +91 984 303 3406</p>
                        <p>Website: https://gegosoft.com</p>
                    </div>
                </div>

            </div>

        </div>
  
    </div>
@endsection
