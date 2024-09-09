@extends('layouts.auth')

@section('title-page', 'Review Leave')
@section('title', 'Leaves')
@section('breadcrumb-item-1', 'Leaves')
@section('breadcrumb-item-1-link', route('leave'))

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Review Leave</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th>Status</th>
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
                                    <td>
                                        <form action="{{ route('leave.approve', $leave->id) }}" method="POST"
                                            style="display: inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="processLeaveConfirmation(this, 'approve')">Approve</button>
                                        </form>
                                        <form action="{{ route('leave.reject', $leave->id) }}" method="POST"
                                            style="display: inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="processLeaveConfirmation(this, 'reject')">Reject</button>
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
        function processLeaveConfirmation(e, status) {
            let form = e.parentElement;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + status + ' this leave request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: status
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
