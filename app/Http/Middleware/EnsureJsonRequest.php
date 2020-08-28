<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class EnsureJsonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->isJson()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid request',
                'data' => ['Use json Content-Type']
            ]);
        }

        $keys = $request->query->keys();
        array_walk($keys, function ($key) use ($request) {
            $request->query->remove($key);
        });

        $json = $request->json()->all();

        $keys = $request->request->keys();
        array_walk($keys, function ($key) use ($request) {
            $request->request->remove($key);
        });

        array_walk($json, function ($value, $key) use ($request) {
            $request->request->set($key, $value);
        });

        return $next($request);
    }
}
