@extends('layouts.admin.layout')

@section('content')
    <div class="w-full">
        <div>
            <h1 class="admin-h1 mb-5 flex items-center">
                <a href="{{ url('/admin/sermon/show/' . $sermon_id->id) }}" title="Back" class="rounded-full bg-gray-300 p-2">
                    <img src="{{ url('/uploads/icons/back.svg') }}" class="w-3 h-3">
                </a>
                <span class="mx-3">{{ $sermon_id->title }} — Links</span>
            </h1>
        </div>

        @include('partials.message')

        <create-series sermons_id="{{ $sermon_id->id }}" base_url="/admin"></create-series>
        <edit-series base_url="/admin"></edit-series>
        <input type="hidden" id="edit_sermon_id" value="">

        <div class="custom-table overflow-x-auto">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-t-2 border-b-2">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">#</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Chapter Title</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Date</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker text-center">Video</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker text-center">Audio</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker text-center">PDF</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sermons as $index => $sermon)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-2 py-2 text-sm text-gray-500">{{ $sermons->firstItem() + $index }}</td>
                            <td class="px-2 py-2 text-sm font-medium text-gray-800">{{ $sermon->title ?: '—' }}</td>
                            <td class="px-2 py-2 text-xs text-gray-600 whitespace-nowrap">{{ $sermon->date ? date('d M Y', strtotime($sermon->date)) : '—' }}</td>
                            <td class="px-2 py-2 text-center">
                                @if ($sermon->video_link)
                                    <a href="{{ $sermon->video_link }}" target="_blank" rel="noopener noreferrer"
                                       class="text-xs text-blue-600 hover:underline">Watch</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-center">
                                @if ($sermon->audio_link)
                                    <a href="{{ $sermon->audio_link }}" target="_blank" rel="noopener noreferrer"
                                       class="text-xs text-blue-600 hover:underline">Listen</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-center">
                                @if ($sermon->pdf_link)
                                    <a href="{{ \Storage::url($sermon->pdf_link) }}" target="_blank" rel="noopener noreferrer"
                                       class="text-xs text-blue-600 hover:underline">Download</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 text-center flex items-center justify-center gap-2">
                                <a href="#" onclick="editSeries({{ $sermon->id }})" title="Edit"
                                    class="text-white text-xs blue-bg rounded px-2 py-1 font-medium">Edit</a>
                                <a href="#" rel="{{ url('/admin/links/delete/' . $sermon->id) }}" title="Delete"
                                    class="text-white text-xs bg-red-500 rounded px-2 py-1 font-medium delete-link">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $sermons->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        function editSeries(id) {
            $('#edit_sermon_id').val(id);
            $('#edit-series-modal').click();
        }
    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete-link').on('click', function() {
                var link = $(this).attr('rel');
                swal({
                    icon: "info",
                    text: "Do you want to delete this link?",
                    buttons: { cancel: true, confirm: true },
                    allowOutsideClick: false,
                }).then((willChange) => {
                    if (willChange) {
                        $.ajax({
                            url: link,
                            type: "DELETE",
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function() {
                                swal({ icon: "success", text: "Link deleted" })
                                    .then(function() { window.location.reload(); });
                            }
                        });
                    } else {
                        swal("Cancelled");
                    }
                });
            });
        });
    </script>
@endpush
