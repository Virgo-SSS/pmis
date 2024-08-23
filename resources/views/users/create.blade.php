@extends('layouts.auth')

@section('title-page', 'User')
@section('title', 'Create User')
@section('breadcrumb-item-1', 'User')
@section('breadcrumb-item-1-link', route('role'))
@section('breadcrumb-item-2', 'Create User')

@section('content')
    <section class="section">
        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Personal Details</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <img id="preview" style="width:9.1rem" class="img-fluid mt-2 mb-2" src="{{ asset('assets/img/default-profile.jpg') }}" alt="Profile Picture">
                                        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                        @error('profile_picture')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="joined_at">Joined At <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" value="{{ old('joined_at') }}" id="joined_at" name="joined_at" required>
                                        @error('joined_at')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role <span class="text-danger">*</span></label>
                                        <select class="form-control" id="role" name="roles[]" multiple="multiple" required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @selected(in_array($role->name, old('roles') ?? []))>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Enter name" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Enter email" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="">--Gender--</option>
                                            <option value="M" @selected(old('gender') == 'M')>Male</option>
                                            <option value="F" @selected(old('gender') == 'F')>Female</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department <span class="text-danger">*</span></label>
                                        <select class="form-control" id="department" name="department_id" required>
                                            <option value="">-- Select Department --</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Contact</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" value="{{ old('phone') }}" id="phone" name="phone" placeholder="Enter phone">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" class="form-control" value="{{ old('emergency_contact') }}" id="emergency_contact" name="emergency_contact" placeholder="Enter emergency contact">
                                        @error('emergency_contact')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" placeholder="Enter address">{{ old('address') }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Account</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('username') }}" id="username" name="username" placeholder="Enter username" required>
                                        @error('username')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Bank</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="account_bank_name">Account Bank Name</label>
                                        <input type="text" class="form-control" value="{{ old('account_name') }}" id="account_bank_name" name="account_name" placeholder="Enter account bank name">
                                        @error('account_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="account_bank_number">Account Bank Number</label>
                                        <input type="text" class="form-control" value="{{ old('account_number') }}" id="account_bank_number" name="account_number" placeholder="Enter account bank number">
                                        @error('account_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="bank">Bank</label>
                                        <select class="form-control" id="bank" name="bank_id">
                                            <option value="">-- Select Bank --</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}" @selected($bank->id == old('bank_id'))>{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Profile Picture Preview
            $('#profile_picture').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            });

            $('#role').select2();
        });
    </script>
@endsection
