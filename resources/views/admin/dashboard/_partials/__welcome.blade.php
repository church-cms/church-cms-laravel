<div class="w-full col-span-full">
    <div class="bg-1 px-6 py-6 flex items-center rounded relative overflow-hidden">
        <div class="text-white w-full z-10">
            <p class="text-xs font-thin uppercase tracking-widest mb-1">Welcome back</p>
            <h2 class="text-3xl font-bold leading-tight">{{ auth()->user()->name }}</h2>
            <p class="text-sm font-light mt-2 opacity-80">Sub-Administrator &mdash; {{ date('l, F j, Y') }}</p>
        </div>
        <div class="absolute right-0 overflow-hidden">
            <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-32 h-32 circular-1">
            <img src="{{ url('uploads/icons/circular-shape.svg') }}" class="w-32 h-32 circular-2">
        </div>
    </div>
</div>
