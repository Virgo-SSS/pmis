@extends('layouts.auth')

@section('title-page', 'Edit User Attendance')
@section('title', 'Edit Attendance')
@section('breadcrumb-item-1', 'Attendance')
@section('breadcrumb-item-1-link', route('attendance'))
@section('breadcrumb-item-2', 'Edit')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Create Attendance</h1>
            <div class="section-header-button">
                <a href="{{ route('attendance') }}" class="btn btn-primary">Back</a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Create Attendance</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select id="user_id" class="form-control" disabled>
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($attendance->user_id == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="clock_in">Clock In</label>
                            <input type="datetime-local" name="clock_in" id="clock_in" class="form-control" required value="{{ $attendance->clock_in->format('Y-m-d H:i:s') }}">
                            @error('clock_in')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="clock_out">Clock Out</label>
                            <input type="datetime-local" name="clock_out" id="clock_out" class="form-control" required value="{{ $attendance->clock_out->format('Y-m-d H:i:s') }}">
                            @error('clock_out')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="overtime">Overtime (Hours)</label>
                            <input type="text" class="form-control time" name="overtime" id="time" maxlength="8" placeholder="00:00:00" value="{{ $attendance->formatted_overtime }}">
                            @error('overtime')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach (App\Enums\AttendanceStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected($attendance->status->value == $status->value)>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control">{{ $attendance->note }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="is_late">is Late</label>
                            <select name="is_late" id="is_late" class="form-control" required>
                                <option value="">Select is Late</option>
                                <option value="1" @selected($attendance->is_late == 1)>Yes</option>
                                <option value="0" @selected($attendance->is_late == 0)>No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('.time').mask('00:00:00');
    </script>
@endsection
