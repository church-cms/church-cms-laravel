<div class="custom-table overflow-auto">
    <table class="table table-bordered messageTable" id="messagelist">
        <thead class="bg-grey-light">
            <tr>
                <th>From</th>
                <th>Category</th>
                <th>Message</th>
                <th>Sent On</th>
                <th>Actions</th>
            </tr>
        </thead>
        @if (count($feedbacks) != 0)
            @foreach ($feedbacks as $feedback)
                <tbody>
                    <td>
                        @if ($feedback->user_id != null)
                            <a
                                href="{{ url('/admin/member/show/' . $feedback->user->name) }}">{{ ucfirst($feedback->user->FullName) }}</a>
                        @else
                            {{ ucfirst($feedback->mobile_no) }}
                        @endif
                    </td>
                    <td>{{ ucwords(str_replace('_', ' ', str_replace('/', ' / ', $feedback->latestMessage->category))) }}
                    </td>
                    <td>
                        <p> {!! \Str::limit($feedback->feedbackMessage->first()->message, 50, '...') !!}
                            @if ($feedback->latestMessage->is_seen == '0')
                                <span class="bg-red-300 rounded-full text-white inline-block px-2 py-1 my-1 mb-2"> New
                                </span>
                            @endif
                        </p>
                    </td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($feedback->created_at)) }}</td>
                    <td>
                        <div class="flex items-center">
                            <a href="{{ url('admin/feedback/edit/' . $feedback['id']) }}" class=""
                                title="Show">
                                <svg class="w-4 h-4 fill-current text-black-500 mx-1" height="512pt"
                                    viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tbody>
            @endforeach
        @else
            <tbody>
                <td colspan="6" style="text-align: center;">No Records found</td>
            </tbody>
        @endif
    </table>
</div>

{{ $feedbacks->links() }}

@push('scripts')
    <script>
        $(document).ready(function() {
            //  $('messageTable').messageTable();        
        });
    </script>

    <style>
        /* messagetable */
        table.messageTable tbody th,
        table.messageTable tbody td {
            /*    padding: 1rem 1.5rem;*/
        }

        table.messageTable thead th,
        table.messageTable thead td {
            /*padding: 1rem;*/
            border-bottom: 1px solid #1110;
        }

        table.messageTable {
            width: 100%;
            margin: 0 auto;
            clear: both;
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* @media only screen and (max-width: 760px), (max-device-width: 1024px) and (min-device-width: 768px)
            {
                td {
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding-left:50%;
                    padding-top:5%;
                    padding-bottom:5%;
                }*/
        .messageTables_wrapper .messageTables_length,
        .messageTables_wrapper .messageTables_filter {
            float: none;
            text-align: left;
        }
        }

        @media only screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1024px) {

            /* Force table to not be like tables anymore */
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                font-size: 15px;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin: 0 0 1rem 0;
            }

            tr:nth-child(odd) {
                background: #ccc ! important;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 60% ! important;
            }

            td:before {
                /* Now like a table header */
                position: absolute;
                /* Top/left values mimic padding */
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                padding-top: 5%;
            }

            td:nth-of-type(1):before {
                content: "{{ trans('forms.from') }}";
            }

            td:nth-of-type(2):before {
                content: "{{ trans('forms.to') }}";
            }

            td:nth-of-type(3):before {
                content: "{{ trans('forms.message') }}";
            }

            td:nth-of-type(4):before {
                content: "{{ trans('forms.createdon') }}";
            }

            td:nth-of-type(5):before {
                content: "{{ trans('forms.lastreplyby') }}";
            }

            td:nth-of-type(6):before {
                content: "{{ trans('forms.lastreplyon') }}";
            }
        }

        .t-dropdown.t-dropdown-size-sm button {
            border: none ! important;
        }

    </style>
@endpush
