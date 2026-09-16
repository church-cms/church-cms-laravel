@extends('layouts.admin.layout')
@section('content')
<div class="w-full flex flex-col">
    <h1 class="text-xl font-semibold mb-6 text-gray-700">Settings</h1>
    <div class="w-full main-content bg-white flex h-auto">
        <div class="flex flex-col lg:flex-row w-full">
            @include('layouts.admin.settingsbar')
            <div class="flex-1 px-8 py-6 min-w-0">
                @include('partials._page_header', ['pageTitle' => 'Maintenance Settings'])
                @include('partials.message')

                <form method="POST" action="{{ url('/admin/settings/maintenancesettings') }}">
                    @csrf
                    <div class="bg-white border border-gray-200 rounded shadow-sm mb-6 max-w-3xl">
                        <table class="form-table w-full">
                            <tbody>

                                @php
                                $rows = [
                                ['key' => 'maintenance', 'name' => 'maintenance', 'label' => 'Maintenance Mode', 'desc' => 'Show a maintenance page to visitors while you make changes.', 'default' => 0]

                                ];
                                $publicRows = [
                                ['key' => 'member_web_login', 'name' => 'member_web_login', 'label' => 'Member Web Login', 'desc' => 'Allow church members to log in via the website. Disable to direct members to the mobile app only.', 'default' => 1],
                                ['key' => 'guest_login', 'name' => 'guest_login', 'label' => 'Guest Login', 'desc' => 'Allow registered guests to log in to submit prayer requests, help requests, and comments.', 'default' => 1],
                                ['key' => 'guest_registration','name' => 'guest_registration','label' => 'Guest Registration', 'desc' => 'Allow new visitors to self-register as guests on the public website.', 'default' => 1],
                                ];
                                @endphp

                                @foreach($rows as $row)
                                <tr class="border-b border-gray-100">
                                    <th class="w-56 px-5 py-5 text-left align-middle">
                                        <p class="font-semibold text-sm text-gray-800">{{ $row['label'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 font-normal leading-relaxed">{{ $row['desc'] }}</p>
                                    </th>
                                    <td class="px-6 py-5 align-middle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="{{ $row['name'] }}" value="1"
                                                {{ Config::get('settings.' . $row['key'], $row['default']) == 1 ? 'checked' : '' }}>
                                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                            <span class="toggle-text">{{ Config::get('settings.' . $row['key'], $row['default']) == 1 ? 'ON' : 'OFF' }}</span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach

                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <td colspan="2" class="px-6 py-3">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Public Website Access</p>
                                    </td>
                                </tr>

                                @foreach($publicRows as $i => $row)
                                <tr class="{{ $i < count($publicRows) - 1 ? 'border-b border-gray-100' : '' }}">
                                    <th class="w-56 px-5 py-5 text-left align-middle">
                                        <p class="font-semibold text-sm text-gray-800">{{ $row['label'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 font-normal leading-relaxed">{{ $row['desc'] }}</p>
                                    </th>
                                    <td class="px-6 py-5 align-middle">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="{{ $row['name'] }}" value="1"
                                                {{ Config::get('settings.' . $row['key'], $row['default']) == 1 ? 'checked' : '' }}>
                                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                            <span class="toggle-text">{{ Config::get('settings.' . $row['key'], $row['default']) == 1 ? 'ON' : 'OFF' }}</span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="mb-8">
                        <input type="submit" value="Save Changes" name="submit" class="btn btn-primary submit-btn cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update ON/OFF text live when toggle changes
    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var text = this.closest('.toggle-switch').querySelector('.toggle-text');
            if (text) text.textContent = this.checked ? 'ON' : 'OFF';
        });
    });
</script>
@endpush