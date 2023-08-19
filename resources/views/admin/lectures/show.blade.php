@extends('layouts.admin')
@section('title', 'Detail Lecture')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.lectures.index') }}">Lectures</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $lecture->name }}</li>
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-with-button">
                Detail Lecture
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tr>
                                <td>Identity</td>
                                <td>:</td>
                                <td>{{ $lecture->identity }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{ $lecture->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{ $lecture->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td>:</td>
                                <td>{{ $lecture->phone_number }}</td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>:</td>
                                <td>{{ translateGender($lecture->gender) }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>{{ referenceStatus()::translateStatus($lecture->active_status) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-with-button">
                Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('admin.lectures.edit', $lecture->id) }}" class='btn btn-sm btn-primary btn-block btn-edit-student'><i class='fas fa-edit'></i> Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('admin.lectures.modal')
@endsection
