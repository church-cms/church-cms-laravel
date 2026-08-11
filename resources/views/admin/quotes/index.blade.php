@extends('layouts.admin.layout')
@section('content')
    <portal-target name="add_quote"></portal-target>
    <div class="bg-white my-3">
        @include('partials.message')
        <quote-tab url="{{ url('/') }}"></quote-tab>
        <portal-target name="quote_tab"></portal-target>
    </div>
    </div>
@endsection
