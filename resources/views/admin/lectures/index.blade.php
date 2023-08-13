@extends('layouts.admin')
@section('title', 'Master Lecture')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-with-button">
                List Lectures
                <form method="POST" action="{{ route('auth.export.reset_password')}} " id="form-reset-password">
                @csrf
                </form>
                <div class="list-button">
                    {{-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAddLecture"><i class="fas fa-plus"></i> Add Lecture</button> --}}
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.lectures.create') }}"><i class="fas fa-plus"></i> Add Lecture</a>
                    <button class="btn btn-success btn-sm" id="btn-export-reset"><i class="fas fa-file-export"></i> Reset & Export Password</button>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalImportLecture"><i class="fas fa-file-import"></i> Import Lecture</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-lecture" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all" /></th>
                                <th>ID</th>
                                <th>Lecture Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
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

<!-- Modal -->
@include('admin.lectures.modal')
@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#table-lecture').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            select: 'multi',
            ajax: {
                url: "{{ route('admin.lectures.datatables') }}",
            },
            order: [
                [1, 'desc']
            ],
            columnDefs: [
                {
                    targets: 0,
                    checkboxes: {
                        selectRow: true,
                        stateSave: false
                    }
                }
            ],
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
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
                    data: 'phone_number',
                    name: 'phone_number'
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

        $('#form-reset-password').on('submit', function(e){
            var form = this;

            var rows_selected = table.column(0).checkboxes.selected();

            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, row){
                // Create a hidden element
                var value = $(row).val();
                $(form).append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'user_ids[]')
                        .val(value)
                );
            });
        });

        $("#btn-export-reset").on('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You will reset password for selected user(s)!",
                icon: "warning",
                showCancelButton: true,
                showDenyButton: true,
                denyButtonText: "Yes, use default password",
                denyButtonColor: "#3085d6",
                buttons: true,
                dangerMode: true,
            }).then((action) => {
                if (action.isConfirmed) {
                    $("#form-reset-password").submit();
                } else if (action.isDenied) {
                    $("#form-reset-password").append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'use_default_password')
                            .val(1)
                    );
                    $("#form-reset-password").submit();
                }
            });
        });
    });

</script>
@endsection
