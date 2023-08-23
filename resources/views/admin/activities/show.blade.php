@extends('layouts.admin')
@section('title', 'Detail Activity')


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatables-checkboxes/css/dataTables.checkboxes.css') }}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.activities.index') }}">Activities</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $activity->name }}</li>
@endsection

@section('content')
<!-- Content Row -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-with-button">
                Detail Activity
                <div class="list-button">
                    <h5><span class="badge badge-{{ referenceStatus()::translateStatusColor($activity->status) }}">{{ referenceStatus()::translateStatus($activity->status) }}</span></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary btn-sm float-right" href="{{ route('admin.students.edit', $activity->id) }}"><i class="fas fa-edit"></i> Edit Activity</a>
                        <h3>{{ $activity->name }}</h3>

                        <p><i class="fas fa-map-marker-alt"></i> {{ $activity->location }}</p>
                        <p><i class="fas fa-clock"></i> {{ carbon_format($activity->start_date, 'd F Y') }} - {{ carbon_format($activity->end_date, 'd F Y') }}</p>
                        @if($activity->banner)
                        <img src="{{ $activity->banner->getUrl() }}" alt="{{ $activity->name }}" class="img-fluid">
                        @endif
                        {!! $activity->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-with-button">
                Participants
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @foreach($participants as $participant)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="container-participant">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <i class="fas fa-user icon-big"></i>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="container-profile-user-list pl-5">
                                                <p class="no-margin">
                                                    @if($participant->is_lecturer)
                                                    <span class="badge badge-info">Lecture</span>
                                                    @else
                                                    <span class="badge badge-success">Student</span>
                                                    @endif
                                                </p>
                                                <p class="no-margin">{{$participant->user->identity}} - {{ $participant->user->name }}</p>
                                                <p class="no-margin"><a href="mailto:{{ $participant->user->email }}">{{ $participant->user->email }}</a></p>
                                                <p class="no-margin">{{ $participant->user->phone_number }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!$participant->is_lecturer)
                                    <a href="#" class="btn btn-primary btn-sm btn-block">View Logbook</a>
                                    @endif
                                    <hr>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
