@extends('layouts.auth')

@section('title-page', 'Leave Management')
@section('title', 'Leaves')
@section('breadcrumb-item-1', 'Leaves')
@section('breadcrumb-item-1-link', route('leave'))

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Leaves</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Start AT</th>
                                <th>End At</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th>Actions</th>
                            </tr>
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $leave->user->name }}</td>
                                    <td>{{ $leave->start_date->format('d M Y') }}</td>
                                    <td>{{ $leave->end_date->format('d M Y') }}</td>
                                    <td>{{ $leave->reason   }}</td>
                                    <td>{{ $leave->status->name }}</td>
                                    <td>{{ $leave->approvedBy ? $leave->approvedBy->name : '-' }}</td>
                                    <td>
                                        <a href="{{ route('leave.edit', $leave->id) }}"
                                           class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('leave.delete', $leave->id) }}" method="POST"  style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteConfirmation(this)">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
            const form = e.parentElement;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this data!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $(form).submit();
                }
            });
        }
    </script>
@endsection
