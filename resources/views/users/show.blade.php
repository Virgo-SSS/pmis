@extends('layouts.auth')

@section('title-page', 'Users Details')
@section('title', 'Users')
@section('breadcrumb-item-1', 'Users')
@section('breadcrumb-item-1-link', route('user'))
@section('breadcrumb-item-2', 'Details')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->profile->profile_picture ? asset('storage/' . $user->profile->profile_picture) : asset('assets/images/default-picture.jpg') }}" alt="profile picture" class="profile-picture">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->deparment->name ?? '-' }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">User</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
