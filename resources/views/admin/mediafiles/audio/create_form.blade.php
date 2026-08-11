<form method="POST" action="{{ url('/admin/mediafile/audio/create') }}" enctype="multipart/form-data">
    @csrf
    <div class="px-3 py-3 mx-2">
        <div class="my-3">
            <div class="">
                <div class="w-full lg:w-1/4">
                    <label for="name" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 md:w-3/5 my-2">
                    <input type="text" name="name" id="name" class="tw-form-control w-full" placeholder="Title"
                        value="{{ old('name') }}">
                    <span class="text-danger text-xs">{{ $errors->first('name') }}</span>
                </div>
            </div>
        </div>

        <div class="my-3">
            <div class="">
                <div class="w-full lg:w-1/4">
                    <label class="tw-form-label">Description</label>
                </div>
                <div class="w-full lg:w-2/5 md:w-3/5 my-2">
                    <textarea type="textarea" name="description" id="description" class="tw-form-control w-full"
                        rows="3" placeholder="Description"
                        value="{{ old('description') }}">{{ old('description') }}</textarea>
                    <span class="text-danger text-xs">{{ $errors->first('description') }}</span>
                </div>
            </div>
        </div>

        <div class="my-3">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label for="audio_type" class="tw-form-label">Audio Type<span
                            class="text-red-500">*</span></label>
                </div>
                <div class="my-2 w-full lg:w-2/5 md:w-3/5 flex">
                    <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8">
                        <input type="radio" name="audio_type" id="attachaudio" value="attach"
                            {{ old('audio_type') == 'attach' ? 'checked' : '' }}>
                        <span class="text-sm mx-1">Attach</span>
                    </div>
                    <div class="w-1/2 flex items-center tw-form-control">
                        <input type="radio" name="audio_type" id="recordaudio" value="record"
                            {{ old('audio_type') == 'record' ? 'checked' : '' }}>
                        <span class="text-sm mx-1">Record</span>
                    </div>
                </div>
                <span class="text-danger text-xs">{{ $errors->first('audio_type') }}</span>
            </div>
        </div>

        <div class="my-3 hidden" id="audio_upload">
            <div class="w-full lg:w-1/4">
                <label class="tw-form-label">Attach<span class="text-red-500">*</span></label>
            </div>
            <div class="">
                <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                    <input type="file" name="audioupload" id="audioupload" class="w-full">
                    <span class="text-danger text-xs">{{ $errors->first('audioupload') }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 mb-4">
            <input id="submit" class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium"
                name="submit" type="submit" value="Submit" disabled="disabled">
        </div>
    </div>
</form>

<div class="px-3 py-3 mx-2">
    <div class="my-3">
        <div class="">
            <div class="w-full lg:w-2/5 md:w-2/5 my-2 hidden" id="audio_record">
                <div class="w-full lg:w-1/2 md:w-1/2 lg:mr-8 md:mr-8">
                    <div id="record" class="" style="max-width: 28em;">
                        <div class="my-6">
                            <div class="w-full lg:w-1/4">
                                <label class="tw-form-label">Record<span class="text-red-500">*</span></label>
                            </div>
                            <div id="controls">
                                <button id="recordButton">Record</button>
                                <button id="stopButton" disabled>Stop</button>
                            </div>
                        </div>
                        <div style="max-width: 28em; display:none;">
                            <!-- Do not remove this div.If removed recording won't work -->
                            <p>Convert recorded audio to:</p>
                            <select id="encodingTypeSelect" style="display:none">
                                <option value="mp3">MP3 (MPEG-1 Audio Layer III) (.mp3)</option>
                                <option value="wav">Waveform Audio (.wav)</option>
                                <option value="ogg">Ogg Vorbis (.ogg)</option>
                            </select>
                            <div id="formats" style="display:none"></div>
                            {{-- <pre>Log</pre> --}}
                            <pre id="log" style="display:none"></pre>
                        </div>
                    </div>
                </div>
                <span class="text-danger text-xs">{{ $errors->first('audiorecord') }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link href="{{ asset('css/audio-style.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('audio/WebAudioRecorder.min.js') }}"></script>
    <script src="{{ asset('audio/app.js') }}"></script>
    <script type="text/javascript">
        var url = "{{ url('/') }}";
        var posturl = "{{ url('/admin/mediafile/audio/save') }}";
    </script>

    <script type="text/javascript">
        $('#attachaudio').on('click', function() {
            if ($('#audio_upload').hasClass('hidden')) {
                $('#audio_upload').removeClass('hidden').addClass('block');
                $('#audio_record').removeClass('block').addClass('hidden');
            }
        });

        $('#recordaudio').on('click', function() {
            if ($('#audio_record').hasClass('hidden')) {
                $('#audio_record').removeClass('hidden').addClass('block');
                $('#audio_upload').removeClass('block').addClass('hidden');
            }
        });

        $('#attachaudio').on('click', function() {
            if ($('#submit').hasClass('hidden')) {
                $('#submit').removeClass('hidden').addClass('block');
            }
            $('#audioupload').on('click', function() {
                document.getElementById("submit").disabled = false;
            });
        });

        $('#recordaudio').on('click', function() {
            $('#stopButton').on('click', function() {
                $('#submit').removeClass('hidden').addClass('block');
                document.getElementById("submit").disabled = false;
            });
        });

        $('#recordaudio').on('click', function() {
            $('#submit').addClass('hidden');
        });

        $(document).ready(function() {
            var attachaudio = document.getElementById("attachaudio");
            if (attachaudio.checked) {
                $('#audio_upload').removeClass('hidden').addClass('block');
                $('#audio_record').removeClass('block').addClass('hidden');
            }

            var recordaudio = document.getElementById("recordaudio");
            if (recordaudio.checked) {
                $('#audio_record').removeClass('hidden').addClass('block');
                $('#audio_upload').removeClass('block').addClass('hidden');
            }
        });
    </script>
@endpush
