<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function apiPost(string $uri, array $payload = [], string $lang = 'en', $actingUser = null)
    {
        $headers = ['Lang' => $lang, 'Accept' => 'application/json'];
        if ($actingUser) {
            \Laravel\Sanctum\Sanctum::actingAs($actingUser);
        }
        return $this->postJson($uri, $payload, $headers);
    }

    protected function apiGet(string $uri, array $params = [], string $lang = 'en', $actingUser = null)
    {
        $headers = ['Lang' => $lang, 'Accept' => 'application/json'];
        if ($actingUser) {
            \Laravel\Sanctum\Sanctum::actingAs($actingUser);
        }
        return $this->getJson($uri, $headers);
    }

    protected function apiPatch(string $uri, array $payload = [], string $lang = 'en', $actingUser = null)
    {
        $headers = ['Lang' => $lang, 'Accept' => 'application/json'];
        if ($actingUser) {
            \Laravel\Sanctum\Sanctum::actingAs($actingUser);
        }
        return $this->patchJson($uri, $payload, $headers);
    }

    protected function apiDelete(string $uri, string $lang = 'en', $actingUser = null)
    {
        $headers = ['Lang' => $lang, 'Accept' => 'application/json'];
        if ($actingUser) {
            \Laravel\Sanctum\Sanctum::actingAs($actingUser);
        }
        return $this->deleteJson($uri, [], $headers);
    }
}
