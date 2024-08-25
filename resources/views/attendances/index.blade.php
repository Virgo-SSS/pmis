@extends('layouts.auth')

@section('title-page', 'Attendances')
@section('title', 'Attendance Management')
@section('breadcrumb-item-1', 'Attendance')
@section('breadcrumb-item-1-link', route('attendance'))

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Attendance Management</h1>
            <div class="section-header-button">
                <a href="{{ route('attendance.create') }}" class="btn btn-primary">Add New</a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Overtime (Hours)</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Actions</th>
                            </tr>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->clock_in->format('d M Y H:i:s') }}</td>
                                    <td>{{ $attendance->clock_out->format('d M Y H:i:s') }}</td>
                                    <td>{{ $attendance->formatted_overtime }}</td>
                                    <td>{{ $attendance->status->name }}</td>
                                    <td>{{ $attendance->note }}</td>
                                    <td>
                                        <a href="{{ route('attendance.edit', $attendance->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('attendance.delete', $attendance->id) }}" method="POST"
                                            style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">Delete</button>
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
