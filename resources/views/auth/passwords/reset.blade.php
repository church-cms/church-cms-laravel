@extends('layouts.app')

@section('content')
    <div class="flex relative" style="min-height: calc(100vh - 118px);">
        <div class="flex justify-center w-full">
            <div class="w-2/5 mx-auto my-10">
                <div class="bg-white shadow border border-grey p-5">
                    <div class="">
                        <h1 class="admin-h1 mb-3">{{ __('Reset Password') }}</h1>
                    </div>

                    <div class="">
                        <form method="POST" action="{{ url('/password/reset/') }}"
                            aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <label for="email" class="tw-form-label">{{ __('E-Mail Address') }}</label>

                                <div class="my-2">
                                    <input id="email" type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} tw-form-control w-full"
                                        name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-xs text-red-600" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="tw-form-label">{{ __('Password') }}</label>

                                <div class="my-2">
                                    <input id="password" type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} tw-form-control w-full"
                                        name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-xs text-red-600" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="tw-form-label">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="tw-form-control w-full"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary submit-btn">
                                        {{ __('Reset Password') }}
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
