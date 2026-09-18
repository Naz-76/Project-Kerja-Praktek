<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');

        if (! $key && $request->bearerToken()) {
            $key = $request->bearerToken();
        }

        if (! $key && $request->query('api_key')) {
            $key = $request->query('api_key');
        }

        if (! $key) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Header X-API-KEY atau Bearer token wajib disertakan.',
            ], 401);
        }

        $apiKey = ApiKey::where('key', $key)->where('is_active', true)->first();

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. API Key tidak valid atau telah dinonaktifkan.',
            ], 401);
        }

        // Update waktu terakhir digunakan
        $apiKey->update(['last_used_at' => now()]);

        $request->attributes->set('api_key_record', $apiKey);

        return $next($request);
    }
}
