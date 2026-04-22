@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manage Users Add</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Manage Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email address" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select user role...</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Limited Access)</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="webpage_url" class="form-label fw-bold">Logout Webpage URL <span class="text-muted small">(Optional)</span></label>
                                <input type="url" name="webpage_url" id="webpage_url" class="form-control @error('webpage_url') is-invalid @enderror" value="{{ old('webpage_url') }}" placeholder="https://example.com">
                                @error('webpage_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save User
                                </button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary px-4 ms-2">
                                    <i class="fa fa-arrow-left me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
