@extends('layouts.siteadmin.layout')

@section('content')
    <div class="relative">
        @include('partials.message')
        <div class="flex flex-row justify-between">
            <form method="post" action="{{ url('/siteadmin/index/show/' . $subscriptions->id) }}" class="mb-0">
                {{ csrf_field() }}
                @if ($membership == 'guest')
                    <input type="hidden" name="membership_type" value="member">
                    <a>
                        <button type="submit"
                            class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">Click
                            To Activate
                        </button>
                    </a>
                @else
                    <input type="hidden" name="membership_type" value="guest">
                    <a>
                        <button type="submit"
                            class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">Click
                            to Deactivate
                        </button>
                    </a>
                @endif
            </form>
        </div>
        <div class="flex flex-row justify-between">
            
        </div>
        <div class="flex flex-row justify-between">
            
        </div>
    </div>
@endsection
