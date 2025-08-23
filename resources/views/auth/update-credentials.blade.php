{{-- resources/views/auth/update-credentials.blade.php --}}
@extends('layout.mainlayout')

@section('content')
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header">Update Your Credentials</div>
        <div class="card-body">
            <form method="POST" action="{{ route('update.credentials') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Save Credentials</button>
            </form>
        </div>
    </div>
</div>
@endsection
