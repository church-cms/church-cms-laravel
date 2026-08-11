@extends('layouts.admin.layout')

@section('content')
    <div class="relative admin-page-list">
        @include('partials._page_header', [
            'pageTitle' => 'Pages',
            'addUrl'    => url('/admin/page/add'),
            'addLabel'  => 'Add Page',
        ])
        @include('partials.message')
        <div class="bg-white shadow my-3 p-4">
        <page-list url="{{ url('/') }}" mode="admin"></page-list>
        </div>
    </div>

@endsection
