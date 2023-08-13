@extends('layouts.admin')
@section('title', 'Settings')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-with-button">
                Default Password Setting
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.settings.update', $userDefaultPassword->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control password-toggle" name="default_password" placeholder="Enter password" value="{{$userDefaultPassword->value}}">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            @error('default_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Save Default Password</button>
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
