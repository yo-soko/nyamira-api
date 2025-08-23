<?php $page = 'under-maintenance'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header">Update Credentials To Safeguard Your Account <br><small class="text-info">You may use email and password to signin or username and password</small> </div>
        <div class="card-body">
            <form method="POST" action="{{ route('update.credentials') }}">
                @csrf
                <!-- Code / Username field -->
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="code" value="{{ $user->code }}" 
                        class="form-control" required 
                        autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" 
                        class="form-control" required 
                        autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off">
                </div>

                <div class="mb-3">
                    <label>New Password <small class="text-success">* More than 4 characters *</small></label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" required 
                            autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" id="password">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="ti ti-eye-off"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" class="form-control" required 
                            autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" id="confirmPassword">
                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                            <i class="ti ti-eye-off"></i>
                        </button>
                    </div>
                </div>

                <script>
                    const togglePassword = document.getElementById('togglePassword');
                    const password = document.getElementById('password');
                    togglePassword.addEventListener('click', () => {
                        const type = password.type === 'password' ? 'text' : 'password';
                        password.type = type;
                        togglePassword.innerHTML = type === 'password' ? '<i class="ti ti-eye-off"></i>' : '<i class="ti ti-eye"></i>';
                    });

                    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                    const confirmPassword = document.getElementById('confirmPassword');
                    toggleConfirmPassword.addEventListener('click', () => {
                        const type = confirmPassword.type === 'password' ? 'text' : 'password';
                        confirmPassword.type = type;
                        toggleConfirmPassword.innerHTML = type === 'password' ? '<i class="ti ti-eye-off"></i>' : '<i class="ti ti-eye"></i>';
                    });
                </script>


                <button type="submit" class="btn btn-primary w-100">Save Credentials</button>
            </form>
        </div>
    </div>
</div>
@endsection
