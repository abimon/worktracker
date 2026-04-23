<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Get all tasks for a project
     */
    public function index(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($request->user(), $project);

        $tasks = $project->tasks()
            ->orderBy('order')
            ->paginate(20);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $tasks,
            ]);
        }

        return view('tasks.index', ['project' => $project, 'tasks' => $tasks]);
    }

    /**
     * Create a new task
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $task = Task::create([
            ...$validated,
            'project_id' => $project->id,
            'order' => $project->tasks()->max('order') + 1 ?? 0,
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task,
            ], Response::HTTP_CREATED);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Task created successfully');
    }

    /**
     * Show the form for creating a new task
     */
    public function create(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('tasks.create', ['project' => $project]);
    }

    /**
     * Get a specific task
     */
    public function show(Request $request, Project $project, Task $task)
    {
        $this->authorize('view', $project);

        if ($task->project_id !== $project->id) {
            abort(404);
        }

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $task,
            ]);
        }

        return view('tasks.show', ['project' => $project, 'task' => $task]);
    }

    /**
     * Show the form for editing a task
     */
    public function edit(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $project);

        if ($task->project_id !== $project->id) {
            abort(404);
        }

        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('tasks.edit', ['project' => $project, 'task' => $task]);
    }

    /**
     * Update a task
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $project);

        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:todo,in_progress,review,completed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'completion_percentage' => 'sometimes|integer|min:0|max:100',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $task->update($validated);

        // Update project progress
        $this->updateProjectProgress($project);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $task,
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Task updated successfully');
    }

    /**
     * Delete a task
     */
    public function destroy(Request $request, Project $project, Task $task)
    {
        $this->authorize('delete', $project);

        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $task->delete();

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Task deleted successfully');
    }

    /**
     * Reorder tasks
     */
    public function reorder(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer',
        ]);

        foreach ($validated['task_ids'] as $index => $taskId) {
            Task::where('id', $taskId)
                ->where('project_id', $project->id)
                ->update(['order' => $index]);
        }

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Tasks reordered successfully',
            ]);
        }

        return back()->with('success', 'Tasks reordered successfully');
    }

    /**
     * Update project progress based on tasks
     */
    private function updateProjectProgress(Project $project)
    {
        $tasks = $project->tasks;
        if ($tasks->isEmpty()) {
            return;
        }

        $avgProgress = $tasks->avg('completion_percentage');
        $project->progress_percentage = (int) $avgProgress;

        if ($avgProgress == 100) {
            $project->progress = 'completed';
        } elseif ($avgProgress > 0) {
            $project->progress = 'in_progress';
        }

        $project->save();
    }

    /**
     * Helper method to check if user can access project
     */
    private function authorizeProjectAccess($user, $project)
    {
        if ($project->developer_id === $user->id) {
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
