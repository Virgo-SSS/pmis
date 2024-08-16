@extends('layouts.auth')

@section('title-page', 'Activity Logs')
@section('title', 'Activity Logs')
@section('breadcrumb-item-1', 'Index')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Activity Logs</h4>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Description</th>
                                    <th>DateTime</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $log->causer->name }}</td>
                                        <td>
                                            {{ $log->event }}
                                        </td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->created_at }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#accordion{{ $loop->iteration }}" aria-expanded="false"
                                                aria-controls="accordion{{ $loop->iteration }}">
                                                Details
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="accordion{{ $loop->iteration }}">
                                        <td colspan="6">
                                            <div class="p-3">
                                                @php
                                                    $changes = json_decode($log->changes, true);
                                                    $old = $changes['old'] ?? [];
                                                    $new = $changes['attributes'] ?? [];
                                                @endphp
                                                @if ($changes)
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h5>Old</h5>
                                                            <table class="table table-bordered">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>Field</th>
                                                                        <th>Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($old as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key }}</td>
                                                                            <td>{{ $value }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h5>New</h5>
                                                            <table class="table table-bordered">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>Field</th>
                                                                        <th>Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($new as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key }}</td>
                                                                            <td>{{ $value }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection