@extends('layouts.admin.layout')

@section('content')
    @include('partials._page_header', [
        'pageTitle' => 'FAQ',
        'addUrl'    => url('/admin/faq/create'),
        'addLabel'  => 'Add FAQ',
    ])

    <div class="bg-white my-3">
        @include('partials.message')
        <faq-list url="{{ url('/') }}"></faq-list>
    </div>
@endsection
