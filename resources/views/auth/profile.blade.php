@extends('layouts.backend.master')

@section('page_title', 'My Profile')
@section('header_height', '85px')
@section('header_padding', '0 2.5rem')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 2rem;">
        <!-- Left: Profile Info Card -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <div class="card" style="text-align: center; padding: 3rem 2rem; border-radius: 1.5rem;">
                <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=fff&size=128" 
                         style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                    <div style="position: absolute; bottom: 5px; right: 5px; background: #10b981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff;"></div>
                </div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ $user->name }}</h3>
                <p style="margin: 0.25rem 0 1.5rem 0; color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem; text-align: left; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; color: #64748b; font-size: 0.9rem;">
                        <i class="fas fa-envelope" style="width: 16px;"></i> {{ $user->email }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; color: #64748b; font-size: 0.9rem;">
                        <i class="fas fa-phone" style="width: 16px;"></i> {{ $user->phone ?? 'Not provided' }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; color: #64748b; font-size: 0.9rem;">
                        <i class="fas fa-calendar-alt" style="width: 16px;"></i> Joined {{ $user->created_at->format('M Y') }}
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 1.5rem; border-radius: 1rem; background: var(--primary-color); color: white;">
                <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 800;">Account Security</h4>
                <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; line-height: 1.5;">Keep your account safe by updating your password regularly.</p>
            </div>
        </div>

        <!-- Right: Settings Tabs -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Personal Information -->
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-user-circle" style="color: var(--primary-color);"></i> Personal Information
                </h3>
                
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 700;">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required style="height: 50px; border-radius: 0.75rem;">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 700;">Phone Number</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" style="height: 50px; border-radius: 0.75rem;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label" style="font-weight: 700;">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required style="height: 50px; border-radius: 0.75rem;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; border-radius: 0.75rem; font-weight: 800;">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Password Change -->
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-lock" style="color: #ef4444;"></i> Change Password
                </h3>
                
                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-weight: 700;">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required style="height: 50px; border-radius: 0.75rem;" placeholder="••••••••">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 700;">New Password</label>
                            <input type="password" name="password" class="form-control" required style="height: 50px; border-radius: 0.75rem;" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 700;">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required style="height: 50px; border-radius: 0.75rem;" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger" style="padding: 0.85rem 2rem; border-radius: 0.75rem; font-weight: 800; background: #ef4444;">
                        Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
