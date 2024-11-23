<?php

namespace Tomshoo\Defer\Helpers;

class Callback
{
    protected $callback;
    protected string $name;

    public bool $always;

    protected bool $executed = false;

    protected ConnectionCapture $capture;

    public function __construct(callable $callback, bool $always, string $name)
    {
        $this->callback = $callback;
        $this->always = $always;

        $this->name = $name;
        $this->capture = new ConnectionCapture();
    }

    public function execute()
    {
        if ($this->executed) {
            return;
        }

        try {
            $this->capture->usingCaptured($this->callback);
        } finally {
            $this->executed = true;
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function isExecuted()
    {
        return $this->executed;
    }

    public function connectionName()
    {
        return $this->capture->connectionName();
    }
}
