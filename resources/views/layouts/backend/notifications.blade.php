@if(session('success'))
    <div id="success-alert" class="alert-message" style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #10b981; transition: opacity 0.5s ease-out;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="error-alert" class="alert-message" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #ef4444; transition: opacity 0.5s ease-out;">
        {{ session('error') }}
    </div>
@endif
