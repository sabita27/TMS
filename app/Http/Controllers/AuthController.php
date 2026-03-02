<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function showManagerLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        \Log::info('Login attempt', ['email' => $request->email]);
        
        $user = User::where('email', $request->email)->first();
        if ($user) {
            \Log::info('User found', ['email' => $user->email, 'status' => $user->status]);
            $passwordMatch = \Hash::check($request->password, $user->password);
            \Log::info('Password match check', ['match' => $passwordMatch]);
            
            if (Auth::attempt($credentials)) {
                \Log::info('Login successful via Auth::attempt', ['user_id' => Auth::id(), 'roles' => Auth::user()->getRoleNames()]);
                if (Auth::user()->status == 0) {
                    \Log::warning('User deactivated', ['user_id' => Auth::id()]);
                    Auth::logout();
                    return back()->withErrors(['email' => 'Your account is currently deactivated. Please contact support.']);
                }
                $request->session()->regenerate();
                return $this->redirectUser(Auth::user())->with('success', 'Logged in successfully!');
            } else {
                \Log::warning('Auth::attempt failed despite manual match check being ' . ($passwordMatch ? 'TRUE' : 'FALSE'), ['email' => $request->email]);
            }
        } else {
            \Log::warning('User not found in login attempt', ['email' => $request->email]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userRole = \App\Models\Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $userRole->id, // Keep legacy role_id for now for compatibility
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        Auth::login($user);

        return $this->redirectUser($user)->with('success', 'Account created and logged in successfully!');
    }

    public function logout(Request $request)
    {
        \Log::info('Logout method called', ['user_id' => Auth::id()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        \Log::info('User logged out successfully');
        return redirect()->route('login');
    }

    public function getLogout(Request $request)
    {
        return $this->logout($request);
    }

    private function redirectUser($user)
    {
        \Log::info('Redirecting user', ['user_id' => $user->id, 'roles' => $user->getRoleNames()]);

        // Attempt to redirect to a role-specific dashboard if the route exists
        $role = strtolower($user->getRoleNames()->first());
        if ($role && \Route::has("$role.dashboard")) {
            return redirect()->route("$role.dashboard");
        }

        // Fallback or explicit mapping for established roles
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard');
        }
        
        // Final fallback for 'user' role or any new custom roles
        return redirect()->route('user.dashboard');
    }
    
}
