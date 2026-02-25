@extends('layouts.frontend.master')

@section('title', 'Admin Command Center')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 2rem 1rem;">
    <!-- Admin Premium Card -->
    <div style="background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.12); width: 100%; max-width: 450px; overflow: hidden; border: 1px solid #f1f5f9;">
        
        <!-- Deep Indigo Accent for Admin Role -->
        <div style="height: 6px; background: linear-gradient(90deg, #4f46e5, #0f172a); width: 100%;"></div>

        <div style="padding: 3.5rem 2.5rem;">
            <!-- Branding Section -->
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="width: 64px; height: 64px; background: #eef2ff; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid #e0e7ff; position: relative;">
                    <i class="fas fa-terminal" style="color: #4f46e5; font-size: 1.75rem;"></i>
                    <span style="position: absolute; bottom: -5px; right: -5px; width: 18px; height: 18px; background: #ef4444; border: 3px solid white; border-radius: 50%;"></span>
                </div>
                
                <h1 style="font-size: 2rem; font-weight: 900; color: #0f172a; margin: 0; letter-spacing: -0.05em; text-transform: uppercase;">ADMIN LOGIN</h1>
                <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem; font-weight: 700;">SYSTEM AUTHORIZATION REQUIRED</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 900; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Command ID (Email)</label>
                    <div style="position: relative;">
                        <i class="fas fa-user-secret" style="position: absolute; left: 1.25rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="email" name="email" style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 0.95rem; background: #f8fafc; transition: all 0.2s; outline: none; color: #0f172a;" placeholder="root@system" required autofocus onfocus="this.style.borderColor='#4f46e5'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 900; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Security Clear-Key</label>
                    <div style="position: relative;">
                        <i class="fas fa-key" style="position: absolute; left: 1.25rem; top: 1rem; color: #94a3b8;"></i>
                        <input type="password" name="password" style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 0.95rem; background: #f8fafc; transition: all 0.2s; outline: none; color: #0f172a;" placeholder="••••••••••••" required onfocus="this.style.borderColor='#4f46e5'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #0f172a; color: white; padding: 1.15rem; border: none; border-radius: 0.75rem; font-weight: 900; font-size: 1rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem; letter-spacing: 0.05em;" onmouseover="this.style.background='#4f46e5'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#0f172a'; this.style.transform='translateY(0)';">
                    INITIATE BYPASS
                    <i class="fas fa-unlock-alt" style="font-size: 0.85rem;"></i>
                </button>
            </form>

            @if($errors->any())
                <div style="margin-top: 1.5rem; padding: 1rem; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 0.75rem;">
                    @foreach($errors->all() as $error)
                        <div style="color: #ef4444; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-family: monospace;">
                            [AUTH_ERR] {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div style="background: #0f172a; padding: 1.25rem; text-align: center; color: rgba(255,255,255,0.4); font-family: monospace; font-size: 0.7rem; letter-spacing: 0.1em;">
            ENCRYPTION: AES-256-GCM // LOGGING ACTIVE
        </div>
    </div>
</div>
@endsection
