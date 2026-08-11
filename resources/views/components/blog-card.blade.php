  <a href="{{ url($link) }}" class="p-3 block bg-white shadow">
      <img src="{{ asset($image) }}" alt={{ $alt }} class="mb-4">

      <h2 class="font-headings text-2xl">{{ $slot }}</h2>
      <p class="text-sm">{{ $snippet }}</p>
  </a>
