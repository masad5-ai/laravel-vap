<?php

namespace Tests\Feature\Admin;

use App\Models\Integration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_store_custom_http_integration(): void
    {
        $user = $this->makeSuperAdmin();

        $response = $this->actingAs($user)->post(route('admin.integrations.store'), [
            'name' => 'Webhook Processor',
            'type' => Integration::TYPE_PAYMENT,
            'driver' => Integration::DRIVER_CUSTOM_HTTP,
            'provider' => 'Shared Hosting',
            'is_active' => 1,
            'endpoint_url' => 'https://example.com/hook.php',
            'endpoint_method' => 'POST',
            'endpoint_payload_template' => '{"order":"{{ order_reference }}"}',
            'endpoint_payload_format' => 'json',
            'endpoint_headers' => [
                ['key' => 'X-Secret', 'value' => 'abc123'],
            ],
            'settings' => [
                ['key' => 'payload_format', 'value' => 'json'],
            ],
        ]);

        $response->assertRedirect(route('admin.integrations.index'));

        $this->assertDatabaseHas('integrations', [
            'name' => 'Webhook Processor',
            'driver' => Integration::DRIVER_CUSTOM_HTTP,
            'endpoint_url' => 'https://example.com/hook.php',
        ]);
    }

    public function test_custom_http_driver_requires_endpoint(): void
    {
        $user = $this->makeSuperAdmin();

        $response = $this->actingAs($user)
            ->from(route('admin.integrations.create'))
            ->post(route('admin.integrations.store'), [
                'name' => 'Invalid Custom',
                'type' => Integration::TYPE_PAYMENT,
                'driver' => Integration::DRIVER_CUSTOM_HTTP,
                'endpoint_method' => 'POST',
            ]);

        $response->assertRedirect(route('admin.integrations.create'));
        $response->assertSessionHasErrors(['endpoint_url']);
    }

    protected function makeSuperAdmin(): User
    {
        $role = Role::firstOrCreate([
            'slug' => 'super-admin',
        ], [
            'name' => 'Super Admin',
            'description' => 'Full access for testing',
            'is_default' => false,
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
