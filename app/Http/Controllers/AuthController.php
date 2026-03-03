<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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

        Log::info('Login attempt', ['email' => $request->email]);
        
        $user = User::where('email', $request->email)->first();
        if ($user) {
            Log::info('User found', ['email' => $user->email, 'status' => $user->status]);
            $passwordMatch = Hash::check($request->password, $user->password);
            Log::info('Password match check', ['match' => $passwordMatch]);
            
            if (Auth::attempt($credentials)) {
                Log::info('Login successful via Auth::attempt', ['user_id' => Auth::id(), 'roles' => Auth::user()->getRoleNames()]);
                if (Auth::user()->status == 0) {
                    Log::warning('User deactivated', ['user_id' => Auth::id()]);
                    Auth::logout();
                    return back()->withErrors(['email' => 'Your account is currently deactivated. Please contact support.']);
                }
                $request->session()->regenerate();
                return $this->redirectUser(Auth::user())->with('success', 'Logged in successfully!');
            } else {
                Log::warning('Auth::attempt failed despite manual match check being ' . ($passwordMatch ? 'TRUE' : 'FALSE'), ['email' => $request->email]);
            }
        } else {
            Log::warning('User not found in login attempt', ['email' => $request->email]);
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

        $userRole = Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $userRole->id ?? null, // Safe check if role exists
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        Auth::login($user);

        return $this->redirectUser($user)->with('success', 'Account created and logged in successfully!');
    }

    public function logout(Request $request)
    {
        Log::info('Logout method called', ['user_id' => Auth::id()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('User logged out successfully');
        return redirect()->route('login');
    }

    public function getLogout(Request $request)
    {
        return $this->logout($request);
    }

    private function redirectUser($user)
    {
        Log::info('Redirecting user', ['user_id' => $user->id, 'roles' => $user->getRoleNames()]);

        // If user can view dashboard, send them to the appropriate one
        if ($user->can('view dashboard')) {
            $role = strtolower($user->getRoleNames()->first());
            if ($role && Route::has("$role.dashboard")) {
                return redirect()->route("$role.dashboard");
            }

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('manager')) {
                return redirect()->route('manager.dashboard');
            } elseif ($user->hasRole('staff')) {
                return redirect()->route('staff.dashboard');
            }
            
            return redirect()->route('user.dashboard');
        }

        // If they can't view dashboard but can manage tickets, send them to ticket list
        if ($user->can('manage tickets')) {
            return redirect()->route('user.tickets');
        }

        // Final fallback: profile page
        return redirect()->route('user.profile');
    }
    
}
