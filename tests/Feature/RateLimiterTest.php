<?php

namespace Tests\Feature;

use Illuminate\Cache\RateLimiter as RateLimiterManager;
use Tests\TestCase;

class RateLimiterTest extends TestCase
{
    public function test_public_rate_limiter_is_registered(): void
    {
        $this->assertNotNull(app(RateLimiterManager::class)->limiter('public'));
    }
}
