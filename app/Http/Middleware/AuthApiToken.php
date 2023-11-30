<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthApiToken
{
    /**
     * Require auth token for API requests
     *
     * See 'app.auth_api_token' configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /* In reality should not be hardcoded and should be replaced to the process when user obtains the token */
        $authTokens = config('app.auth_api_token');

        $user = $password = '';

        // Parse the BasicAuth header, if any. e.g. "Authorization: Basic BASE64ENC(USER:PASSWORD)"
        //     https://datatracker.ietf.org/doc/html/rfc7617#section-2
        $authHeader = $request->header('Authorization', '');
        if (stripos($authHeader, 'basic ') === 0) {
            $limit = 2;
            list($user, $password) = explode(':', base64_decode(substr($authHeader, 6)), $limit);
        }

        if (!empty($user) && !empty($password) && !empty($authTokens[$user]) && $authTokens[$user] === $password) {
            return $next($request);
        }

        return response('', 401)
                ->header('WWW-Authenticate', 'Basic realm="API Access"');
    }
}
