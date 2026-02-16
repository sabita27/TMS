@extends('layouts.frontend.master')

@section('title', 'Login')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 70vh;">
    <div style="background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem;">TMS PRO</h1>
            <p style="color: #6b7280; font-size: 0.875rem;">Enter your credentials to access your account</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; box-sizing: border-box;" placeholder="john@example.com" required autofocus>
                @error('email')
                    <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; box-sizing: border-box;" placeholder="••••••••" required>
            </div>

            <button type="submit" style="width: 100%; background: var(--primary-gradient); color: #fff; padding: 0.75rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #6b7280;">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Create one</a>
        </div>
    </div>
</div>
@endsection
