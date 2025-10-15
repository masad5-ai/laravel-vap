<?php

namespace Tests\Unit;

use App\Models\Integration;
use App\Services\Integration\IntegrationEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IntegrationEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_payment_invokes_custom_endpoint(): void
    {
        Http::fake([
            'https://hooks.example.com/*' => Http::response(['status' => 'ok'], 200),
        ]);

        $integration = Integration::create([
            'name' => 'Custom Hook',
            'type' => Integration::TYPE_PAYMENT,
            'driver' => Integration::DRIVER_CUSTOM_HTTP,
            'provider' => 'Shared',
            'settings' => ['payload_format' => 'json'],
            'endpoint_url' => 'https://hooks.example.com/pay',
            'endpoint_method' => 'POST',
            'endpoint_headers' => ['X-Token' => '{{ customer_email }}'],
            'endpoint_payload_template' => '{"order":"{{ order_reference }}","amount":"{{ amount }}"}',
        ]);

        $engine = new IntegrationEngine();

        $result = $engine->processPayment($integration, [
            'order_reference' => 'CHK-123',
            'amount' => 49.99,
            'currency' => 'AUD',
            'customer_email' => 'customer@example.com',
        ]);

        Http::assertSent(function ($request) {
            $payload = $request->data();

            return $request->url() === 'https://hooks.example.com/pay'
                && $request->method() === 'POST'
                && $request->hasHeader('X-Token', 'customer@example.com')
                && data_get($payload, 'order') === 'CHK-123';
        });

        $this->assertTrue($result['success']);
        $this->assertSame('Endpoint accepted payload.', $result['message']);
    }
}
