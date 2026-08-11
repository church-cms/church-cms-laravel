<div class="w-full">
    <a href="{{ url('admin/members?gender=male') }}">
        <div class="bg-2 px-3 py-3 flex justify-between items-center rounded relative overflow-hidden">
            <div class="text-white w-full">
                <div class="flex justify-between items-center">
                    <p class="text-4xl font-bold">{{ $dashboard['maleCount'] }}</p>
                    <img src="{{ url('uploads/icons/male.svg') }}" class="w-10 h-10 opacity-75">
                </div>
                <div class="mt-4">
                    <p class="text-sm">Male Members</p>
                </div>
            </div>
            <div class="absolute right-0 overflow-hidden">
                <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-24 h-24 circular-1">
                <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-24 h-24 circular-2">
            </div>
        </div>
    </a>
</div>
