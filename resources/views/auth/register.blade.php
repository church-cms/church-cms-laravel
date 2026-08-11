@extends('layouts.empty')

@section('content')
    <div class="w-full lg:w-1/2 md:w-3/4 xl:w-2/5 lg:p-8 md:p-8 mx-auto ">
        <!--  login-form -->
        <div class="card-header uppercase text-gray-200 text-center text-lg font-semibold tracking-widest">
            {{ __('Register your Church here') }}</div>
        <div class="card-body">
            @if (\Config::get('settings.register') == 1)
                <div class="alert-box danger">Register page is under maintenance!!!</div>
            @else
                @include('partials.message')
                <form id="msform" method="POST" url="">
                    @csrf
                    <register-tab url="{{ url('/') }}"></register-tab>
                    <portal-target name="register_tab"></portal-target>
                    <portal to="google_tab">
                        <div class="flex lg:items-center flex-col lg:flex-row">
                            <div class="g-recaptcha" id="feedback-recaptcha" name="feedback-recaptch"
                                data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                            <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">
                                <p>{{ $errors->first('g-recaptcha-response') }}</p>
                            </span>
                        </div>
                    </portal>
                    <!-- progressbar -->
                    <!-- <ul id="progressbar">
                            <li class="active w-1/2 lg:w-2/5 md:w-2/5"><span class="ml-3">Church Info</span></li>
                            <li class="w-1/2 lg:w-2/5 md:w-2/5" style="margin-left: -45px;"><span class="ml-3">Login Info</span></li>
                        </ul> -->
                    <!-- fieldsets -->
                    <!-- <fieldset>
                            <div class="form-group my-6">
                                <div class="">
                                    <input id="church_name" type="text" class="form-control px-2 py-2 w-full text-sm border" name="church_name" value="{{ old('church_name') }}" placeholder="Church Name">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('church_name') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="input-group flex  w-full">
                                    <input id="address" type="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="address" value="{{ old('address') }}" placeholder="Address">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('address') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="">
                                    <select name="state" class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected="selected"' : '' }}>{{ ucwords($state->name) }}</option>
                                        @endforeach 
                                    </select>
                                    <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('state') }}</span>
                                </div>
                            </div>

                            <div class="form-group my-6">
                                <div class="">
                                    <select name="city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border">
                                        <option value="">Select City</option>
                                        @foreach ($city_names as $city)
                                            <option value="{{ $city->name }}" {{ old('city') == $city->name ? 'selected="selected"' : '' }}>{{ ucwords($city->name) }}</option>
                                        @endforeach 
                                    </select>
                                    <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('city') }}</span>
                                </div>
                            </div>

                            <div class="form-group my-6">
                                <div class="">
                                    <input id="pincode" type="text" class="form-control{{ $errors->has('pincode') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="pincode" value="{{ old('pincode') }}" placeholder="Pincode">
                                    <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('pincode') }}</span>
                                </div>
                            </div>
                            <input type="button" name="next" class="btn btn-primary login-btn text-gray-200 uppercase px-8 py-1 tracking-wider next action-button cursor-pointer next" value="Next" />
                        </fieldset>
                        <fieldset>
                            <div class="form-group my-6">
                                <div class="input-group flex w-full">
                                    <span class="input-group-addon w-12 flex items-center justify-center">
                                        <img src="{{ url('uploads/icons/user.svg') }}" class="w-4 h-4">
                                    </span>
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="name" value="{{ old('name') }}" placeholder="Name">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="input-group flex w-full">
                                    <span class="input-group-addon w-12 flex items-center justify-center">
                                        <img src="{{ url('uploads/icons/mobile.svg') }}" class="w-4 h-4" style="filter: brightness(0) invert(1);">
                                    </span>
                                    <input id="mobile_no" type="text" class="form-control{{ $errors->has('mobile_no') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Mobile Number">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('mobile_no') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="input-group flex w-full">
                                    <span class="input-group-addon w-12 flex items-center justify-center">
                                        <img src="{{ url('uploads/icons/envelope.svg') }}" class="w-4 h-4">
                                    </span>
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="email" value="{{ old('email') }}" placeholder="E-Mail Address">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('email') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="input-group flex w-full">
                                    <span class="input-group-addon w-12 flex items-center justify-center">
                                        <img src="{{ url('uploads/icons/lock.svg') }}" class="w-4 h-4">
                                    </span>
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} px-2 py-2 w-full text-sm border" name="password" placeholder="Password">
                                </div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('password') }}</span>
                            </div>

                            <div class="form-group my-6">
                                <div class="input-group flex w-full">
                                    <span class="input-group-addon w-12 flex items-center justify-center">
                                        <img src="{{ url('uploads/icons/lock.svg') }}" class="w-4 h-4">
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control px-2 py-2 w-full text-sm border" name="password_confirmation" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="flex lg:items-center flex-col lg:flex-row">
                                <div class="w-full py-1">
                                    <div class="form-check flex items-center">
                                        <input type="checkbox" class="form-check-input" name="termsandcondn" value="1" @if (old('termsandcondn') == 1) checked @endif>
                                        <label for="termsandcondn" class="form-control px-2 py-2 w-full text-sm">I Agree to
                                            <a href="{{ url('/terms-of-service') }}" target="_blank">
                                                <b>Terms and Conditions</b>
                                            </a>
                                        </label>
                                    </div>
                                    <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">{{ $errors->first('termsandcondn') }}</span>
                                </div>
                            </div>
                            <div class="flex lg:items-center flex-col lg:flex-row">
                                <div class="g-recaptcha" id="feedback-recaptcha" name="feedback-recaptch" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert">
                                  <p>{{ $errors->first('g-recaptcha-response') }}</p>
                                </span>
                            </div>

                            <div class="form-group my-6">
                                <div class="flex items-center">
                                    <button type="submit" class="btn btn-primary login-btn text-gray-200 uppercase px-8 py-1 tracking-wider mx-2">{{ __('Register') }}</button>
                                </div>
                            </div>
                        </fieldset> -->
                </form>
                <div class="mt-4">
                    <p class="text-gray-200 text-center tracking-wider">Already have account<a href="{{ url('/login') }}"
                            class="underline text-blue-500 mx-1">Sign in</a> here</p>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(document).ready(function() {
            $(".next").click(function() {
                if (animating) return false;
                animating = true;
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now, mx) {
                        scale = 1 - (1 - now) * 0.2;
                        left = (now * 50) + "%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        next_fs.css({
                            'left': left,
                            'opacity': opacity
                        });
                    },
                    duration: 800,
                    complete: function() {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });

            $(".previous").click(function() {
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();
                //de-activate current step on progressbar
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
                //show the previous fieldset
                previous_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale previous_fs from 80% to 100%
                        scale = 0.8 + (1 - now) * 0.2;
                        //2. take current_fs to the right(50%) - from 0%
                        left = ((1 - now) * 50) + "%";
                        //3. increase opacity of previous_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'left': left
                        });
                        previous_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'opacity': opacity
                        });
                    },
                    duration: 800,
                    complete: function() {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });

            $(".submit").click(function() {
                return false;
            });
        });
    </script>
    <style>
        .input-group-addon {
            background-color: #5bc0de;
        }

        /*form styles*/

        #msform {
            margin: 30px auto;
            position: relative;
        }

        #msform .fieldset {
            background: white;
            border: 0 none;
            border-radius: 3px;
            box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
            padding: 20px 30px;
            box-sizing: border-box;
            width: 100%;
            position: relative;
        }

        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            counter-reset: step;
            justify-content: center;
            display: flex;
        }

        #progressbar li {
            list-style-type: none;
            color: white;
            font-size: 14px;
            /*  width: 44%;*/
            float: left;
            position: relative;
            display: flex;
            align-items: center;
            background: #f7fafc;
            color: #ccc;
            border-radius: 50px;
            padding: 3px;
            text-align: center;
            border: 2px solid #fff;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 35px;
            line-height: 35px;
            display: block;
            font-size: 14px;
            color: #5bc0de;
            background: white;
            border-radius: 50%;
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            color: #5bc0de;
        }

        #progressbar li.active {
            background-color: #5bc0de;
            color: #fff;
        }

    </style>
@endpush
