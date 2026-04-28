<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Get all projects for the authenticated user
     */
    public function index()
    {
        $user = request()->user();
        $projects = Project::where('developer_id', $user->id)
            ->with(['tasks', 'payments', 'collaborators', 'feedbacks'])
            ->paginate(15);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $projects,
            ]);
        }

        return view('projects.index', ['projects' => $projects]);
    }

    /**
     * Create a new project
     */
    public function store()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'nullable|date',
            'start_date' => 'nullable|date',
            'preview_url' => 'nullable|url',
        ]);

        $project = Project::create([
            ...$validated,
            'developer_id' => request()->user()->id,
            'status' => 'draft',
        ]);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'data' => $project->load(['tasks', 'payments', 'collaborators']),
            ],201);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully');
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        if (request()->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('projects.create');
    }

    /**
     * Get a specific project
     */
    public function show(Project $project)
    {
        $this->authorizeProjectAccess(request()->user(), $project);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $project->load(['tasks', 'payments', 'collaborators', 'feedbacks']),
            ]);
        }

        return view('projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing a project
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        if (request()->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('projects.edit', ['project' => $project]);
    }

    /**
     * Update a project
     */
    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:draft,active,on_hold,completed,archived',
            'progress' => 'sometimes|in:planning,in_progress,review,completed',
            'progress_percentage' => 'sometimes|integer|min:0|max:100',
            'deadline' => 'nullable|date',
            'start_date' => 'nullable|date',
            'preview_url' => 'nullable|url',
        ]);

        $project->update($validated);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project,
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully');
    }

    /**
     * Delete a project
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully',
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

    /**
     * Generate share token for clients
     */
    public function generateShareToken(Project $project)
    {
        $this->authorize('update', $project);

        $token = $project->generateShareToken();

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Share token generated successfully',
                'share_link' => route('projects.shared', $token),
                'token' => $token,
            ]);
        }

        return back()->with('success', 'Share token generated successfully');
    }

    /**
     * Get shared project (accessible without authentication)
     */
    public function showShared($token)
    {
        $project = Project::where('share_token', $token)->firstOrFail();

        return view('projects.shared', ['project' => $project->load(['developer', 'tasks', 'payments', 'feedbacks'])]);
    }

    /**
     * Helper method to check if user can access project
     */
    private function authorizeProjectAccess($user, $project)
    {
        if ($project->developer_id == $user->id) {
            return true;
        }

        $isCollaborator = $project->collaborators()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();

        if (!$isCollaborator) {
            abort(403, 'Unauthorized');
        }

        return true;
    }
}
