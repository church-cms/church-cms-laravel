<div class="container-wrapper bg-blue-200 border-t border-b border-blue-200"
    style="background-image: url('{{ asset('images/pattern-bg.svg') }}'); background-repeat: repeat no-repeat; background-position:bottom;">
    <div class="container mx-auto">
        <div class="w-full p-8 flex flex-col lg:flex-row items-center">
            <div class="w-full lg:w-1/2">
                <h1 class="text-left mt-8 mb-4 text-blue-600 font-headings text-3xl lg:text-5xl">Church Management
                    Software as Service</h1>
                <h2 class="lg:text-xl text-left text-blue-700">Manage your church members and membership easily and also
                    manage the Events, Activities and Church Website.</h2>
                <div class="pt-8 pb-5 flex mx-auto justify-start">
                    @if (Auth::user()->usergroup_id == 3 || Auth::user()->usergroup_id == 5)
                        <a href="{{ url('/admin/dashboard') }}"
                            class="button px-5 py-3 bg-blue-900 rounded-full mr-2 text-lg text-white tracking-wide">Go
                            To Admin Panel</a>
                    @elseif(Auth::user()->usergroup_id == 6)
                        <a href="{{ url('/preacher/dashboard') }}"
                            class="button px-5 py-3 bg-blue-900 rounded-full mr-2 text-lg text-white tracking-wide">Go
                            To Preacher Panel</a>
                    @else
                        <a href="{{ url('/register') }}"
                            class="button px-5 py-3 bg-blue-900 rounded-full mr-2 text-lg text-white tracking-wide">Register
                            as Church</a>

                        <a href="https://play.google.com/store/apps/details?id=com.gegosoft.churchapplication"
                            target="blank"
                            class="">
                            <div class="button px-5 py-3 bg-blue-900 flex rounded-full mr-2 text-lg text-white tracking-wide">
                            <svg x="0px" y="0px" viewBox="0 0 20.012 20.012" xml:space="preserve"
                                class="fill-current w-4 h-4">
                                <g>
                                    <g>
                                        <path d="M14.217,0H5.796C4.991,0,4.34,0.652,4.34,1.457v17.098c0,0.805,0.651,1.457,1.456,1.457h8.421
            c0.805,0,1.456-0.652,1.456-1.457V1.457C15.673,0.652,15.021,0,14.217,0z M13.256,0.912c0.206,0,0.373,0.166,0.373,0.372
            s-0.167,0.373-0.373,0.373s-0.372-0.167-0.372-0.373S13.049,0.912,13.256,0.912z M8.053,1.054h3.904v0.425H8.053V1.054z
             M10.006,19.117c-0.567,0-1.026-0.46-1.026-1.026c0-0.567,0.459-1.026,1.026-1.026c0.567,0,1.026,0.459,1.026,1.026
            C11.031,18.657,10.573,19.117,10.006,19.117z M14.58,16.215H5.431V2.459h9.149V16.215z"></path>
                                        <path d="M8.291,6.417c-0.336,0.418-0.54,0.958-0.54,1.549h4.539c0-0.597-0.209-1.142-0.552-1.561
            l1.177-1.24c0.094-0.1,0.094-0.261,0-0.359s-0.246-0.099-0.34,0l-1.188,1.252c-0.38-0.303-0.853-0.484-1.367-0.484
            c-0.52,0-0.997,0.185-1.379,0.494L7.444,4.806c-0.095-0.099-0.247-0.099-0.341,0s-0.094,0.26,0,0.359L8.291,6.417z M10.809,6.517
            c0.233,0,0.424,0.198,0.424,0.446c0,0.245-0.19,0.446-0.424,0.446c-0.234,0-0.424-0.201-0.424-0.446
            C10.383,6.715,10.573,6.517,10.809,6.517z M9.205,6.517c0.233,0,0.424,0.198,0.424,0.446c0,0.245-0.191,0.446-0.424,0.446
            c-0.234,0-0.424-0.201-0.424-0.446C8.781,6.715,8.972,6.517,9.205,6.517z"></path>
                                        <path
                                            d="M7.739,12.254h0.403v1.176c0,0.353,0.287,0.639,0.639,0.639c0.353,0,0.638-0.286,0.638-0.639
            v-1.176h1.108v1.176c0,0.353,0.287,0.639,0.638,0.639c0.354,0,0.639-0.286,0.639-0.639v-1.176h0.436V8.225h-4.5v4.029H7.739z">
                                        </path>
                                        <path d="M12.642,8.863v1.846c0,0.353,0.285,0.639,0.638,0.639s0.639-0.286,0.639-0.639V8.863
            c0-0.354-0.286-0.639-0.639-0.639S12.642,8.51,12.642,8.863z"></path>
                                        <path d="M6.732,11.348c0.353,0,0.638-0.286,0.638-0.639V8.863c0-0.354-0.285-0.639-0.638-0.639
            c-0.352,0-0.638,0.285-0.638,0.639v1.846C6.093,11.062,6.379,11.348,6.732,11.348z"></path>
                                    </g>
                                </g>
                            </svg> <p>Download Android App</p>
                            </div>
                       </a>
                    @endif

                </div>


            </div>
            <div class="hidden lg:block lg:w-1/2 px-16">
                <img src="{{ url('uploads/static/homebanner.png') }}" alt="welcome to church membership pro"
                    style="height: 360px; width:auto">
            </div>
        </div>
    </div>
</div>
