@extends('layouts.empty')
{{-- @extends('layouts.app') --}}
@section('content')
    <!-- <div class="flex relative" style="min-height: calc(100vh - 118px);"> -->
    <div class="w-full lg:w-1/3 p-8 mx-auto login-form">
        <div class="flex justify-center w-full">
            <div class="w-full">
                <!-- w-2/5 mx-auto my-10 -->
                <div class="p-5">
                    <!--  bg-white shadow border border-grey  -->
                    <div class="card-header uppercase text-gray-200 text-center text-lg font-semibold tracking-widest">
                        {{ __('Reset Password') }}</div>

                    <div class="">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}"
                            aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <div class="form-group row">
                                <!--  <label for="email" class="tw-form-label">{{ __('E-Mail Address') }}</label> -->
                                <div class="form-group my-8">
                                    <div class="input-group flex  w-full">
                                        <span class="input-group-addon w-12 flex items-center justify-center">
                                            <img src="{{ url('uploads/icons/envelope.svg') }}"
                                                class="w-4 h-4"></span>

                                        <input id="email" type="email" placeholder="E-Mail Address" name="email" value=""
                                            required="required" autofocus="autofocus"
                                            class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} px-2 py-2 w-full"
                                            name="email" value="{{ old('email') }}" required>

                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-xs text-red-600" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--  <div class="mt-2 mb-3">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} tw-form-control w-full" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-xs text-red-600" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div> -->
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit"
                                        class="btn btn-primary login-btn text-gray-200 uppercase px-8 py-1 tracking-wider">
                                        <!-- btn btn-primary submit-btn -->
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <style>
        .login-page {
            background: url('../uploads/static/banner1.png') !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            min-height: 100vh;
            padding: 40px 0;
            position: relative;
        }

    </style>
@endpush
