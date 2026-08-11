<div class="members-table-container bg-white rounded shadow p-4">
    <div class="my-4">
        <!-- Search Box -->
        <div class="mb-4">
            <input type="text" id="table-search" class="border px-4 py-2 text-sm border-gray-400 w-full lg:w-1/3 rounded bg-white shadow" placeholder="Search members by name, email, or phone...">
        </div>

        <div class="w-full mb-4 p-4 bg-gradient-to-r from-blue-50 to-blue-50 rounded-lg border border-blue-100 shadow-sm" id="action-buttons-container" style="display: none;">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-sm">
                    <div class="font-semibold text-blue-700 flex items-center gap-2">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold" id="selected-count-text">0</span>
                        <span id="selected-count">member(s) selected</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-3 ml-4 border-l border-blue-200 pl-4">
                        <button id="select-all-checkbox" class="text-xs px-3 py-1 rounded bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors duration-200" title="Select All">
                            <i class="fas fa-check-double mr-1"></i> Select All
                        </button>
                        <button id="select-none-checkbox" class="text-xs px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium transition-colors duration-200" title="Deselect All">
                            <i class="fas fa-times mr-1"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button id="send-message-btn" class="flex-1 sm:flex-none bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-envelope"></i>
                        Send Message
                    </button>
                    <button id="subscribe-newsletter-btn" class="flex-1 sm:flex-none bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-2.5 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-star"></i>
                        Subscribe
                    </button>
                </div>
            </div>
        </div>

        @if(count($members) > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-3 py-2 w-12">
                            <input type="checkbox" class="member-checkbox" value="all" id="checkbox-all">
                        </th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-32">Name</th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-32">Email</th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-24">Phone</th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-40">Profession</th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-28">Location</th>
                        <th class="border border-gray-300 px-3 py-2 text-left w-20">Status</th>
                        <th class="border border-gray-300 px-3 py-2 text-center w-16">Action</th>
                    </tr>
                </thead>
                <tbody id="members-table-body">
                    @foreach($members as $member)
                    <tr class="member-row hover:bg-gray-50 border-b border-gray-300" data-member-id="{{ $member['id'] }}" data-search-text="{{ strtolower($member['fullname'] . ' ' . $member['email'] . ' ' . ($member['mobile_no'] ?? '')) }}">
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <input type="checkbox" class="member-checkbox" value="{{ $member['id'] }}">
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">
                            <div class="flex items-center gap-2">
                                <img src="{{ $member['avatar'] }}" alt="{{ $member['fullname'] }}" class="w-8 h-8 rounded-full flex-shrink-0">
                                <a href="{{ url('/admin/member/show/' . $member['name']) }}" class="text-blue-600 hover:underline font-semibold truncate">
                                    {{ $member['fullname'] }}
                                </a>
                            </div>
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">{{ $member['email'] }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">{{ $member['mobile_no'] ?? '-' }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">
                            @if($member['sub_occupation'])
                            {{ $member['profession'] }} ({{ $member['sub_occupation'] }})
                            @else
                            {{ $member['profession'] ?? '-' }}
                            @endif
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">{{ $member['state'] ?? '-' }} - {{ $member['city'] ?? '-' }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <a href="{{ url('/admin/member/show/' . $member['name']) }}" class="text-blue-600 hover:underline text-xs font-semibold">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($paginator && $paginator->lastPage() > 1)
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm text-gray-600">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} members
            </div>
            <div class="flex gap-2">
                @if($paginator->onFirstPage())
                <span class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-400 cursor-not-allowed">Previous</span>
                @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100" onclick="clearSelections();">Previous</a>
                @endif

                @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if($page == $paginator->currentPage())
                <span class="px-3 py-1 text-sm border border-blue-500 rounded bg-blue-500 text-white">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100" onclick="clearSelections();">{{ $page }}</a>
                @endif
                @endforeach

                @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100" onclick="clearSelections();">Next</a>
                @else
                <span class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-400 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
        @endif
        @else
        <div class="my-8 text-center text-gray-600 py-8">
            <p class="text-lg">No members found</p>
        </div>
        @endif
    </div>
</div>

<!-- Modals for actions -->
<div class="modal modal-mask" id="message-modal" style="display: none;">
    <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-md px-4 mx-auto">
            <div id="success-alert" class="alert alert-success" style="display: none;"></div>
            <div class="modal-header flex justify-between items-center">
                <h2>Send Message</h2>
                <button class="modal-default-button text-2xl py-1 close-modal">&times;</button>
            </div>
            <form id="message-form">
                <div class="modal-body">
                    <div class="flex flex-col lg:flex-row">
                        <div class="tw-form-group w-full">
                            <div class="flex gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="mode" id="mail" value="mail" checked class="mode-select">
                                    <label for="mail" class="text-sm mx-2 cursor-pointer">Email</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="mode" id="notification" value="notification" class="mode-select">
                                    <label for="notification" class="text-sm mx-2 cursor-pointer">Notification</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="mode" id="sms" value="sms" class="mode-select">
                                    <label for="sms" class="text-sm mx-2 cursor-pointer">SMS</label>
                                </div>
                            </div>
                            <span class="text-red-500 text-xs font-semibold error-message" style="display: none;"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-body" id="subject-container">
                    <div class="flex flex-col">
                        <label for="subject" class="tw-form-label">Subject</label>
                        <input type="text" name="subject" id="subject" class="tw-form-control w-full" placeholder="Enter Subject">
                        <span class="text-red-500 text-xs font-semibold error-message" style="display: none;"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="flex flex-col">
                        <label for="message-text" class="tw-form-label">Message</label>
                        <textarea name="message" id="message-text" class="tw-form-control w-full" rows="3" placeholder="Enter Message"></textarea>
                        <span class="text-red-500 text-xs font-semibold error-message" style="display: none;"></span>
                    </div>
                </div>

                <div class="modal-body" id="attachments-container">
                    <div class="flex flex-col">
                        <label for="attachments" class="tw-form-label">Attachments</label>
                        <input type="file" name="attachments" id="attachments" class="tw-form-control w-full">
                        <span class="text-red-500 text-xs font-semibold error-message" style="display: none;"></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_later" id="send-later" class="tw-form-control">
                        <label for="send-later" class="tw-form-label ml-2 cursor-pointer">Send Later</label>
                    </div>
                </div>

                <div class="modal-body" id="datetime-container" style="display: none;">
                    <div class="flex">
                        <label for="executed-at" class="tw-form-label">Date Time</label>
                        <input type="datetime-local" name="executed_at" id="executed-at" class="tw-form-control w-full rounded">
                        <span class="text-red-500 text-xs font-semibold error-message" style="display: none;"></span>
                    </div>
                </div>

                <div class="my-6">
                    <button type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-mask" id="newsletter-modal" style="display: none;">
    <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-md px-4 mx-auto">
            <div id="newsletter-success-alert" class="alert alert-success" style="display: none;"></div>
            <div class="modal-header flex justify-between items-center">
                <h2>Subscribe News Letter</h2>
                <button class="modal-default-button text-2xl py-1 close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-gray-700">Are you sure you want to subscribe the selected members to the newsletter?</p>
            </div>
            <div class="my-6">
                <button id="subscribe-confirm-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">
                    Subscribe
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to clear selections before pagination
    function clearSelections() {
        document.querySelectorAll('input[type="checkbox"].member-checkbox').forEach(cb => {
            cb.checked = false;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectedMembers = new Set();
        const tableSearchInput = document.getElementById('table-search');
        const tableBody = document.getElementById('members-table-body');
        const selectAllCheckbox = document.getElementById('checkbox-all');
        const selectAllButton = document.getElementById('select-all-checkbox');
        const selectNoneButton = document.getElementById('select-none-checkbox');
        const messageModal = document.getElementById('message-modal');
        const newsletterModal = document.getElementById('newsletter-modal');
        const sendMessageBtn = document.getElementById('send-message-btn');
        const subscribeNewsletterBtn = document.getElementById('subscribe-newsletter-btn');
        const selectedCountText = document.getElementById('selected-count-text');
        const selectedCountLabel = document.getElementById('selected-count');
        const actionButtonsContainer = document.getElementById('action-buttons-container');
        const sendLaterCheckbox = document.getElementById('send-later');
        const datetimeContainer = document.getElementById('datetime-container');
        const subjectContainer = document.getElementById('subject-container');
        const attachmentsContainer = document.getElementById('attachments-container');
        const modeRadios = document.querySelectorAll('input[name="mode"]');
        const messageForm = document.getElementById('message-form');

        // Get all member checkboxes (excluding select-all)
        function getMemberCheckboxes() {
            return document.querySelectorAll('input[type="checkbox"].member-checkbox[value!="all"]');
        }

        // Table search functionality
        if (tableSearchInput) {
            tableSearchInput.addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tr.member-row');
                tableRows.forEach(row => {
                    const searchContent = row.getAttribute('data-search-text') || '';
                    if (searchText === '' || searchContent.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                updateUI();
            });
        }

        // Update UI based on selection
        function updateUI() {
            const count = selectedMembers.size;
            const hasMembers = count > 0;

            if (hasMembers) {
                selectedCountText.textContent = count;
                selectedCountLabel.textContent = count === 1 ? 'member selected' : 'member(s) selected';
                actionButtonsContainer.style.display = 'flex';
            } else {
                actionButtonsContainer.style.display = 'none';
            }

            // Update select all checkbox state
            const memberCheckboxes = getMemberCheckboxes();
            const visibleCheckboxes = Array.from(memberCheckboxes).filter(cb => cb.closest('tr').style.display !== 'none');
            const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
            if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
        }

        // Event delegation for member checkboxes
        if (tableBody) {
            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('member-checkbox') && e.target.value !== 'all') {
                    const memberId = e.target.value;
                    if (e.target.checked) {
                        selectedMembers.add(memberId);
                    } else {
                        selectedMembers.delete(memberId);
                    }
                    updateUI();
                }
            });
        }

        // Handle select all checkbox (in table header)
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const memberCheckboxes = getMemberCheckboxes();
                memberCheckboxes.forEach(checkbox => {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = this.checked;
                        const memberId = checkbox.value;
                        if (this.checked) {
                            selectedMembers.add(memberId);
                        } else {
                            selectedMembers.delete(memberId);
                        }
                    }
                });
                updateUI();
            });
        }

        // Handle select all button
        if (selectAllButton) {
            selectAllButton.addEventListener('click', function(e) {
                e.preventDefault();
                const memberCheckboxes = getMemberCheckboxes();
                memberCheckboxes.forEach(checkbox => {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = true;
                        selectedMembers.add(checkbox.value);
                    }
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = true;
                updateUI();
            });
        }

        // Handle select none button
        if (selectNoneButton) {
            selectNoneButton.addEventListener('click', function(e) {
                e.preventDefault();
                const memberCheckboxes = getMemberCheckboxes();
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    selectedMembers.delete(checkbox.value);
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateUI();
            });
        }

        // Mode radio button change
        modeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isEmail = this.value === 'mail';
                if (subjectContainer) subjectContainer.style.display = isEmail ? 'block' : 'none';
                if (attachmentsContainer) attachmentsContainer.style.display = isEmail ? 'block' : 'none';
            });
        });

        // Send later checkbox
        if (sendLaterCheckbox) {
            sendLaterCheckbox.addEventListener('change', function() {
                if (datetimeContainer) datetimeContainer.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Send message button
        if (sendMessageBtn) {
            sendMessageBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (selectedMembers.size > 0) {
                    messageModal.style.display = 'flex';
                    if (messageForm) messageForm.reset();
                    // Add smooth animation
                    messageModal.style.animation = 'slideIn 0.3s ease-out';
                } else {
                    showNotification('Please select at least one member', 'error');
                }
            });
        }

        // Subscribe newsletter button
        if (subscribeNewsletterBtn) {
            subscribeNewsletterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (selectedMembers.size > 0) {
                    newsletterModal.style.display = 'flex';
                    newsletterModal.style.animation = 'slideIn 0.3s ease-out';
                } else {
                    showNotification('Please select at least one member', 'error');
                }
            });
        }

        // Close modals
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                messageModal.style.display = 'none';
                newsletterModal.style.display = 'none';
            });
        });

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });

        // Message form submission
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData();
                const mode = document.querySelector('input[name="mode"]:checked').value;
                formData.append('mode', mode);
                formData.append('subject', document.getElementById('subject').value || '');
                formData.append('message', document.getElementById('message-text').value);
                formData.append('send_later', sendLaterCheckbox && sendLaterCheckbox.checked ? 1 : 0);
                formData.append('executed_at', document.getElementById('executed-at').value || '');
                formData.append('count', selectedMembers.size);
                formData.append('selected', Array.from(selectedMembers).join(','));
                formData.append('membership_type', 'member');

                const attachmentsInput = document.getElementById('attachments');
                if (attachmentsInput && attachmentsInput.files.length > 0) {
                    formData.append('attachments', attachmentsInput.files[0]);
                }

                // Show loading
                const sendBtn = messageForm.querySelector('button[type="submit"]');
                const originalText = sendBtn.textContent;
                const originalHTML = sendBtn.innerHTML;
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                sendBtn.classList.add('opacity-75', 'cursor-not-allowed');

                axios.post('/admin/member/sendMessageToAll', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    const successAlert = document.getElementById('success-alert');
                    if (successAlert) {
                        successAlert.textContent = response.data.message;
                        successAlert.style.display = 'block';
                        successAlert.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-4', 'py-3', 'rounded', 'mb-4');
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }).catch(error => {
                    showNotification('Error sending message. Please try again.', 'error');
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = originalHTML;
                    sendBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                });
            });
        }

        // Notification function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 8px;
                font-weight: 500;
                z-index: 10000;
                animation: slideInRight 0.3s ease-out;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;

            if (type === 'error') {
                notification.style.cssText += 'background-color: #fee; color: #c00; border: 1px solid #fcc;';
            } else if (type === 'success') {
                notification.style.cssText += 'background-color: #efe; color: #060; border: 1px solid #cfc;';
            } else {
                notification.style.cssText += 'background-color: #eef; color: #006; border: 1px solid #ccf;';
            }

            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Newsletter subscription
        const subscribeConfirmBtn = document.getElementById('subscribe-confirm-btn');
        if (subscribeConfirmBtn) {
            subscribeConfirmBtn.addEventListener('click', function() {
                const formData = new FormData();
                formData.append('count', selectedMembers.size);

                let i = 0;
                selectedMembers.forEach(memberId => {
                    formData.append(`user${i}`, memberId);
                    i++;
                });

                // Show loading
                const originalHTML = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Subscribing...';
                this.classList.add('opacity-75', 'cursor-not-allowed');

                axios.post('/admin/members/subscribe', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    const successAlert = document.getElementById('newsletter-success-alert');
                    if (successAlert) {
                        successAlert.textContent = response.data.message;
                        successAlert.style.display = 'block';
                        successAlert.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-4', 'py-3', 'rounded', 'mb-4');
                    }
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 1500);
                }).catch(error => {
                    showNotification('Error subscribing to newsletter. Please try again.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                    this.classList.remove('opacity-75', 'cursor-not-allowed');
                });
            });
        }

        // Initialize UI
        updateUI();
    });
</script>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }

    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-container {
        background-color: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-height: 90vh;
        overflow-y: auto;
        max-width: 500px;
        width: 90%;
        animation: slideIn 0.3s ease-out;
    }

    .modal-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #999;
        cursor: pointer;
        padding: 0;
        transition: color 0.2s;
    }

    .close-modal:hover {
        color: #333;
    }

    .modal-body {
        margin-bottom: 1rem;
    }

    .alert {
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-radius: 0.375rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .tw-form-label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #1f2937;
        font-size: 0.95rem;
    }

    .tw-form-control {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
        margin-bottom: 1rem;
    }

    .tw-form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: #f0f7ff;
    }

    .modal-footer {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 8px 12px rgba(59, 130, 246, 0.3);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover:not(:disabled) {
        background-color: #d1d5db;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #action-buttons-container button {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
    }

    #action-buttons-container button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }

    #action-buttons-container button:active:not(:disabled) {
        transform: translateY(0);
    }

    .members-table-container {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>