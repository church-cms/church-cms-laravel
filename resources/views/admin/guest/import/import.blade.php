@extends('layouts.admin.layout')
@section('content')
    <div class="container">
        <div class="container-body">
            <h1 class="admin-h1 mb-3 flex items-center">
                <a href="{{ url('/admin/guests') }}" class="rounded-full bg-gray-100 p-2" title="Back"><img
                        src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3"></a>
                <span class="mx-3">Import</span>
            </h1>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading border border-gray-400 bg-white px-4">
                <div class="flex flex-col">
                    <div class="mt-3">
                        <a href="{{ url('admin/downloadformat') }}" id="sample"
                            class="no-underline text-white px-4 my-3 mx-3 flex items-center custom-green py-1 w-11/12 lg:w-1/5 md:w-1/3">Download
                            Sample Format</a>
                    </div>
                </div>
                <form style="padding: 10px;margin-bottom: unset;" action="{{ url('admin/importGuests') }}"
                    class="form-horizontal" method="post" enctype="multipart/form-data">
                    @csrf
                    @include('partials.message')
                    {{ session()->forget('count') }}
                    {{ session()->forget('insertedcount') }}
                    <div class="flex flex-col">
                        <div>
                            <input type="file" id="file" name="import_file">
                        </div>
                        <span class="text-red-500 text-xs font-semibold">{{ $errors->first('import_file') }}</span>
                        <div class="my-3">
                            <button class="btn btn-primary submit-btn" id="import">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
