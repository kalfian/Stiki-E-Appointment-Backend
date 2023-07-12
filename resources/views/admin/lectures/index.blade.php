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
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAddLecture"><i class="fas fa-plus"></i> Add Lecture</button>
                    <button class="btn btn-success btn-sm" id="btn-export-reset"><i class="fas fa-file-export"></i> Reset & Export Password</button>
                    <button class="btn btn-info btn-sm"><i class="fas fa-file-import"></i> Import Lecture</button>
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
<div class="modal fade" id="modalAddLecture" tabindex="-1" role="dialog" aria-labelledby="modalAddLectureLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLectureLabel">Add Lecture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="nik" placeholder="Enter ID">
                        @error('id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="gender1" value="0" checked>
                            <label class="form-check-label" for="gender1">
                            Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="gender2" value="1">
                            <label class="form-check-label" for="gender2">
                            Female
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="1" checked>
                            <label class="form-check-label" for="status1">
                            Active
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="0">
                            <label class="form-check-label" for="status2">
                            Inactive
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Lecture</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditLecture" tabindex="-1" role="dialog" aria-labelledby="modalEditLectureLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.lectures.update') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLectureLabel">Edit Lecture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Lecture</button>
                </div>
            </div>
        </form>
    </div>
</div>
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
                buttons: true,
                dangerMode: true,
            }).then((action) => {
                if (action.isConfirmed) {
                    $("#form-reset-password").submit();
                }
            });
        });
    });

    $(document).on('click', '.btn-edit-lecture', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('admin.lectures.edit') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {
                $("#modalEditLecture").find('.modal-body').html(res);
                $("#modalEditLecture").modal('show');
            },
        });

    })

</script>
@endsection
