<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{

    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('api-key');
        if (!$apiKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $project = Project::where('project_key', $apiKey)->with('apiBuilders')->first();
        if (!$project) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the current route name
        $routeName = $request->route()->getName();

        // Conditionally skip the specific route authorization check for the '/project/apis' route
        if ($routeName !== 'project.apis') {
            // Check if the requested route is in the project's apiBuilders
            $hasAccess = $project->apiBuilders->contains(function ($api) use ($routeName) {
                return $api->route_name === $routeName;
            });

            if (!$hasAccess) {
                return response()->json(['error' => 'Forbidden: You do not have access to this API'], 403);
            }
        }

        //store project and associated APIs in request for later use
        $request->attributes->set('project', $project);
        $request->attributes->set('apiBuilders', $project->apiBuilders);
        return $next($request);
    }
}
