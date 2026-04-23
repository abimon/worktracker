<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    /**
     * Get all payments for a project
     */
    public function index(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($request->user(), $project);

        $payments = $project->payments()
            ->with('payer')
            ->latest()
            ->paginate(20);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $payments,
                'summary' => [
                    'total_budget' => $project->budget,
                    'total_paid' => $project->payments()->where('status', 'completed')->sum('amount'),
                    'pending_amount' => $project->payments()->where('status', 'pending')->sum('amount'),
                    'remaining_balance' => $project->budget - $project->payments()->where('status', 'completed')->sum('amount'),
                ],
            ]);
        }

        return view('payments.index', ['project' => $project, 'payments' => $payments]);
    }

    /**
     * Record a payment
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:mpesa,card,bank_transfer,manual,other',
            'payer_id' => 'nullable|exists:users,id',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $payment = Payment::create([
            ...$validated,
            'project_id' => $project->id,
            'status' => 'pending',
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => $payment,
            ], Response::HTTP_CREATED);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Payment recorded successfully');
    }

    /**
     * Show the form for creating a new payment
     */
    public function create(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('payments.create', ['project' => $project]);
    }

    /**
     * Get a specific payment
     */
    public function show(Request $request, Project $project, Payment $payment)
    {
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $this->authorizeProjectAccess($request->user(), $project);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $payment->load('payer'),
            ]);
        }

        return view('payments.show', ['project' => $project, 'payment' => $payment]);
    }

    /**
     * Show the form for editing a payment
     */
    public function edit(Request $request, Project $project, Payment $payment)
    {
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $this->authorize('update', $project);

        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('payments.edit', ['project' => $project, 'payment' => $payment]);
    }

    /**
     * Update a payment
     */
    public function update(Request $request, Project $project, Payment $payment)
    {
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $this->authorize('update', $project);

        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0.01',
            'payment_method' => 'sometimes|in:mpesa,card,bank_transfer,manual,other',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $payment->update($validated);

        // Update project paid amount if status changed to completed
        if (isset($validated['status']) && $validated['status'] === 'completed') {
            $this->updateProjectPaidAmount($project);
        }

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payment,
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Payment updated successfully');
    }

    /**
     * Delete a payment
     */
    public function destroy(Request $request, Project $project, Payment $payment)
    {
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $this->authorize('delete', $project);

        $payment->delete();

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully',
            ]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Payment deleted successfully');
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(Request $request, Project $project, Payment $payment)
    {
        if ($payment->project_id !== $project->id) {
            abort(404);
        }

        $this->authorize('update', $project);

        $payment->markAsCompleted();

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Payment marked as completed',
                'data' => $payment,
            ]);
        }

        return back()->with('success', 'Payment marked as completed');
    }

    /**
     * Update project paid amount
     */
    private function updateProjectPaidAmount(Project $project)
    {
        $totalPaid = $project->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $project->update(['paid_amount' => $totalPaid]);
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
