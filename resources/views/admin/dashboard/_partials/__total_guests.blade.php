<div class="w-full">
    <a href="#">
        <div class="bg-2 px-3 py-3 flex justify-between items-center rounded relative overflow-hidden">
            <div class="text-white w-full">
                <div class="flex justify-between items-center">
                    <div class="text-4xl font-bold flex items-center w-5/6">
                        <div class="flex flex-col items-center w-1/3 leading-tight">
                            <a href="{{ url('/admin/guests') }}"
                                title="Total Guests Count">{{ $dashboard['guestCount'] }}
                                <p class="text-xs font-thin py-1">Total Guests</p>
                            </a>
                        </div>
                        <div class="flex flex-col items-center w-1/3 leading-tight dashboard-user-count">
                            <a href="{{ url('/admin/guests?gender=male') }}"
                                title="Male Guest Count">{{ $dashboard['maleGuestCount'] }}
                                <p class="text-xs font-thin py-1">Male</p>
                            </a>
                        </div>
                        <div class="flex flex-col items-center w-1/3 leading-tight leading-tight">
                            <a href="{{ url('/admin/guests?gender=female') }}"
                                title="Female Guest Count">{{ $dashboard['femaleGuestCount'] == 0 ? 0 : $dashboard['femaleGuestCount'] }}
                                <p class="text-xs font-thin py-1">Female</p>
                            </a>
                        </div>
                    </div>
                    <img src="{{ url('uploads/icons/guest-user.svg') }}" class="w-10 h-10 opacity-75">
                </div>
                <!--  <div class="mt-4">
                    <p class="text-sm">Total Guests</p>
                </div> -->
            </div>
            <div class="absolute right-0 overflow-hidden">
                <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-24 h-24 circular-1">
                <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-24 h-24 circular-2">
            </div>
        </div>
    </a>
</div>
