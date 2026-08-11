    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-indigo-500 mb-6">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-indigo-100 to-purple-100 px-6 py-4 border-b-2 border-indigo-200">
            <div class="flex justify-between items-start gap-3">
                <div>
                    <h3 class="font-bold text-gray-900">Posted by: {{ $item->submitter_name }}</h3>
                    <p class="text-xs text-gray-500">Posted {{ $item->created_at->format('M j, Y') }}</p>
                </div>
                @if($item->category)
                <span class="flex-shrink-0 px-3 py-1 rounded-full text-sm font-semibold"
                    style="background-color: {{ $item->category->gradient_start }}; color: {{ $item->category->display_color }}">
                    {{ $item->category->emoji }} {{ $item->category->name }}
                </span>
                @endif
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6">
            <p class="text-gray-700 leading-relaxed text-sm mb-5">"{{ $item->text }}"</p>

            {{-- Expiry bar --}}
            @if($item->expires_at)
            <div class="mb-4 p-3 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-900">⏰ Expires in:</span>
                    <span class="text-lg font-bold text-orange-600">{{ $item->days_remaining }} days</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-2 mt-2">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full"
                        style="width: {{ $item->expiry_percentage }}%"></div>
                </div>
            </div>
            @endif

            {{-- Participant counts --}}
            <div class="mb-4 grid grid-cols-3 gap-3">
                <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold">Members</p>
                    <p class="text-2xl font-bold text-indigo-600" data-prayer-member="{{ $item->id }}">{{ $item->member_count }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg border border-purple-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold">Guests</p>
                    <p class="text-2xl font-bold text-purple-600" data-prayer-guest="{{ $item->id }}">{{ $item->guest_count }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-center">
                    <p class="text-xs text-gray-600 font-semibold">Anonymous</p>
                    <p class="text-2xl font-bold text-gray-600" data-prayer-anonymous="{{ $item->id }}">{{ $item->anonymous_count }}</p>
                </div>
            </div>

            <p class="text-xs text-gray-600 text-center mb-4">
                <strong>Total:</strong> <span data-prayer-total="{{ $item->id }}">{{ $item->total_participant_count }}</span> people praying
            </p>

            @php

            $userId = Auth::id();

            $hasLifted = App\Models\PrayerParticipant::where('prayer_id', $item->id)
            ->where('user_id', $userId)
            ->exists();

            @endphp
            @if($hasLifted==0)
            @include('theme::_lift_prayer_btn', ['item' => $item])
            @endif

        </div>
    </div>