@extends('theme::layout')

@section('title', $_church->name ?? config('app.name'))

@section('content')

    @include('theme::_hero_banner', [
        'heroTitle'    => $_church->name ?? config('app.name'),
        'heroSubtitle' => $_church->quotes ?? null,
    ])

    @foreach($widgets as $widget)
        {!! $widget->content !!}
    @endforeach

@endsection
