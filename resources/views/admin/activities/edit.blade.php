@extends('layouts.admin')
@section('title', 'Update Activity')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}">

@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.activities.show', $activity->id) }}">{{ $activity->name }}</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit Activity</li>
@endsection

@section('content')
<!-- Content Row -->
<div class="row mb-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-with-button">
                Update Activity
            </div>
            <div class="card-body">
                <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Activity Name" required value="{{ $activity->name }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Banner</label>
                            <input type="file" class="form-control" name="banner">
                            @error('banner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Activity Periode</label>
                            <input type="text" value="" class="form-control" id="filter-date-activity" name="activity_periode" placeholder="Activity Periode" required>
                            <input type="hidden" id="filter-date-activity-start" name="start_date" required value="{{ carbon_format($activity->start_date, 'Y-m-d') }}">
                            <input type="hidden" id="filter-date-activity-end" name="end_date" required value="{{ carbon_format($activity->end_date, 'Y-m-d') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="tinymce-input" id="" cols="30" rows="10">{{ $activity->description }}</textarea>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" @if($activity->status == referenceStatus()::STATUS_ACTIVE) @checked(true) @endif id="status1" value="1" >
                                <label class="form-check-label" for="status1">
                                Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" @if($activity->status == referenceStatus()::STATUS_INACTIVE) @checked(true) @endif id="status2" value="0" >
                                <label class="form-check-label" for="status2">
                                Inactive
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control" name="location" placeholder="Activity Location" required value="{{ $activity->location }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary float-right" type="submit">Update Activity</button>

                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-with-button">
                Edit Participant
            </div>
            <div class="card-body">
                <form action="{{ route('admin.activities.add_participant', $activity->id) }}" method="post">
                @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Lecture</label>
                                <select name="lecture" class="form-control">
                                    <option value="">Select Lecture</option>
                                    @foreach($lectures as $lecture)
                                    <option value="{{ $lecture->id }}" @if($lecture->id == $currentLecture->user_id) @selected(true) @endif>{{ $lecture->name }}</option>
                                    @endforeach
                                </select>
                                @error('lecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Students</label>
                                <select name="students[]" class="form-control" id="select-students" multiple>

                                </select>
                            </div>
                            <div class="row">
                                {{-- User list with close button --}}
                                @foreach($activity->students as $student)
                                <div class="col-md-12">
                                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                        <p class="no-margin">
                                            {{ $student->user->name }}<br/>
                                            {{ $student->user->identity }}
                                        </p>
                                        <button type="button" data-participant-id="{{ $student->id }}" class="close btn-remove-participant" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                {{-- End User list with close button --}}
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary float-right" type="submit">Submit Participant</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="{{ route('admin.activities.remove_participant', $activity->id) }}" method="POST" id="remove-participant">@csrf</form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
@endsection
@section('script')
<script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(".btn-remove-participant").click(function(e){
        // prevent
        e.preventDefault();
        var participant_id = $(this).data('participant-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to remove this participant from this activity?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add form user_id
                $("#remove-participant").append('<input type="hidden" name="participant" value="'+participant_id+'">');
                $("#remove-participant").submit();
            }
        })
    });
</script>
<script>
    $('#filter-date-activity').val('{{ carbon_format($activity->start_date, 'd-m-Y') }} - {{ carbon_format($activity->end_date, 'd-m-Y') }}');
    $('#filter-date-activity').daterangepicker({
        ranges: {
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        autoUpdateInput: false,
        alwaysShowCalendars: true,
        locale: { cancelLabel: 'Clear' },
        showDropdowns: true,
        drops: "auto",
        opens: "center",
        startDate: moment('{{ $activity->start_date }}'),
        endDate: moment('{{ $activity->end_date }}'),
        }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            $('#filter-date-activity').val(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
            $('#filter-date-activity-start').val(start.format('YYYY-MM-DD'));
            $('#filter-date-activity-end').val(end.format('YYYY-MM-DD'));
        }
    );

    $('#filter-date-activity').on('cancel.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
        $('#filter-date-activity').val('');
        $('#filter-date-activity-start').val('');
        $('#filter-date-activity-end').val('');
    });
</script>
<script src={{asset("assets/vendor/tinymce/tinymce.min.js")}}></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce-input',
        height: 400,
        menubar: false,
        plugins: 'anchor autolink charmap image link lists media searchreplace visualblocks wordcount',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | align lineheight | checklist numlist bullist indent outdent | removeformat',
    });
</script>
<script>
    $('#select-students').select2({
        ajax: {
            url: "{{ route('admin.students.select2') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    search: params.term,
                    type: 'public',
                    activity_id: '{{ $activity->id }}'
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        placeholder: 'Search Student',
        minimumInputLength: 3,
    });
</script>
@endsection
