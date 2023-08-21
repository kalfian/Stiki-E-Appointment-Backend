@extends('layouts.admin')
@section('title', 'Create Student')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
<li class="breadcrumb-item active" aria-current="page">Create Student</li>
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card">
            <div class="card-header card-with-button">
                Create Student
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Identity</label>
                            <input type="text" class="form-control" name="identity" placeholder="Enter ID">
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Study Program</label>
                            <select name="major" class="form-control" >
                                <option value="">Select Study Program</option>
                                <option value="ti">Teknik Informatika</option>
                                <option value="si">Sistem Informasi</option>
                                <option value="mi">Manajemen Informatika</option>
                                <option value="dkv">Desain Komunikasi Visual</option>
                            </select>
                            @error('name')
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
                                <input class="form-check-input" type="radio" name="gender" id="gender1" @checked(true) value="0">
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
                            <label>Use Default Password</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="use_default_password" @checked(true) id="use_default_password1" value="1" >
                                <label class="form-check-label" for="use_default_password1">
                                Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="use_default_password" id="use_default_password2" value="0" >
                                <label class="form-check-label" for="use_default_password2">
                                No
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary float-right" type="submit">Create student</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('admin.students.modal')
@endsection
