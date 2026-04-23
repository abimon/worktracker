<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load([
            'projects',
            'collaboratedProjects',
            'verificationDocuments',
            'payments',
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        }

        return view('profile.show', ['user' => $user]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $request->user()->update($validated);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $request->user(),
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ]);
        }

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    /**
     * Get user dashboard statistics
     */
    public function dashboardStats(Request $request)
    {
        $user = $request->user();
        $projects = $user->projects;

        $stats = [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', 'active')->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'total_budget' => $projects->sum('budget'),
            'total_earned' => $projects->sum('paid_amount'),
            'pending_payments' => $projects->flatMap(function ($p) {
                return $p->payments()->where('status', 'pending')->get();
            })->sum('amount'),
            'pending_collaborations' => $user->collaboratedProjects()
                ->wherePivot('status', 'pending')
                ->count(),
        ];

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        }

        return view('dashboard', ['stats' => $stats, 'projects' => $projects]);
    }
}
