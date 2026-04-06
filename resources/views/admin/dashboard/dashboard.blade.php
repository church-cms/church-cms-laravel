@extends('layouts.admin.layout')

@section('content')
    @php $isAdmin = auth()->user()->usergroup_id == 3; @endphp
    <!-- start -->
    <div class="flex flex-col lg:flex-row my-4">
        <div class="w-full">
            @if($isAdmin)
                {{-- Full admin dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-5">
                    @include('admin.dashboard._partials.__total_members')
                    @include('admin.dashboard._partials.__total_guests')
                    {{-- @include('admin.dashboard._partials.__male_count')
                     @include('admin.dashboard._partials.__female_count') --}}
                    @include('admin.dashboard._partials.__search_box')
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @include('admin.dashboard._partials.__statistics')
                    @include('admin.dashboard._partials.__recently_added_members')
                    @include('admin.dashboard._partials.__not_attended_members')
                    @include('admin.dashboard._partials.__birthday')
                    @include('admin.dashboard._partials.__anniversary')
                    @include('admin.dashboard._partials.__upcoming_events')
                    @include('admin.dashboard._partials.__latest_sermons')
                    @include('admin.dashboard._partials.__offerings')
                </div>
            @else
                {{-- Sub-admin dashboard: welcome widget only --}}
                <div class="grid grid-cols-1 gap-8">
                    @include('admin.dashboard._partials.__welcome')
                </div>
            @endif
        </div>
    </div>
    <!-- end -->
@endsection

@push('scripts')
@if(auth()->user()->usergroup_id == 3)
    <script>
        var final = <?php echo json_encode($dashboard['final']); ?>;
        if (final.length == 0) {
            $('#chartContainer').append(
                '<div class="w-full my-2 relative"><div class="px-2"><h2 class="font-bold text-base text-gray-700">No Records Found</h2></div></div>'
            );
        } else {
            window.onload = function() {
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    height: 280,
                    width: 290,
                    title: {
                        text: ""
                    },
                    axisY: {
                        title: "",
                        valueFormatString: "",
                        suffix: "",
                        prefix: ""
                    },
                    data: [{
                        type: "column",
                        color: "rgba(54,158,173,.7)",
                        markerSize: 5,
                        xValueFormatString: "",
                        yValueFormatString: "Rs #,##0.##",
                        dataPoints: <?php echo json_encode($dashboard['final'], JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();
            }
        }
    </script>
@endif
@endpush
