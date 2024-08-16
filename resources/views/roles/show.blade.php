@extends('layouts.auth')

@section('title-page', 'Role and Permission Control')
@section('title', 'Role Detail')
@section('breadcrumb-item-1', 'Role and Permission Control')
@section('breadcrumb-item-1-link', route('role'))
@section('breadcrumb-item-2', 'Role Detail')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Role</h5>
                    <div class="row">
                        <div class="col-12">
                            <h3>{{ $role->name }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Permissions</h5>
                    <div class="row">
                        @foreach($permissions->chunk(4) as $chunk)
                            <div class="row">
                                @foreach($chunk as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            {{-- add icon check-square-fill if user has the permission, and  x-square-fill if user doesn't have the permission --}}
                                            <label class="form-check-label">
                                                @if($role->hasPermissionTo($permission->name))
                                                    <i class="bi bi-check-square-fill text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square-fill text-danger"></i>
                                                @endif
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection