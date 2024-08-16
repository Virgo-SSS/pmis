@extends('layouts.auth')

@section('title-page', 'Role and Permission Control')
@section('title', 'Create Role')
@section('breadcrumb-item-1', 'Role and Permission Control')
@section('breadcrumb-item-1-link', route('role'))
@section('breadcrumb-item-2', 'Create Role')

@section('content')
<section class="section">
    <form action="{{ route('role.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Role</h5>
                        <div class="row">
                            <div class="col-12">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="role-name" placeholder="Role Name e.g. Admin">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="btn-create-role">Create Role</button>
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
                                                <input class="form-check-input" name="permissions[]" @checked(is_array(old('permissions')) && in_array($permission->id, old('permissions')))
                                                type="checkbox" id="permission-{{ $permission->id }}" value="{{ $permission->id }}">
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
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
    </form>
</section>
@endsection