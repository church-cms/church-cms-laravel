@extends('layouts.admin.layout')

@section('content')
    <div class="w-full lg:mx-2">
        <h1 class="admin-h1 my-3 flex items-center">
            <a href="{{ url('admin/feedbacks') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px"
                    class="w-3 h-3 fill-current text-gray-700">
                    <g>
                        <g>
                            <g>
                                <path
                                    d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z"
                                    data-original="#000000" fill="" class="active-path"></path>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
            <span class="mx-3">Feedback Details</span>
        </h1>
    </div>

    <div class="w-full lg:mx-2">
        <div class="card bg-white shadow my-5">
            <div class="panel panel-default px-3 py-3">
                <div class="panel-body">
                    @include('partials.message')
                    <div class="w-full">
                        <div class="flex flex-row participant-box col-md-4">
                            <div class="flex flex-col lg:w-3/4 lg:flex-row profile-section items-center">
                                <div class="profile-image-section p-4">
                                    @if ($feedback->user_id != null)
                                        <img src="{{ $feedback->user->userprofile->AvatarPath }}" width="60px"
                                            height="60px">
                                    @endif
                                </div>
                                <div class="profile-data-section my-2">
                                    <h3 class="profile-full-name text-sm">
                                        @if ($feedback->user_id != null)
                                            <a
                                                href="{{ url('/admin/member/show/' . $feedback->user->name) }}">{{ $feedback->user->FullName }}</a>
                                        @else
                                            {{ ucfirst($feedback->mobile_no) }}
                                        @endif

                                    </h3>
                                </div>
                            </div>
                            <div class="flex items-center lg:w-1/4 lg:justify-end">
                                <div class="flex items-center text-gray-600">
                                    @if ($feedback->latestMessage->is_seen == '0')
                                        <a href="#"
                                            class="no-underline text-white px-4 my-3 mx-1 flex items-center bg-orange-500 py-1 justify-center"
                                            onclick='update("{{ url('/admin/feedback/updateStatus/' . $feedback->latestMessage->id) }}","has_seen")'>
                                            <span class="mx-1 text-sm font-semibold">Mark As Seen</span>
                                        </a>
                                    @elseif($feedback->latestMessage->is_seen == 'has_seen')
                                        <a href="#"
                                            class="no-underline text-white px-4 my-3 mx-1 flex items-center bg-red-600 py-1 justify-center"
                                            onclick='update("{{ url('/admin/feedback/updateStatus/' . $feedback->latestMessage->id) }}","action_taken")'>
                                            <span class="mx-1 text-sm font-semibold">Take Action</span>
                                        </a>
                                    @elseif($feedback->latestMessage->is_seen == 'action_taken')
                                        <a href="#"
                                            class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                                            <span class="mx-1 text-sm font-semibold">Action Taken</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="conversation-box w-full flex">
                            <input type="hidden" name="baseurl" id="baseurl" value="{{ url('/') }}">
                            @include('admin.feedbacks.details')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function update(link, value) {
            if (value == 'has_seen') {
                var text_value = "Do you want to mark this as seen ?";
            } else {
                var text_value = "Do you want to take action ?";
            }
            swal({
                icon: "info",
                text: text_value,
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) {
                    $.ajax({
                        url: link,
                        type: "POST",
                        data: {
                            status: value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (value == 'has_seen') {
                                var msg_text = "Mark As Seen Successfully";
                            } else {
                                var msg_text = "Action Taken Successfully";
                            }
                            swal({
                                icon: "success",
                                text: msg_text,
                            }).then(function() {
                                window.location.reload();
                            });
                        }
                    });
                } else {
                    swal("Cancelled");
                }
            });
        }
    </script>

    <style>
        .message-bubble-text-right {
            text-align: start;
        }

        .message-bubble-text-left {
            text-align: end;
        }

    </style>
@endpush
