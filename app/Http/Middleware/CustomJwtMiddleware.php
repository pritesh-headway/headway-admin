<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class CustomJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            // ✅ Step 1: Inject custom token header into standard Authorization
            if ($request->hasHeader('token')) {
                $token = $request->header('token');
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }

            // ✅ Step 2: Retrieve the token
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['status' => false, 'message' => 'Token not provided'], 401);
            }

            // ✅ Step 3: Extract user ID from token
            $payload = JWTAuth::setToken($token)->getPayload();
            $userId = $payload->get('sub');

            // ✅ Step 4: Manually load the user (to avoid model mismatch)
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 401);
            }

            // ✅ Step 5: Set authenticated user for current request
            auth()->setUser($user);

        } catch (TokenExpiredException $e) {
            return response()->json(['status' => false, 'message' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => false, 'message' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token error: ' . $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Authorization error', 'error' => $e->getMessage()], 500);
        }

        return $next($request);
    }


}
