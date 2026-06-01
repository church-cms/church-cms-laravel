@extends('layouts.admin.layout')
@section('content')

    {{-- Vue modals --}}
    <create-series sermons_id="{{ $sermon->id }}" base_url="/admin"></create-series>
    <edit-series base_url="/admin"></edit-series>
    <input type="hidden" id="edit_sermon_id" value="">

    <div class="flex items-center justify-between mb-3">
        <h1 class="admin-h1 flex items-center">
            <a href="{{ url('/admin/sermons') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
            </a>
            <span class="mx-3">{{ $sermon->title }}</span>
        </h1>
    </div>

    <div class="py-3 bg-white shadow px-3 overflow-x-auto">
        <table class="w-full text-sm" style="border-collapse:collapse;table-layout:fixed;">
            <colgroup>
                <col style="width:5%">
                <col style="width:30%">
                <col style="width:13%">
                <col style="width:13%">
                <col style="width:13%">
                <col style="width:10%">
                <col style="width:16%">
            </colgroup>
            <thead>
                <tr style="background:#6b7280;">
                    <th style="padding:10px 12px;text-align:left;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">#</th>
                    <th style="padding:10px 12px;text-align:left;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">Chapter Title</th>
                    <th style="padding:10px 12px;text-align:left;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">Date</th>
                    <th style="padding:10px 12px;text-align:center;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">Video</th>
                    <th style="padding:10px 12px;text-align:center;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">Audio</th>
                    <th style="padding:10px 12px;text-align:center;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">PDF</th>
                    <th style="padding:10px 12px;text-align:center;font-size:12px;font-weight:600;color:#fff;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($sermonlinks as $index => $sermonlink)
                <tr style="border-bottom:1px solid #e5e7eb;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:10px 12px;color:#9ca3af;font-size:13px;">{{ $sermonlinks->firstItem() + $index }}</td>
                    <td style="padding:10px 12px;font-weight:500;color:#1f2937;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $sermonlink->title ?: '—' }}</td>
                    <td style="padding:10px 12px;color:#6b7280;font-size:12px;white-space:nowrap;">{{ $sermonlink->date ? date('d M Y', strtotime($sermonlink->date)) : '—' }}</td>

                    {{-- Video --}}
                    <td style="padding:10px 12px;text-align:center;">
                        @if ($sermonlink->video_link)
                            <a href="{{ $sermonlink->video_link }}" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;justify-content:center;gap:4px;font-size:11px;font-weight:500;color:#2563eb;text-decoration:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 16 16"><path d="M6.552 5.978A1 1 0 0 0 5 6.626v2.748a1 1 0 0 0 1.552.834l2.124-1.374a1 1 0 0 0 0-1.668L6.552 5.978z"/><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/></svg>
                                Watch
                            </a>
                        @else
                            <span style="color:#d1d5db;font-size:12px;">—</span>
                        @endif
                    </td>

                    {{-- Audio --}}
                    <td style="padding:10px 12px;text-align:center;">
                        @if ($sermonlink->audio_link)
                            <a href="{{ $sermonlink->audio_link }}" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;justify-content:center;gap:4px;font-size:11px;font-weight:500;color:#2563eb;text-decoration:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 16 16"><path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z"/><path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z"/><path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/></svg>
                                Listen
                            </a>
                        @else
                            <span style="color:#d1d5db;font-size:12px;">—</span>
                        @endif
                    </td>

                    {{-- PDF --}}
                    <td style="padding:10px 12px;text-align:center;">
                        @if ($sermonlink->pdf_link)
                            <a href="{{ \Storage::url($sermonlink->pdf_link) }}" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;justify-content:center;gap:4px;font-size:11px;font-weight:500;color:#2563eb;text-decoration:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;flex-shrink:0;" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/></svg>
                                PDF
                            </a>
                        @else
                            <span style="color:#d1d5db;font-size:12px;">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td style="padding:10px 12px;text-align:center;">
                        <div style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            <a href="#" onclick="editChapter({{ $sermonlink->id }})"
                               style="display:inline-flex;align-items:center;justify-content:center;width:58px;height:26px;font-size:11px;font-weight:500;border-radius:4px;border:1px solid #3b82f6;color:#2563eb;background:#eff6ff;text-decoration:none;box-sizing:border-box;">
                                Edit
                            </a>
                            <a href="#" rel="{{ url('/admin/links/delete/' . $sermonlink->id) }}"
                               class="delete-link"
                               style="display:inline-flex;align-items:center;justify-content:center;width:58px;height:26px;font-size:11px;font-weight:500;border-radius:4px;border:1px solid #ef4444;color:#dc2626;background:#fef2f2;text-decoration:none;box-sizing:border-box;">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="padding:32px;text-align:center;color:#9ca3af;font-size:13px;">No chapters added yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $sermonlinks->links() }}

@endsection

@push('scripts')
    <script>
        function editChapter(id) {
            $('#edit_sermon_id').val(id);
            $('#edit-series-modal').click();
        }
    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.delete-link').on('click', function () {
                var link = $(this).attr('rel');
                swal({
                    icon: "warning",
                    text: "Delete this chapter?",
                    buttons: { cancel: true, confirm: { text: "Delete", className: "btn-danger" } },
                    allowOutsideClick: false,
                }).then(function (willDelete) {
                    if (willDelete) {
                        $.ajax({
                            url: link,
                            type: "DELETE",
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function () {
                                swal({ icon: "success", text: "Chapter deleted." })
                                    .then(function () { window.location.reload(); });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

