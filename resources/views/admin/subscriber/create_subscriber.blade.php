<html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.0.1/tailwind.min.css" rel="stylesheet">
</head>

<body class="">
    <div class="app font-sans">
        <!-- start -->
        <div class="py-20 min-h-screen px-4">
            <div class="container mx-auto">
                <div class="w-full lg:w-3/5 mx-auto shadow-md ">
                    <div
                        style="background-image: url('{{ url('images/bg.png') }}');background-repeat: no-repeat; background-size: cover;background-position: center;">
                        <div class=" py-4">
                            <form style="margin-block-end: auto;" method="post">
                                @csrf
                                <div class="">
                                    <div class="w-11/12 mx-auto">
                                        <div class="text-white text-xl py-2 text-center">
                                            <p class="tracking-wider">Subscribe</p>
                                        </div>
                                        <div class="flex py-3">
                                            <div class="form-group py-3 w-1/3 px-2">
                                                <div class="input-group w-full">
                                                    <input id="firstname" type="text" name="firstname"
                                                        value="{{ old('firstname') }}" placeholder="First Name"
                                                        class="form-control focus:outline-none w-full text-sm px-4 border-yellow-500 py-2 rounded-full"
                                                        style=" color: #a7a8b5;border-width: 3px;">
                                                    <span
                                                        class="text-danger text-xs">{{ $errors->first('firstname') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group py-3 w-1/3 px-2">
                                                <div class="input-group w-full">
                                                    <input id="lastname" type="text" name="lastname"
                                                        value="{{ old('lastname') }}" placeholder="Last Name"
                                                        class="form-control focus:outline-none w-full text-sm px-4 border-yellow-500 py-2 rounded-full"
                                                        style=" color: #a7a8b5;border-width: 3px;">
                                                    <span
                                                        class="text-danger text-xs">{{ $errors->first('lastname') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group py-3 w-1/3 px-2">
                                                <div class="input-group w-full">
                                                    <input id="email" type="email" name="email"
                                                        value="{{ old('email') }}" placeholder="Email"
                                                        class="form-control focus:outline-none w-full text-sm px-4 border-yellow-500 py-2 rounded-full"
                                                        style=" color: #a7a8b5;border-width: 3px;">
                                                    <span
                                                        class="text-danger text-xs">{{ $errors->first('email') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group py-3 w-1/3 px-2">
                                                <div class="input-group w-full">
                                                    <input id="aff" type="text" name="aff" value="{{ old('aff') }}"
                                                        placeholder="Aff"
                                                        class="form-control focus:outline-none w-full text-sm px-4 border-yellow-500 py-2 rounded-full"
                                                        style=" color: #a7a8b5;border-width: 3px;">
                                                    <span class="text-danger text-xs">{{ $errors->first('aff') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group py-3 w-1/3 px-2">
                                                <div class="input-group w-full">
                                                    <input id="source" type="text" name="source"
                                                        value="{{ old('source') }}" placeholder="Source"
                                                        class="form-control focus:outline-none w-full text-sm px-4 border-yellow-500 py-2 rounded-full"
                                                        style=" color: #a7a8b5;border-width: 3px;">
                                                    <span
                                                        class="text-danger text-xs">{{ $errors->first('source') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-3 text-center">
                                            <button type="submit"
                                                class="italic text-sm py-2 text-white px-6 rounded-full font-bold tracking-wider focus:outline-none"
                                                style="background-color: #ed142e;">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</body>

</html>
<style>
    .text-danger

</style>
