<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

//Dev Function, deleted when development done
function base64UrlDecode($input) {
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $addlen = 4 - $remainder;
        $input .= str_repeat('=', $addlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
}

function decodeJwtWithoutVerification($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        throw new \InvalidArgumentException('Invalid JWT token structure.');
    }

    // Decode header and payload
    $header = json_decode(base64UrlDecode($parts[0]), true);
    $payload = json_decode(base64UrlDecode($parts[1]), true);

    return [
        'header' => $header,
        'payload' => $payload
    ];
}
// End of Dev Function

class JwtAuthMiddleware
{
    protected $publicKey;

    public function __construct()
    {
        // Load your public key from the file (ensure this path is correct)
        $this->publicKey = file_get_contents(storage_path('app/keys/public.pem'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the token from the Authorization header
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['error' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $matches[1];

        try {
            // Decode the JWT token using the public key
            $decoded = JWT::decode($token, new Key($this->publicKey, 'RS256'));

            //Dev only
            // $decoded = decodeJwtWithoutVerification($token)['payload'];
            // $user = User::with(['profil'])->find($decoded['id']);

            $user = User::with(['profil'])->find($decoded->id);
            if (!$user) {
                return response()->json(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
            }

            // Set the user in Auth
            Auth::login($user); // Log the user in

            return $next($request);

        } catch (\Exception $e) {
            // If token is invalid or expired, return an unauthorized response
            return response()->json(['error' => 'Invalid or expired token', 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }

        // Allow the request to proceed to the next middleware/controller

        // return $user;
    }
}
