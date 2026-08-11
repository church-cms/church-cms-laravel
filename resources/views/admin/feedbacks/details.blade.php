@if (count($messages))
    <div class="w-full px-4">
        <div class="bg-white shadow leading-loose px-3 py-3 overflow-y-auto">
            @foreach ($messages as $message)
                @if ($message->feedback->user_id == $message->user_id)
                    <p>{{ ucwords(str_replace('_', ' ', str_replace('/', ' / ', $message->category))) }}</p>
                    <p class="message-bubble-text-right bg-orange-100 rounded-full inline-block px-2 py-1 my-1 mb-2">
                        <small class="mr-4"> {!! $message->message !!}</small>
                        <small>{{ $message->created_at->diffForHumans() }}</small>
                    </p>
                    <p class="flex flex-row">
                        @foreach ($message->FilePath as $file)
                            <img src="{{ $file }}" class="w-40 h-40 px-3">
                        @endforeach
                    </p>
                @endif
            @endforeach
        </div>
    </div>
@endif
