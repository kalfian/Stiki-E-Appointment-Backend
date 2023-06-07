@extends('layouts.admin')
@section('title', 'Master Lecture')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/media/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables-bootstrap4.min.css') }}">
@endsection

@section('script')
<script src="{{ asset('assets/modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script>
    $('#table-lecture').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.lectures.datatables') }}",
        },
        columns: [
            {
                data: 'identity',
                name: 'identity',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'gender',
                name: 'gender',
                render: function ( data, type, row ) {
                    var gender = "Male";
                    if (data == 1 ) {
                        gender = "Female";
                    }

                    return '<div class="badge badge-info">'+gender+'</div>';
                }
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
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Master Lecture</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Advanced Table</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer" id="table-lecture">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Lecture Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
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
    </div>
</section>
@endsection
