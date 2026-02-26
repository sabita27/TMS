@extends('layouts.frontend.master')

@section('title', 'Manager Portal Access')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 2rem 1rem;">
    <!-- Manager Premium Card -->
    <div style="background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); width: 100%; max-width: 450px; overflow: hidden; border: 1px solid #f1f5f9;">
        
        <!-- Amber Accent for Manager Role -->
        <div style="height: 6px; background: linear-gradient(90deg, #f59e0b, #d97706); width: 100%;"></div>

        <div style="padding: 3.5rem 2.5rem;">
            <!-- Branding Section -->
            <div style="text-align: center; margin-bottom: 3rem;">
                @php
                    $sys_logo = \App\Models\Setting::get('system_logo');
                    $sys_name = \App\Models\Setting::get('system_name', 'TMS PRO');
                @endphp
                
                <div style="width: 64px; height: 64px; background: #fffbeb; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid #fef3c7;">
                    <i class="fas fa-user-shield" style="color: #f59e0b; font-size: 1.75rem;"></i>
                </div>
                
                <h1 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Manager Portal</h1>
                <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.5rem; font-weight: 500;">Operations management authentication</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Management Email</label>
                       <div style="position: relative;">
                        <i class="far fa-envelope" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="email" name="email" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="name@company.com" required autofocus onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Access Key</label>
                        <a href="#" style="font-size: 0.75rem; color: #f59e0b; text-decoration: none; font-weight: 700;">Forgot?</a>
                    </div>
                     <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="password" name="password" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="••••••••" required onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #0f172a; color: white; padding: 1.15rem; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='#1e293b'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#0f172a'; this.style.transform='translateY(0)';">
                    Authorize Session
                    <i class="fas fa-shield-alt" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            @if($errors->any())
                <div style="margin-top: 1.5rem; padding: 1rem; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 0.75rem;">
                    @foreach($errors->all() as $error)
                        <div style="color: #ef4444; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                            <i class="fas fa-exclamation-circle"></i> {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div style="background: #f8fafc; padding: 1.25rem; border-top: 1px solid #f1f5f9; text-align: center;">
            <p style="margin: 0; font-size: 0.8rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                <i class="fas fa-lock" style="margin-right: 0.5rem;"></i> Internal Management System
            </p>
        </div>
    </div>
</div>
@endsection
