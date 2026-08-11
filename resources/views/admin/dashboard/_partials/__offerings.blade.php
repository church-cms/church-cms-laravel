 <div>
     <div class="flex justify-between">
         <h1 class="text-sm uppercase text-gray-800 font-semibold mb-2">Offering Collection</h1>
         @if ($chart == 0)
             <span class="px-4 enable-chart">
                 <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="880.000000pt" height="920.000000pt"
                     viewBox="0 0 880.000000 920.000000" preserveAspectRatio="xMidYMid meet" class="w-5 h-5">
                     <g transform="translate(0.000000,920.000000) scale(0.100000,-0.100000)" fill="#000000"
                         stroke="none">
                         <path
                             d="M7606 8766 c-72 -31 -138 -93 -173 -164 -33 -68 -42 -168 -19 -232 14 -41 14 -46 -2 -70 -9 -15 -309 -434 -665 -932 l-648 -905 -62 -5 c-35 -3 -84 -14 -108 -25 l-45 -20 -572 282 c-528 262 -572 285 -577 312 -22 101 -72 179 -149 230 -60 41 -103 53 -191 53 -90 0 -171 -36 -233 -104 -57 -62 -81 -119 -90 -210 l-7 -75 -603 -479 -603 -480 -37 10 c-61 17 -170 5 -229 -25 -86 -44 -155 -130 -176 -218 -7 -29 20 -17 -753 -329 l-381 -153 -68 34 c-61 30 -78 34 -146 33 -62 0 -87 -6 -131 -27 -69 -34 -131 -96 -166 -167 -38 -77 -38 -190 -1 -270 33 -70 99 -136 169 -169 80 -38 192 -37 272 2 79 39 153 122 175 197 l17 58 564 225 563 225 61 -31 c56 -29 70 -32 152 -32 79 0 98 4 141 27 111 58 174 154 184 280 l6 77 555 441 c728 580 650 522 689 509 47 -17 167 -6 224 19 l48 21 572 -287 572 -287 7 -42 c16 -101 105 -205 206 -243 79 -30 195 -25 267 12 62 32 130 103 157 166 28 63 35 164 14 222 -13 38 -13 48 -2 65 8 11 307 430 665 930 l651 910 63 6 c123 11 235 94 277 206 30 79 25 196 -10 268 -55 112 -154 176 -285 182 -68 3 -89 0 -139 -21z" />
                         <path
                             d="M7070 3715 l0 -2915 663 2 662 3 3 2913 2 2912 -665 0 -665 0 0 -2915z m990 5 l0 -2580 -330 0 -330 0 0 2580 0 2580 330 0 330 0 0 -2580z" />
                         <path
                             d="M3740 2965 l0 -2165 665 0 665 0 0 2165 0 2165 -665 0 -665 0 0 -2165z m998 3 l2 -1828 -335 0 -335 0 0 1830 0 1830 333 -2 332 -3 3 -1827z" />
                         <path
                             d="M5400 2550 l0 -1750 665 0 665 0 0 1750 0 1750 -665 0 -665 0 0 -1750z m998 3 l2 -1413 -332 0 -333 0 1 1408 c1 774 2 1410 3 1415 0 4 148 6 329 5 l327 -3 3 -1412z" />
                         <path
                             d="M2072 2303 l3 -1498 663 -3 662 -2 0 1500 0 1500 -665 0 -665 0 2 -1497z m998 2 l0 -1165 -330 0 -330 0 0 1165 0 1165 330 0 330 0 0 -1165z" />
                         <path
                             d="M410 1965 l0 -1165 665 0 665 0 0 1165 0 1165 -665 0 -665 0 0 -1165z m1000 5 l0 -830 -335 0 -335 0 0 830 0 830 335 0 335 0 0 -830z" />
                     </g>
                 </svg>
             </span>
         @else
             <span class="px-4 enable-chart">
                 &times;
             </span>
         @endif
         <a href="{{ url('/admin/funds') }}" class="text-xs underline">See All</a>
     </div>
     <div class="w-full bg-white shadow rounded dashboard-content">
         <div id="chartContainer" class="w-full px-3 hidden"></div>
         <ul class="w-full list-reset my-3 shadow">
             <div class="bg-yellow-300 p-2 flex flex-row">
                 Total Offerings = Rs . {{ number_format($dashboard['total_fund'], 0) }}
             </div>
             @if (count($dashboard['fundlist']) > 0)
                 @foreach ($dashboard['fundlist'] as $fund)
                     <li class="py-2 px-2 flex justify-between items-center">
                         <div class="w-1/2 flex items-center">
                             @if ($fund->membership == 'member')
                                 <a href="{{ url('/admin/member/show/' . $fund->user->name) }}"
                                     class="text-sm text-gray-800">{{ ucwords($fund->user->FullName) }}</a>
                                 <p
                                     class="fund-method text-white mx-2 rounded-full px-2 {{ ucwords($fund->method) }}">
                                     {{ ucwords($fund->method) }}</p>
                             @else
                                 <a href="#"
                                     class="text-sm text-gray-800">{{ ucwords($fund->data['first_name']) }}</a>
                                 <p
                                     class="fund-method text-white mx-2 rounded-full px-2 {{ ucwords($fund->method) }}">
                                     {{ ucwords($fund->method) }}</p>
                             @endif
                         </div>
                         <div class="w-1/2">
                             <p class="text-gray-700 text-sm font-semibold flex items-center justify-end">
                                 <img src="{{ url('uploads/icons/rupee-indian.svg') }}" class="w-3 h-3 mx-1">
                                 <span>{{ $fund->amount }}</span>
                             </p>
                         </div>
                     </li>
                 @endforeach
             @else
                 <p class="text-gray-700 mx-2 rounded-full px-2 py-1 text-sm">No Offerings Records Found</p>
             @endif
         </ul>
     </div>
 </div>

 @push('scripts')
     <script type="text/javascript">
         $(document).ready(function() {
             $('.enable-chart').on('click', function() {
                 if ($('#chartContainer').hasClass('hidden')) {
                     $('#chartContainer').addClass('block').removeClass('hidden');
                     var chart = 1;
                 } else {
                     $('#chartContainer').addClass('hidden').removeClass('block');
                     var chart = 0;
                 }
             });
         });
     </script>
 @endpush
