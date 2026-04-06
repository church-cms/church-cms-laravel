@extends('layouts.empty')

@section('content')
    <div class="w-full lg:w-1/3 md:w-1/2 p-8 mx-auto login-form">
        @include('partials.message')
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header uppercase text-gray-200 text-center text-lg font-semibold tracking-widest">
                    {{ __('Login') }}</div>

                <div class="card-body">

                    @if (\Config::get('settings.login_status') == 0)

                        <div class="alert-box success">
                            Login page under maintenance
                        </div>

                    @else
                        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                            @csrf
                            <div class="lg:px-5 md:px-5">
                                <div class="form-group my-8">
                                    <!-- <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label> -->

                                    <div class="input-group flex  w-full">
                                        <span class="input-group-addon w-12 flex items-center justify-center">
                                            <img src="{{ url('uploads/icons/envelope.svg') }}" class="w-4 h-4">
                                        </span>
                                        <input id="email" type="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm"
                                            placeholder="E-Mail Address" name="email" value="{{ old('email') }}" required
                                            autofocus>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group mt-8 mb-3">
                                    <!-- <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> -->

                                    <div class="input-group flex  w-full">
                                        <span class="input-group-addon w-12 flex items-center justify-center">
                                            <img src="{{ url('uploads/icons/key.svg') }}" class="w-4 h-4">
                                        </span>
                                        <input id="password" placeholder="Password" type="password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm"
                                            name="password" required>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback text-red-500 text-xs" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="flex justify-between">
                                        <div class="form-check flex items-center">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label text-gray-200 text-sm mx-1" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group  my-8 flex justify-between">
                                    <div class="">
                                        <button type="submit"
                                            class="btn btn-primary login-btn text-gray-200 uppercase px-8 py-1 tracking-wider"
                                            id="login">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                    <div>
                                        <a class="text-gray-200 text-xs" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
