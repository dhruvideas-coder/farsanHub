@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('portal.manage_users') }} {{ __('portal.add') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn d-none d-xl-flex d-lg-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('portal.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('portal.manage_users') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('portal.add') }}</li>
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
                                <label for="name" class="form-label fw-bold">{{ __('portal.name') }} (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('portal.enter_full_name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="name_gu" class="form-label fw-bold">{{ __('portal.name') }} (ગુજરાતી)</label>
                                <input type="text" name="name_gu" id="name_gu" class="form-control" value="{{ old('name_gu') }}" placeholder="મોહનભાઇ">
                                <small class="text-muted">{{ __('portal.auto_translate_note') ?? 'Leave blank to auto-translate' }}</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold">{{ __('portal.email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('portal.enter_email_address') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label fw-bold">{{ __('portal.role') }} <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="" disabled selected>{{ __('portal.select_user_role') }}</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>{{ __('portal.super_admin_full_access') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('portal.admin_limited_access') }}</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="webpage_url" class="form-label fw-bold">{{ __('portal.logout_webpage_url') }} <span class="text-muted small">({{ __('portal.optional') }})</span></label>
                                <input type="url" name="webpage_url" id="webpage_url" class="form-control @error('webpage_url') is-invalid @enderror" value="{{ old('webpage_url') }}" placeholder="https://example.com">
                                @error('webpage_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> {{ __('portal.save_user') }}
                                </button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary px-4 ms-2">
                                    <i class="fa fa-arrow-left me-1"></i> {{ __('portal.cancel') }}
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
