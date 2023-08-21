@extends('layouts.admin')
@section('title', 'Create Activity')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">

@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Create Activity</li>
@endsection

@section('content')
<!-- Content Row -->
<form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row mb-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-with-button">
                Create Activity
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Activity Name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Banner</label>
                            <input type="file" class="form-control" name="banner" required>
                            @error('banner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Activity Periode</label>
                            <input type="text" class="form-control" id="filter-date-activity" name="activity_periode" placeholder="Activity Periode" required>
                            <input type="hidden" id="filter-date-activity-start" name="start_date" required>
                            <input type="hidden" id="filter-date-activity-end" name="end_date" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="tinymce-input" id="" cols="30" rows="10"></textarea>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" @checked(true) id="status1" value="1" >
                                <label class="form-check-label" for="status1">
                                Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status2" value="0" >
                                <label class="form-check-label" for="status2">
                                Inactive
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" class="form-control" name="location" placeholder="Activity Location" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary float-right" type="submit">Create Activity</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-with-button">
                Add Participant
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Identity</label>
                            <input type="text" class="form-control" name="identity" placeholder="Enter ID">
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<!-- Modal -->
@endsection
@section('script')
<script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
<script>
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
        drops: "auto"
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
@endsection
