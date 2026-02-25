@extends('layouts.frontend.master')

@section('title', 'Register')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 2rem 1rem;">
    <!-- Main Premium Card -->
    <div style="background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); width: 100%; max-width: 480px; overflow: hidden; border: 1px solid #f1f5f9;">
        
        <!-- Subtle Top Accent -->
        <div style="height: 6px; background: linear-gradient(90deg, #6366f1, #a855f7); width: 100%;"></div>

        <div style="padding: 3.5rem 2.5rem;">
            <!-- Branding Section -->
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="width: 56px; height: 56px; background: #6366f1; border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2);">
                    <i class="fas fa-user-plus" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Create Account</h1>
                <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.5rem; font-weight: 500;">Join TMS PRO to start managing your tickets</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Full Name</label>
                    <div style="position: relative;">
                        <i class="far fa-user" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="text" name="name" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="John Doe" required autofocus onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                    @error('name')
                        <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Email Address</label>
                    <div style="position: relative;">
                        <i class="far fa-envelope" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="email" name="email" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="john@example.com" required onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                    @error('email')
                        <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Password</label>
                        <input type="password" name="password" style="width: 100%; padding: 0.875rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" required onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Confirm</label>
                        <input type="password" name="password_confirmation" style="width: 100%; padding: 0.875rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" required onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #0f172a; color: white; padding: 1.15rem; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='#1e293b'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#0f172a'; this.style.transform='translateY(0)';">
                    Create Account
                    <i class="fas fa-arrow-right" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: #64748b; font-weight: 500;">
                Already have an account? <a href="{{ route('login') }}" style="color: #6366f1; text-decoration: none; font-weight: 700;">Sign In</a>
            </div>
        </div>
    </div>
</div>
@endsection
