<?php

namespace Panservice\FilamentUsers\Traits;

use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

trait HasUserAuthenticationLog
{
    use AuthenticationLoggable;

    public function getLastLoginAtAttribute(): ?string
    {
        return $this->lastLoginAt();
    }
}
