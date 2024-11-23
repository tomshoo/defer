<?php

namespace Tomshoo\Defer;

use Ramsey\Uuid\Uuid;
use Tomshoo\Defer\Helpers\Callback;
use Tomshoo\Defer\Helpers\ConnectionCapture;

class DeferCallbackQueue
{
    /** @var array<string, Callback> */
    protected array $callbacks = [];

    public function register(callable $callback, bool $always = false, ?string $name = null)
    {
        return tap(
            new Callback($callback, $always, $name ?? Uuid::uuid4()->toString()),
            fn($callbackHandle) => $this->callbacks[$callbackHandle->name()] = $callbackHandle
        );
    }

    public function forget(string $name)
    {
        $callback = $this->callbacks[$name] ?? null;

        if (! is_null($callback)) {
            unset($this->callbacks[$name]);
        }

        return $callback;
    }

    public function invoke()
    {
        $this->invokeWhen(fn() => true);
    }

    public function invokeWhen(\Closure $filter)
    {
        $capture = new ConnectionCapture();

        foreach ($this->callbacks as $callback) {
            if (! $filter($callback)) {
                continue;
            }

            rescue(fn() => $callback->execute());
        }

        $capture->restore();
    }
}
