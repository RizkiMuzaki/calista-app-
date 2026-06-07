<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class BridgeController extends Controller
{
    /**
     * Bridge mobile app to web session using Sanctum token.
     */
    public function loginWithToken(Request $request)
    {
        $token = $request->query('token');
        $redirect = $request->query('redirect', '/halamanawal');

        if (!$token) {
            return response('Token is required', 400);
        }

        // Find token
        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken) {
            $user = $accessToken->tokenable;
            
            // Log user into web session
            Auth::login($user);
            
            // 📱 Tandai bahwa user sedang dalam "Mode Mobile"
            $request->session()->put('is_mobile_webview', true);

            // Regenerate session for security
            $request->session()->regenerate();

            return redirect($redirect);
        }

        return response('Unauthorized: Invalid or expired token', 401);
    }
}
