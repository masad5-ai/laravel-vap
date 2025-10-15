<?php

namespace App\Services\Integration;

use App\Models\Integration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class IntegrationEngine
{
    /**
     * Execute every active integration for a given type.
     *
     * @return array<int, array<string, mixed>>
     */
    public function executeForType(string $type, string $action, array $payload = []): array
    {
        return Integration::query()
            ->active()
            ->forType($type)
            ->get()
            ->map(fn (Integration $integration) => $this->execute($integration, $action, $payload))
            ->all();
    }

    /**
     * Execute a payment integration and return the response details.
     *
     * @return array<string, mixed>
     */
    public function processPayment(Integration $integration, array $payload): array
    {
        return $this->execute($integration, 'payment.charge', $payload);
    }

    /**
     * Execute a single integration.
     *
     * @return array<string, mixed>
     */
    public function execute(Integration $integration, string $action, array $payload = []): array
    {
        $result = [
            'integration_id' => $integration->id,
            'name' => $integration->name,
            'type' => $integration->type,
            'driver' => $integration->driver,
            'action' => $action,
            'success' => true,
            'message' => null,
            'meta' => [],
        ];

        try {
            if ($integration->driver === Integration::DRIVER_CUSTOM_HTTP) {
                return $this->callCustomEndpoint($integration, $payload, $result);
            }

            $result['message'] = $this->simulateBuiltin($integration, $payload);
            $result['meta'] = ['payload_snapshot' => Arr::only($payload, ['order_reference', 'amount', 'currency', 'customer_email'])];
        } catch (Throwable $exception) {
            report($exception);
            $result['success'] = false;
            $result['message'] = $exception->getMessage();
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $result
     * @return array<string, mixed>
     */
    protected function callCustomEndpoint(Integration $integration, array $payload, array $result): array
    {
        $url = $integration->endpoint_url;
        $method = strtoupper($integration->endpoint_method ?: 'POST');

        if (! $url) {
            $result['success'] = false;
            $result['message'] = 'Custom endpoint missing URL.';
            return $result;
        }

        $headers = collect($integration->endpoint_headers ?? [])
            ->mapWithKeys(fn ($value, $key) => [$key => $this->interpolate((string) $value, $payload)])
            ->all();

        $format = data_get($integration->settings, 'payload_format', 'json');
        $template = (string) ($integration->endpoint_payload_template ?? '');
        $rendered = $template !== '' ? $this->interpolate($template, $payload) : null;
        $body = $this->buildBody($rendered, $payload, $format);

        $request = Http::timeout(15);

        if (! empty($headers)) {
            $request = $request->withHeaders($headers);
        }

        try {
            $options = $this->buildRequestOptions($method, $format, $body);
            $response = $request->send($method, $url, $options);
        } catch (Throwable $exception) {
            Log::warning('Custom integration failed', [
                'integration_id' => $integration->id,
                'message' => $exception->getMessage(),
            ]);

            $result['success'] = false;
            $result['message'] = $exception->getMessage();
            return $result;
        }

        $result['success'] = $response->successful();
        $result['message'] = $response->successful() ? 'Endpoint accepted payload.' : 'Endpoint returned '.$response->status();
        $result['meta'] = [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $headers,
            'payload' => $body,
        ];

        return $result;
    }

    /**
     * @param array<string, mixed> $payload
     */
    protected function simulateBuiltin(Integration $integration, array $payload): string
    {
        $provider = $integration->provider ?: Str::title($integration->type.' integration');
        Log::info('Simulated integration executed.', [
            'integration_id' => $integration->id,
            'provider' => $provider,
            'type' => $integration->type,
        ]);

        return $provider.' handled the request.';
    }

    protected function interpolate(string $template, array $payload): string
    {
        return preg_replace_callback('/{{\s*(.+?)\s*}}/', function (array $matches) use ($payload) {
            $key = $matches[1];
            $value = data_get($payload, $key);
            return is_scalar($value) ? (string) $value : json_encode($value);
        }, $template) ?? $template;
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildBody(?string $rendered, array $payload, string $format): array
    {
        if ($rendered === null || trim($rendered) === '') {
            return $payload;
        }

        if ($format === 'form') {
            parse_str($rendered, $form);
            if (is_array($form) && ! empty($form)) {
                return $form;
            }
        }

        $decoded = json_decode($rendered, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return ['raw' => $rendered];
    }

    /**
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     */
    protected function buildRequestOptions(string $method, string $format, array $body): array
    {
        if ($method === 'GET') {
            return ['query' => $body];
        }

        if ($format === 'form') {
            return ['form_params' => $body];
        }

        return ['json' => $body];
    }
}
