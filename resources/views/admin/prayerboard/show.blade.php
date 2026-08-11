@extends('layouts.admin.layout')

@section('content')
<div class="flex flex-row justify-between items-center mb-4">
    <div>
        <a href="{{ url('/admin/prayerboard') }}" class="text-sm text-indigo-600 hover:underline">← Prayer Board</a>
        <h1 class="admin-h1 mt-1">Prayer Detail</h1>
    </div>
    <span class="px-4 py-1 rounded-full text-sm font-bold
        {{ $prayer->status === 'PENDING'  ? 'bg-yellow-100 text-yellow-800' : '' }}
        {{ $prayer->status === 'ACTIVE'   ? 'bg-green-100 text-green-800'  : '' }}
        {{ $prayer->status === 'ANSWERED' ? 'bg-blue-100 text-blue-800'    : '' }}
        {{ $prayer->status === 'ENDED'    ? 'bg-gray-100 text-gray-700'    : '' }}
        {{ $prayer->status === 'REJECTED' ? 'bg-red-100 text-red-800'      : '' }}
        {{ $prayer->status === 'UNPUBLISHED' ? 'bg-gray-100 text-gray-700' : '' }}
    ">
        {{ $prayer->status_label }}
    </span>
</div>

<div class="grid grid-cols-3 gap-6">
    {{-- Main column --}}
    <div class="col-span-2 space-y-4">
        {{-- Prayer card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                    {{ strtoupper(substr($prayer->submitter_name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-lg">{{ $prayer->submitter_name }}</div>
                    @if($prayer->user && $prayer->user->email)
                    <div class="text-sm text-gray-500">{{ $prayer->user->email }}</div>
                    @endif
                    <div class="text-xs text-gray-400">Submitted {{ $prayer->created_at->format('M j, Y g:ia') }}</div>
                </div>
                @if($prayer->category)
                <span class="ml-auto px-3 py-1 rounded-full text-sm font-semibold flex-shrink-0"
                    style="background-color: {{ $prayer->category->gradient_start }}; color: {{ $prayer->category->display_color }}">
                    {{ $prayer->category->emoji }} {{ $prayer->category->name }}
                </span>
                @endif
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm font-bold text-gray-500 mb-1">Prayer Text</p>
                <p class="text-gray-800">{{ $prayer->text }}</p>
            </div>

            @if($prayer->original_text && $prayer->original_text !== $prayer->text)
            <div class="bg-yellow-50 rounded-lg p-4 mb-4 border border-yellow-200">
                <p class="text-xs font-bold text-yellow-700 mb-1">Original submission (before admin edit)</p>
                <p class="text-gray-700 text-sm italic">{{ $prayer->original_text }}</p>
            </div>
            @endif

            @if($prayer->answer_testimony)
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <p class="text-sm font-bold text-green-700 mb-1">📖 Testimony</p>
                <p class="text-green-900 italic">"{{ $prayer->answer_testimony }}"</p>
            </div>
            @endif

            @if($prayer->rejection_reason)
            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                <p class="text-sm font-bold text-red-700 mb-1">❌ Rejection Reason</p>
                <p class="text-red-900">{{ $prayer->rejection_reason }}</p>
            </div>
            @endif
        </div>

        {{-- Participant counts --}}
        @if($prayer->status !== 'PENDING' && $prayer->status !== 'REJECTED')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-3">🙏 Intercession Summary</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold mb-1">Members</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $prayer->member_count }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold mb-1">Guests</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $prayer->guest_count }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold mb-1">Anonymous</p>
                    <p class="text-3xl font-bold text-gray-600">{{ $prayer->anonymous_count }}</p>
                </div>
            </div>
            <p class="text-center text-sm text-gray-600 mt-3">
                <strong>{{ $prayer->total_participant_count }}</strong> total people praying
            </p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Timeline --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold text-gray-800 mb-3">📋 Timeline</h3>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Submitted</dt>
                    <dd class="text-gray-800">{{ $prayer->created_at->format('M j, Y g:ia') }}</dd>
                </div>
                @if($prayer->approved_at)
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Approved</dt>
                    <dd class="text-gray-800">{{ $prayer->approved_at->format('M j, Y g:ia') }}</dd>
                    @if($prayer->approver)<dd class="text-gray-500 text-xs">by {{ $prayer->approver->name }}</dd>@endif
                </div>
                @endif
                @if($prayer->expires_at)
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Expiry</dt>
                    <dd class="text-gray-800">{{ $prayer->expires_at->format('M j, Y') }}
                        @if($prayer->status === 'ACTIVE')
                        <span class="text-orange-600 font-semibold">({{ $prayer->days_remaining }} days left)</span>
                        @endif
                    </dd>
                </div>
                @endif
                @if($prayer->answered_at)
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Answered</dt>
                    <dd class="text-gray-800">{{ $prayer->answered_at->format('M j, Y g:ia') }}</dd>
                    @if($prayer->answerer)<dd class="text-gray-500 text-xs">by {{ $prayer->answerer->name }}</dd>@endif
                </div>
                @endif
                @if($prayer->rejected_at)
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Rejected</dt>
                    <dd class="text-gray-800">{{ $prayer->rejected_at->format('M j, Y g:ia') }}</dd>
                    @if($prayer->rejector)<dd class="text-gray-500 text-xs">by {{ $prayer->rejector->name }}</dd>@endif
                </div>
                @endif
                @if($prayer->pinned_at)
                <div>
                    <dt class="text-xs text-gray-500 font-semibold uppercase">Pinned</dt>
                    <dd class="text-gray-800">{{ $prayer->pinned_at->format('M j, Y') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Audit log --}}
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold text-gray-800 mb-3">🔍 Audit Log</h3>
            @forelse($auditLogs as $log)
            <div class="border-l-2 border-indigo-200 pl-3 mb-3">
                <p class="text-xs font-bold text-indigo-700">{{ $log->description }}</p>
                <p class="text-xs text-gray-500">
                    {{ $log->created_at->format('M j, Y g:ia') }}
                    @if($log->causer) · {{ $log->causer->name }} @endif
                </p>
                @if($log->properties && count($log->properties))
                <div class="mt-1 text-xs text-gray-500">
                    @foreach($log->properties as $key => $val)
                    @if(is_string($val) || is_numeric($val))
                    <span class="mr-2"><strong>{{ $key }}:</strong> {{ $val }}</span>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <p class="text-xs text-gray-400">No audit entries yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
