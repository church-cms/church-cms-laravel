@component('mail::message')

Hi {{ $username }},

<p>{{$message }}
    @component('mail::button', ['url' => $resetlink])
    {{ $resetlinktext }}
    @endcomponent
</p>
{{ $thanks_regards_text }},<br/>
{{ $sender }}
    @component('mail::footer')
    <p>{{ trans('mail.footer_copying_text') }} {{ $resetlink }}</p>
    @endcomponent
@endcomponent