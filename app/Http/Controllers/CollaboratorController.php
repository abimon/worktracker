<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCollaborator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CollaboratorController extends Controller
{
    /**
     * Get all collaborators for a project
     */
    public function index()
    {
        // $this->authorize('view', $project);

        $collaborators = ProjectCollaborator::where('user_id', Auth::user()->id)->paginate(15);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $collaborators,
            ]);
        }

        return view('collaborators.index', [ 'collaborators' => $collaborators]);
    }

    public function create( Project $project)
    {
        $this->authorize('create', $project);
        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $project,
            ]);
        }

        return view('collaborators.create', compact('project'));
    }
    /**
     * Invite a collaborator
     */
    public function store( Project $project)
    {
        $this->authorize('update', $project);
        $validated =request()->validate([
            'email' => 'required|email|exists:users,email|different:' .request()->user()->email,
            'role' => 'required|in:viewer,contributor,manager',
        ]);
        
        $user = User::where('email', request('email'))->first();
        $validated['user_id'] = $user->id;
        $existing = ProjectCollaborator::where('project_id', $project->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existing && $existing->status == 'accepted') {
            if (request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already a collaborator',
                ], 409);
            }
            return back()->withErrors(['user_id' => 'User is already a collaborator']);
        }

        $collaborator = ProjectCollaborator::updateOrCreate(
            [
                'project_id' => $project->id,
                'user_id' => $validated['user_id'],
            ],
            [
                'role' => $validated['role'],
                'status' => 'pending',
            ]
        );

        $token = $collaborator->generateInviteToken();
        Mail::send('mails.collaboration', ['user' => $user, 'accept_link' => route('collaborations.accept', $token),'decline_link'=> route('collaborations.decline', $token) ,'sender' =>request()->user()->name], function ($message) use ($user, $collaborator) {
            $message->to($user->email, $user->name)->subject($collaborator->project->name . ' Collaboration Invite');
        });

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully',
                'data' => $collaborator,
                'invite_link' => route('collaborations.accept', $collaborator->invite_token),
            ], 201);
        }

        return redirect()->back()->with('success', 'Invitation sent successfully');
    }

    /**
     * Accept collaboration invitation
     */
    public function acceptInvitation( $token)
    {
        $collaborator = ProjectCollaborator::where('invite_token', $token)->firstOrFail();

        if ($collaborator->status == 'accepted') {
            if (request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invitation already accepted',
                ], 409);
            }
            return redirect()->route('dashboard')->with('info', 'Invitation already accepted');
        }

        $collaborator->accept();

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation accepted successfully',
                'data' => $collaborator,
            ]);
        }

        return redirect()->route('projects.show', $collaborator->project_id)->with('success', 'Invitation accepted successfully');
    }

    /**
     * Decline collaboration invitation
     */
    public function declineInvitation( $token)
    {
        $collaborator = ProjectCollaborator::where('invite_token', $token)->firstOrFail();

        $collaborator->decline();

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation declined',
                'data' => $collaborator,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Invitation declined');
    }

    /**
     * Update collaborator role
     */
    public function update( Project $project, $collaboratorId)
    {
        $this->authorize('update', $project);

        $validated =request()->validate([
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        $collaborator = ProjectCollaborator::where('project_id', $project->id)
            ->where('user_id', $collaboratorId)
            ->firstOrFail();

        $collaborator->update($validated);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Collaborator role updated',
                'data' => $collaborator,
            ]);
        }

        return back()->with('success', 'Collaborator role updated');
    }

    /**
     * Remove a collaborator
     */
    public function destroy( Project $project, $collaboratorId)
    {
        $this->authorize('update', $project);

        $collaborator = ProjectCollaborator::where('project_id', $project->id)
            ->where('user_id', $collaboratorId)
            ->firstOrFail();

        $collaborator->delete();

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Collaborator removed successfully',
            ]);
        }

        return back()->with('success', 'Collaborator removed successfully');
    }

    /**
     * Get pending invitations for the authenticated user
     */
    public function pendingInvitations()
    {
        $invitations = ProjectCollaborator::where('user_id',request()->user()->id)
            ->where('status', 'pending')
            ->with('project')
            ->latest()
            ->paginate(15);

        if (request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $invitations,
            ]);
        }

        return view('collaborators.pending', ['invitations' => $invitations]);
    }

}
