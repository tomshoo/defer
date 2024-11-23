<?php

namespace Tomshoo\Defer\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectionCapture
{
    protected string $connectionName;
    protected array $config;

    public function __construct()
    {
        $connection = DB::connection()->getDatabaseName();

        $this->connectionName = $connection;
        $this->config = Config::get("database.connections.{$connection}");
    }

    public function usingCaptured(callable $callback)
    {
        $capture = new static();

        $this->restore();

        try {
            $callback();
        } finally {
            $capture->restore();
        }
    }

    public function restore()
    {
        if (! Config::has("database.conenctions.{$this->connectionName}")) {
            Config::set("database.connections.{$this->connectionName}", $this->config);
        }

        if (DB::connection()->getDatabaseName() !== $this->connectionName) {
            DB::setDefaultConnection($this->connectionName);
        }
    }

    public function connectionName()
    {
        return $this->connectionName;
    }
}