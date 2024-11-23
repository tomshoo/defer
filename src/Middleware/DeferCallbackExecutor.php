<?php

namespace Tomshoo\Defer\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tomshoo\Defer\DeferCallbackQueue;

class DeferCallbackExecutor
{
    public function handle(Request $request, \Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        Container::getInstance()
            ->make(DeferCallbackQueue::class)
            ->invokeWhen(fn($handle) => $handle->always || $response->getStatusCode() < 400);
    }
}
