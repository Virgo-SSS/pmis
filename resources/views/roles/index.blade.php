@extends('layouts.auth')

@section('title-page', 'Role and Permission Control')
@section('title', 'Role and Permission Control')
@section('breadcrumb-item-1', 'Role and Permission Control')
@section('breadcrumb-item-1-link', route('role'))
@section('breadcrumb-item-2', 'Dashboard')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Role List</h4>
                        <a href="{{ route('role.create') }}" class="btn btn-primary">Create Role</a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <a href="{{ route('role.show', $role->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('role.delete', $role->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirmation(this)">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function deleteConfirmation(e) {
            var form = e.parentNode;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endsection