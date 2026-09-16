<nav class="navbar bg-white w-full flex  lg:flex-row px-4 lg:px-8 pt-2 justify-between items-center">
    <div class="nav-brand flex items-baseline">
        <a href="{{ url('/admin/dashboard') }}">
            <img src="{{ asset('uploads/Images/church_cms_logo.jpg') }}" alt="ChurchCMS Admin Logo" class="w-auto h-7 mr-2">
        </a>



    </div>
    <div class="flex flex-col">
        <a class="navbar-nav ml-auto flex items-center text-gray-700">
            <strong>{{ ucwords(\Auth::user()->churchdetails->quotes) }} </strong>
        </a>
    </div>
    <div class="navbar-menu collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto flex">
        </ul>
    </div>
    <div class="flex items-center">
        <notification url="{{ url('/') }}" mode="admin"></notification>
        <div class="navbar-menu">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto flex items-center font-headings">

                @guest
                <li class="nav-item px-2">
                    <a class="nav-link" href="{{ route('login') }}" id="login">{{ __('Login') }}</a>
                </li>

                @else
                <!-- start -->
                <li>
                    <div class="profile-click" dusk="profile-menu">
                        @if (Auth::user()->userprofile->avatar != null)
                        <img src="{{ Auth::user()->userprofile->AvatarPath }}"
                            class="w-8 h-8 rounded-full cursor-pointer">
                        @else
                        <img src="{{ url('uploads/user/avatar/default-user.jpg') }}"
                            class="w-8 h-8 rounded-full cursor-pointer">
                        @endif
                        <div class="user-dtl rounded">
                            <ul class="list-reset">
                                <div class="flex border-b p-2 items-center">
                                    @if (Auth::user()->userprofile->avatar != null)
                                    <img src="{{ Auth::user()->userprofile->AvatarPath }}"
                                        class="w-10 h-10 rounded-full cursor-pointer">
                                    @else
                                    <img src="{{ url('uploads/user/avatar/default-user.jpg') }}"
                                        class="w-10 h-10 rounded-full cursor-pointer">
                                    @endif
                                    <div>
                                        <div>
                                            <a id="navbarDropdown"
                                                class="nav-link dropdown-toggle text-sm  no-underline text-black px-2"
                                                href="{{ url('/admin/dashboard') }}">
                                                {{ Auth::user()->FullName }} <span class="caret"></span>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-sm no-underline text-black px-2">{{ Auth::user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2 leading-loose">
                                    <li class="hover:bg-gray-200">
                                        <a href="{{ url('/admin/changepassword/') }}" dusk="password-link"
                                            class="text-sm  no-underline text-black px-3">
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li class="hover:bg-gray-200">
                                        <a href="{{ url('/admin/editprofile') }}"
                                            class="text-sm  no-underline text-black px-3">
                                            <span>Edit Profile</span>
                                        </a>
                                    </li>
                                    <li class="hover:bg-gray-200">
                                        <a href="{{ url('/admin/changeavatar') }}"
                                            class="text-sm  no-underline text-black px-3">
                                            <span>Change Avatar</span>
                                        </a>
                                    </li>
                                    <li class="hover:bg-gray-200">
                                        <a class="dropdown-item text-sm  no-underline text-black px-3"
                                            dusk="logout-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                </li>
                <!-- end -->
                @endguest
            </ul>
        </div>
    </div>
</nav>