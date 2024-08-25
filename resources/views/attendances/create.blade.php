@extends('layouts.auth')

@section('title-page', 'Create User Attendance')
@section('title', 'Create Attendance')
@section('breadcrumb-item-1', 'Attendance')
@section('breadcrumb-item-1-link', route('attendance'))
@section('breadcrumb-item-2', 'Create')

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
                    <form action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="clock_in">Clock In</label>
                            <input type="datetime-local" name="clock_in" id="clock_in" class="form-control" required value="{{ old('clock_in') }}">
                            @error('clock_in')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="clock_out">Clock Out</label>
                            <input type="datetime-local" name="clock_out" id="clock_out" class="form-control" required value="{{ old('clock_out') }}">
                            @error('clock_out')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="overtime">Overtime (Hours)</label>
                            <input type="text" class="form-control time" name="overtime" id="time" maxlength="8" placeholder="00:00:00" value="{{ old('overtime') }}">
                            @error('overtime')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach (App\Enums\AttendanceStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status') == $status->value)>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="is_late">is Late</label>
                            <select name="is_late" id="is_late" class="form-control" required>
                                <option value="">Select is Late</option>
                                <option value="1" @selected(old('is_late') == 1)>Yes</option>
                                <option value="0" @selected(old('is_late') == 0)>No</option>
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
