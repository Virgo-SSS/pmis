@extends('layouts.auth')

@section('title-page', 'Edit Leave')
@section('title', 'Leaves')
@section('breadcrumb-item-1', 'Leaves')
@section('breadcrumb-item-1-link', route('leave'))
@section('breadcrumb-item-2', 'Edit')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-button">
                <a href="{{ route('leave') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Edit Leave</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('leave.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="text" class="form-control date" name="start_date" id="start_date" value="{{ $leave->start_date }}" required>
                            @error('start_date')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="text" class="form-control date" name="end_date" id="end_date" value="{{ $leave->end_date }}" required>
                            @error('end_date')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3">{{ $leave->reason }}</textarea>
                            @error('reason')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                @foreach (App\Enums\LeaveStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected($leave->status == $status)>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
