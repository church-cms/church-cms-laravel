@extends('layouts.admin.layout')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ $prev_url }}" title="Back" id="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">Mailing List Details</span>
        </h1>
    </div>
    <div class="bg-white shadow my-3">
        <div class="px-4 py-4">
            <div class="w-full lg:mr-8 md:mr-8 custom-table overflow-x-auto">
                <table class="w-full">
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Scope</td>
                        <td class="py-3 px-2">{{ ucfirst($mailinglist->scope) }}</td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Name</td>
                        <td class="py-3 px-2">{{ $mailinglist->name }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Description</td>
                        <td class="py-3 px-2">{{ $mailinglist->description }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Published</td>
                        <td class="py-3 px-2">{{ $mailinglist->is_published }} </td>
                    </tr>
                    <tr class="border-t border-b">
                        <td class="py-3 px-2 font-semibold">Subscribe Widget</td>
                        <td class="py-3 px-2 flex items-center justify-between">
                            <a href="{{ url('admin/subscribe/' . $mailinglist->slug) }}"
                                class="no-underline text-white px-4 my-3 flex items-center blue-bg py-1 justify-center text-xs rounded">View</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <import-csv url="{{ url('/') }}" id="{{ $mailinglist->id }}"></import-csv>
    <!--subscribers list-->

    <div class="flex items-center justify-between">
        <h1 class="admin-h1 flex items-center">
            <span class="">Subscribers</span>
        </h1>
        <a href="{{ url('admin/mailinglist/attachsubscriber/' . $mailinglist->id) }}">
            <button type="submit"
                class="no-underline text-white  px-4 my-3 mx-1 flex items-center blue-bg py-1 justify-center text-xs rounded">Attach
                Subscriber</button>
        </a>
    </div>
    <div class="px-3 py-3 bg-white shadow my-3">
        <div class="custom-table overflow-x-auto">
            <table class="w-full border">
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subscribers Email</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Attach To List</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Active</th>
                        <th width="15%" class="text-left text-sm px-2 py-2 text-grey-darker" style="width: 20%;">Action</th>
                    </tr>
                </thead>
                @if (count($subscribers) == 0)
                    <tbody class="bg-grey-light">
                        <tr class="border-t-2 border-b-2">
                            <td colspan="4" style="text-align: center;">No records found</td>
                        </tr>
                    </tbody>
                @else
                    @foreach ($subscribers as $subscriber)
                        <tbody class="bg-grey-light">
                            <td class="py-3 px-2">{{ $subscriber->email }}</td>
                            <td class="py-3 px-2">{{ $mailinglist->name }}</td>
                            <td class="px-3 py-2">
                                @if ($subscriber->is_active == 1)
                                    <!--  <img src="{{ $tick }}" class="w-3 h-3"> -->
                                    <svg class="w-4 h-4 fill-current text-green-500" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 408.576 408.576"
                                        style="enable-background:new 0 0 408.576 408.576;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M204.288,0C91.648,0,0,91.648,0,204.288s91.648,204.288,204.288,204.288s204.288-91.648,204.288-204.288 S316.928,0,204.288,0z M318.464,150.528l-130.56,129.536c-7.68,7.68-19.968,8.192-28.16,0.512L90.624,217.6 c-8.192-7.68-8.704-20.48-1.536-28.672c7.68-8.192,20.48-8.704,28.672-1.024l54.784,50.176L289.28,121.344 c8.192-8.192,20.992-8.192,29.184,0C326.656,129.536,326.656,142.336,318.464,150.528z" />
                                            </g>
                                        </g>
                                    </svg>
                                @else
                                    <!--   <img src="{{ $cross }}" class="w-3 h-3"> -->
                                    <svg class="w-4 h-4 fill-current text-red-500" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 511.76 511.76"
                                        style="enable-background:new 0 0 511.76 511.76;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M436.896,74.869c-99.84-99.819-262.208-99.819-362.048,0c-99.797,99.819-99.797,262.229,0,362.048 c49.92,49.899,115.477,74.837,181.035,74.837s131.093-24.939,181.013-74.837C536.715,337.099,536.715,174.688,436.896,74.869z M361.461,331.317c8.341,8.341,8.341,21.824,0,30.165c-4.16,4.16-9.621,6.251-15.083,6.251c-5.461,0-10.923-2.091-15.083-6.251 l-75.413-75.435l-75.392,75.413c-4.181,4.16-9.643,6.251-15.083,6.251c-5.461,0-10.923-2.091-15.083-6.251 c-8.341-8.341-8.341-21.845,0-30.165l75.392-75.413l-75.413-75.413c-8.341-8.341-8.341-21.845,0-30.165 c8.32-8.341,21.824-8.341,30.165,0l75.413,75.413l75.413-75.413c8.341-8.341,21.824-8.341,30.165,0 c8.341,8.32,8.341,21.824,0,30.165l-75.413,75.413L361.461,331.317z" />
                                            </g>
                                        </g>
                                    </svg>
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <a href="{{ url('/admin/subscriber/show/' . $subscriber->id) }}" title="View">
                                        <img src="{{ url('/uploads/icons/show.svg') }}"
                                            class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                    <a href="{{ url('/admin/subscriber/edit/' . $subscriber->id) }}" title="Edit">
                                        <img src="{{ url('/uploads/icons/edit1.svg') }}"
                                            class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                    <a href="#"
                                        rel="{{ url('admin/mailinglist/detachsubscriber/' . $subscriber->id . '/' . $mailinglist->id) }}"
                                        class="delete_subscriber">
                                        <img src="{{ url('/uploads/icons/delete1.svg') }}"
                                            class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                </div>
                            </td>
                        </tbody>
                    @endforeach
                @endif
            </table>
        </div>
        {{ $subscribers->links() }}
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete_subscriber').on('click', function() {
                var link = $(this).attr('rel');
                var status = $(this).attr('value');
                //alert(status);
                swal({
                    icon: "info",
                    text: "Do you want to detach this subscriber ?",
                    buttons: {
                        cancel: true,
                        confirm: true,
                    },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                //alert(ans);
                                swal({
                                    icon: "success",
                                    text: "Subscriber Detached Successfully",
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        })
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>
@endpush
