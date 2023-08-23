@extends('layouts.admin')
@section('title', 'Master Activities')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Lectures</li>
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-with-button">
                List Activities
                <form method="POST" action="{{ route('auth.export.reset_password')}} " id="form-reset-password">
                @csrf
                </form>
                <div class="list-button">
                    {{-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAddLecture"><i class="fas fa-plus"></i> Add Lecture</button> --}}
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.activities.create') }}"><i class="fas fa-plus"></i> Add Activity</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-activity" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Created</th>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Student</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@endsection

@section('script')
<script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#table-activity').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.activities.datatables') }}",
            },
            order: [[ 1, "desc" ]],
            columns: [
                {
                    data: 'banner_image',
                    name: 'banner_image',
                    orderable: false,
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        return moment(data).fromNow();
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'students_count',
                    name: 'students_count'
                },
                {
                    data: 'active_status',
                    name: 'active_status',
                    render: function(data, type, row) {
                        badge = "success";
                        status = "active";

                        switch (data) {
                            case 0:
                                badge = "warning";
                                status = "Inactive";
                                break;
                            case 1:
                                badge = "success";
                                status = "Active";
                                break;
                            case -1:
                                badge = "danger";
                                status = "Banned";
                                break;
                            default:
                                badge = "success";
                                status = "Active";
                                break;
                        }

                        return '<div class="badge badge-'+badge+'">'+status+'</div>';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                }
            ]
        });
    });

</script>
@endsection
