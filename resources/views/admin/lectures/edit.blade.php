@extends('layouts.admin')
@section('title', 'Create Lecture')


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
                Edit Lecture
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.lectures.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" name="id" value="{{ $lecture->id }}">
                            <div class="form-group">
                                <label>ID</label>
                                <input type="text" class="form-control" name="identity" placeholder="Enter ID" value="{{ $lecture->identity }}">
                                @error('id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{ $lecture->name }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender1" value="0" @if($lecture->gender == 0) @checked(true) @endif>
                                    <label class="form-check-label" for="gender1">
                                    Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender2" value="1" @if($lecture->gender == 1) @checked(true) @endif>
                                    <label class="form-check-label" for="gender2">
                                    Female
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number" value="{{ $lecture->phone_number }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{ $lecture->email }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status1" value="1" @if($lecture->active_status == 1) @checked(true) @endif>
                                    <label class="form-check-label" for="status1">
                                    Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status2" value="0" @if($lecture->active_status == 0) @checked(true) @endif>
                                    <label class="form-check-label" for="status2">
                                    Inactive
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Update Lecture</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('admin.lectures.modal')
@endsection
