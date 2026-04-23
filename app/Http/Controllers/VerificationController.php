<?php

namespace App\Http\Controllers;

use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    /**
     * Submit verification documents
     */
    public function submitDocument(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'document' => 'required|file|max:5120',
        ]);

        $documentPath = $request->file('document')->store('verification-documents', 'public');

        $verification = VerificationDocument::create([
            'user_id' => $request->user()->id,
            'document_type' => $validated['document_type'],
            'document_url' => $documentPath,
            'status' => 'pending',
        ]);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Verification document submitted successfully',
                'data' => $verification,
            ], Response::HTTP_CREATED);
        }

        return redirect()->back()->with('success', 'Verification document submitted successfully');
    }

    /**
     * Show the form for submitting verification document
     */
    public function create(Request $request)
    {
        if ($request->is('api/*')) {
            return response()->json(['error' => 'Not applicable for API'], 405);
        }

        return view('verification.create');
    }

    /**
     * Get verification documents for authenticated user
     */
    public function myDocuments(Request $request)
    {
        $documents = VerificationDocument::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $documents,
            ]);
        }

        return view('verification.documents', ['documents' => $documents]);
    }

    /**
     * Get verification status
     */
    public function getStatus(Request $request)
    {
        $user = $request->user();
        $documents = $user->verificationDocuments;
        $approvedCount = $documents->where('status', 'approved')->count();

        $data = [
            'is_verified' => $user->is_verified,
            'verified_at' => $user->verified_at,
            'total_documents' => $documents->count(),
            'approved_documents' => $approvedCount,
            'pending_documents' => $documents->where('status', 'pending')->count(),
            'documents' => $documents,
        ];

        if ($request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return view('verification.status', $data);
    }
}
