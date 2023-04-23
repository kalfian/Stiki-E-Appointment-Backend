@extends('layouts.admin')
@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/summernote/dist/summernote-bs4.min.css') }}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-person-chalkboard"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Lecture</h4>
                    </div>
                    <div class="card-body">
                        10
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Student</h4>
                    </div>
                    <div class="card-body">
                        42
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Activity</h4>
                    </div>
                    <div class="card-body">
                        1,201
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
