<div class="message-wrapper">
    <div class="message-container">
        @if (session('successmessage'))
            <div class="alert alert-success">
                <a href="#" data-dismiss="alert" aria-label="close">&times;
                    {{ session('successmessage') }}
                    {{ session()->forget('successmessage') }}
                </a>
            </div>
        @endif
        @if (session('failmessage'))
            <div class="alert alert-danger bg-red-200 text-red-500 text-sm p-2 rounded mb-2 w-1/2">
                <a href="#" data-dismiss="alert" aria-label="close">&times;
                    {{ session('failmessage') }}
                    {{ session()->forget('failmessage') }}
                </a>
            </div>
        @endif
    </div>
</div>
