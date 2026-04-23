<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Authorize project access for basic project-based actions.
     */
    protected function authorize($ability, $arguments = [])
    {
        $user = request()->user();
        $project = null;

        if ($arguments instanceof Project) {
            $project = $arguments;
        } elseif (is_array($arguments) && count($arguments) > 0 && $arguments[0] instanceof Project) {
            $project = $arguments[0];
        }

        if ($project instanceof Project) {
            if ($project->developer_id === $user?->id) {
                return true;
            }

            $isCollaborator = $project->collaborators()
                ->where('user_id', $user?->id)
                ->where('status', 'accepted')
                ->exists();

            if ($isCollaborator) {
                return true;
            }
        }

        abort(403, 'Unauthorized');
    }

    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api/*');
    }

    protected function respond(Request $request, array $data, int $status = 200, array $headers = [])
    {
        if ($this->isApiRequest($request)) {
            return response()->json($data, $status, $headers);
        }

        return response($data, $status, $headers);
    }
}
