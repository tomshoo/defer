<?php

use Illuminate\Container\Container;
use Tomshoo\Defer\DeferCallbackQueue;

if (! function_exists('defer')) {
    function defer(?callable $callback = null, bool $always = false, ?string $name = null)
    {
        /** @var DeferCallbackQueue $deferQueue */
        $deferQueue = Container::getInstance()->make(DeferCallbackQueue::class);

        if (is_null($callback)) {
            return $deferQueue;
        }

        return $deferQueue->register($callback, $always, $name);
    }
}
