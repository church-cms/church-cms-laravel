@extends('layouts.admin.layout')

@section('content')

    <create-event url="{{ url('/') }}" count="{{ $count }}"
        ></create-event>
    <edit-event url="{{ url('/') }}"></edit-event>
    <div class="py-5 bg-white shadow px-3">
        <show-event url="{{ url('/') }}" count="{{ $count }}" events="{{ $events }}" mode="admin"></show-event>
    </div>
    <event-popup url="{{ url('/') }}" mode="admin"></event-popup>
    <portal-target name="eventpopup"></portal-target>
@endsection
