@extends('layouts.frontend.master')

@section('title', 'Login Portal')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 75vh; padding: 2rem 1rem;">
    <!-- Main Premium Card -->
    <div style="background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); width: 100%; max-width: 450px; overflow: hidden; border: 1px solid #f1f5f9;">
        
        <!-- Subtle Top Accent -->
        <div style="height: 6px; background: linear-gradient(90deg, #6366f1, #a855f7); width: 100%;"></div>

        <div style="padding: 3.5rem 2.5rem;">
            <!-- Branding Section -->
            <div style="text-align: center; margin-bottom: 3rem;">
                @php
                    $sys_logo = \App\Models\Setting::get('system_logo');
                    $sys_name = \App\Models\Setting::get('system_name', 'TMS PRO');
                @endphp
                
                <div style="margin-bottom: 1.5rem; display: inline-block;">
                    @if($sys_logo)
                        <img src="{{ asset('storage/' . $sys_logo) }}" style="height: 45px;">
                    @else
                        <div style="width: 56px; height: 56px; background: #6366f1; border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2);">
                            <i class="fas fa-shield-alt" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                    @endif
                </div>
                
                <h1 style="font-size: 2.25rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em; line-height: 1.1;">{{ $sys_name }}</h1>
                <p style="color: #64748b; font-size: 0.95rem; margin-top: 0.75rem; font-weight: 500;">Secure gateway authentication system</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Credential ID</label>
                    <div style="position: relative;">
                        <i class="far fa-envelope" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="email" name="email" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="name@company.com" required autofocus onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Access Key</label>
                        <a href="#" style="font-size: 0.75rem; color: #6366f1; text-decoration: none; font-weight: 700;">Forgot Pin?</a>
                    </div>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="password" name="password" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; background: #f8fafc; transition: all 0.2s; outline: none; box-sizing: border-box;" placeholder="••••••••" required onfocus="this.style.borderColor='#6366f1'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.05)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #0f172a; color: white; padding: 1.15rem; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='#1e293b'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#0f172a'; this.style.transform='translateY(0)';">
                    Establish Connection
                    <i class="fas fa-arrow-right" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: #64748b; font-weight: 500;">
                New to the platform? <a href="{{ route('register') }}" style="color: #6366f1; text-decoration: none; font-weight: 700;">Create Identity</a>
            </div>

            <!-- Specialized Access Grid -->
            <div style="margin-top: 3.5rem; border-top: 1px solid #f1f5f9; padding-top: 2.5rem;">
                <div style="text-align: center; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem;">Access Operational Nodes</div>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('manager.login') }}" style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1rem; text-decoration: none; transition: 0.2s;" onmouseover="this.style.borderColor='#6366f1'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                        <div style="width: 38px; height: 38px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.25rem;">
                            <i class="fas fa-user-shield" style="color: #6366f1;"></i>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #334155;">Management</span>
                    </a>
                    <a href="{{ route('login') }}" style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1rem; text-decoration: none; transition: 0.2s;" onmouseover="this.style.borderColor='#10b981'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
                        <div style="width: 38px; height: 38px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.25rem;">
                            <i class="fas fa-user-tie" style="color: #10b981;"></i>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #334155;">Staff Node</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Visual -->
        <div style="background: #f8fafc; padding: 1.25rem; text-align: center; border-top: 1px solid #f1f5f9;">
            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-shield-alt" style="color: #10b981;"></i>
                Secure Encrypted Session Active
            </div>
        </div>
    </div>
</div>
@endsection
