 <footer class="border-t">
     <div class="bg-white">
         <div class="container mx-auto py-4 px-4 lg:px-0 ">
             <div class="flex flex-col-reverse lg:flex-row md:flex-row justify-between">
                 <div class="text-sm pt-4 lg:pt-0 md:pt-0">
                     <p>{!! __('faq.copy') !!}{{ date('Y') }}{!! __('faq.allright') !!}</p>
                 </div>
                 <div>
                     <ul class="flex list-reset text-sm footer-links">

                         <li class="font-semibold"><a href="{{ url('/') }}"
                                 class="hover:text-blue-700">{!! __('faq.home') !!}</a></li>
                         <li class="font-semibold"><a href="{{ url('/about') }}"
                                 class="hover:text-blue-700">{!! __('faq.about') !!}</a></li>
                         <li class="font-semibold"><a href="{{ url('/faq') }}"
                                 class="hover:text-blue-700">{!! __('faq.faq') !!}</a></li>
                         <li class="font-semibold"><a href="{{ url('/swotanalysis') }}"
                                 class="hover:text-blue-700">{!! __('faq.swotanalysis') !!}</a></li>
                         <!-- <li class="font-semibold"><a href="{{ url('/') }}" class="hover:text-blue-700">{!! __('faq.contact') !!}</a></li> -->

                         <li class="font-semibold"><a href="{{ url('/church-websites') }}"
                                 class="hover:text-blue-700">Church Template</a></li>

                     </ul>
                 </div>
             </div>

         </div>
     </div>
 </footer>
