<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    /**
     * Get all feedbacks for a project
     */
    public function index(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($request->user(), $project);

        $feedbacks = $project->feedbacks()
            ->with('user')
            ->latest()
            ->paginate(20);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $feedbacks,
            ]);
        }

        return view('feedbacks.index', ['project' => $project, 'feedbacks' => $feedbacks]);
    }

    /**
     * Create feedback
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:1|max:5',
            'type' => 'sometimes|in:general,task_specific,payment,bug_report',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('feedbacks', 'public');
        }

        $feedback = Feedback::create([
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'rating' => $validated['rating'] ?? null,
            'type' => $validated['type'] ?? 'general',
            'attachment' => $attachmentPath,
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'data' => $feedback,
            ], Response::HTTP_CREATED);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Feedback submitted successfully');
    }

    /**
     * Show the form for creating feedback
     */
    public function create(Request $request, Project $project)
    {
        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('feedbacks.create', ['project' => $project]);
    }

    /**
     * Get a specific feedback
     */
    public function show(Request $request, Project $project, Feedback $feedback)
    {
        if ($feedback->project_id != $project->id) {
            abort(404);
        }

        $this->authorizeProjectAccess($request->user(), $project);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $feedback->load('user'),
            ]);
        }

        return view('feedbacks.show', ['project' => $project, 'feedback' => $feedback]);
    }

    /**
     * Update feedback (only by creator or project owner)
     */
    public function update(Request $request, Project $project, Feedback $feedback)
    {
        if ($feedback->project_id != $project->id) {
            abort(404);
        }

        if ($feedback->user_id != $request->user()->id && $project->developer_id != $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'message' => 'sometimes|string|min:10',
            'rating' => 'nullable|integer|min:1|max:5',
            'resolved' => 'sometimes|boolean',
        ]);

        $feedback->update($validated);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback updated successfully',
                'data' => $feedback,
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Feedback updated successfully');
    }

    /**
     * Delete feedback
     */
    public function destroy(Request $request, Project $project, Feedback $feedback)
    {
        if ($feedback->project_id != $project->id) {
            abort(404);
        }

        if ($feedback->user_id != $request->user()->id && $project->developer_id != $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $feedback->delete();

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback deleted successfully',
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Feedback deleted successfully');
    }

    /**
     * Mark feedback as resolved
     */
    public function markAsResolved(Request $request, Project $project, Feedback $feedback)
    {
        if ($feedback->project_id != $project->id) {
            abort(404);
        }

        $this->authorize('update', $project);

        $feedback->markAsResolved();

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback marked as resolved',
                'data' => $feedback,
            ]);
        }

        return back()->with('success', 'Feedback marked as resolved');
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
