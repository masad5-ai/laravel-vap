<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Integration;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'Manage Products', 'slug' => 'manage-products', 'description' => 'Create, update, archive, and publish products.'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories', 'description' => 'Organise catalog categories and merchandising.'],
            ['name' => 'Manage Coupons', 'slug' => 'manage-coupons', 'description' => 'Create and schedule coupon campaigns.'],
            ['name' => 'Manage Orders', 'slug' => 'manage-orders', 'description' => 'View and progress customer orders.'],
            ['name' => 'Manage Customers', 'slug' => 'manage-customers', 'description' => 'Edit customer profiles and service requests.'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'description' => 'Access dashboard metrics and sales reports.'],
            ['name' => 'Manage Integrations', 'slug' => 'manage-integrations', 'description' => 'Configure payment, email, SMS, and WhatsApp gateways.'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Create roles and assign permissions.'],
        ])->map(fn (array $data) => Permission::updateOrCreate(['slug' => $data['slug']], $data));

        $allPermissionSlugs = $permissions->pluck('slug')->all();

        $roles = [
            'super-admin' => [
                'name' => 'Super Administrator',
                'description' => 'Full access to every configuration and tool.',
                'permissions' => $allPermissionSlugs,
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Manages daily operations and integrations.',
                'permissions' => [
                    'manage-products',
                    'manage-categories',
                    'manage-coupons',
                    'manage-orders',
                    'manage-customers',
                    'view-reports',
                    'manage-integrations',
                ],
            ],
            'manager' => [
                'name' => 'Operations Manager',
                'description' => 'Handles fulfilment, customer service, and reporting.',
                'permissions' => [
                    'manage-orders',
                    'manage-customers',
                    'view-reports',
                ],
            ],
            'product-manager' => [
                'name' => 'Product Manager',
                'description' => 'Curates catalog content and promotional offers.',
                'permissions' => [
                    'manage-products',
                    'manage-categories',
                    'manage-coupons',
                ],
            ],
            'customer' => [
                'name' => 'Customer',
                'description' => 'Default shopper role.',
                'permissions' => [],
                'is_default' => true,
            ],
        ];

        foreach ($roles as $slug => $roleData) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'] ?? null,
                    'is_default' => $roleData['is_default'] ?? false,
                ],
            );

            $rolePermissions = $roleData['permissions'] ?? [];

            if (empty(array_diff($allPermissionSlugs, $rolePermissions))) {
                $role->permissions()->sync($permissions->pluck('id'));
                continue;
            }

            $permissionIds = $permissions
                ->whereIn('slug', $rolePermissions)
                ->pluck('id');

            $role->permissions()->sync($permissionIds);
        }

        $admin = User::factory()
            ->create([
                'name' => 'Store Administrator',
                'email' => 'admin@vaperoo.test',
                'password' => Hash::make('ChangeMe123!'),
            ]);

        $admin->assignRole('super-admin');

        $customers = User::factory(8)->create();

        $integrations = [
            [
                'name' => 'Manual Bank Transfer',
                'type' => Integration::TYPE_PAYMENT,
                'provider' => 'Bank Transfer',
                'settings' => ['account_name' => 'Vape Supplies Pty Ltd', 'bsb' => '123-456', 'account_number' => '78901234'],
            ],
            [
                'name' => 'Stripe Payments',
                'type' => Integration::TYPE_PAYMENT,
                'provider' => 'Stripe',
                'settings' => ['publishable_key' => 'pk_test_xxxx', 'secret_key' => 'sk_test_xxxx'],
            ],
            [
                'name' => 'Postmark Email',
                'type' => Integration::TYPE_EMAIL,
                'provider' => 'Postmark',
                'settings' => ['server_token' => 'postmark-server-token', 'from_email' => 'orders@vaperoo.test'],
            ],
            [
                'name' => 'Twilio SMS',
                'type' => Integration::TYPE_SMS,
                'provider' => 'Twilio',
                'settings' => ['account_sid' => 'ACxxxx', 'auth_token' => 'xxxx', 'from_number' => '+61234567890'],
            ],
            [
                'name' => 'WhatsApp Cloud API',
                'type' => Integration::TYPE_WHATSAPP,
                'provider' => 'Meta',
                'settings' => ['phone_number_id' => '1234567890', 'access_token' => 'whatsapp-token'],
            ],
        ];

        foreach ($integrations as $integration) {
            Integration::updateOrCreate(
                ['name' => $integration['name'], 'type' => $integration['type']],
                $integration,
            );
        }

        $categories = collect([
            'Disposables',
            'Nic Salts',
            'Freebase Liquids',
            'Pods & Coils',
            'Accessories',
        ])->map(function (string $name) {
            return Category::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        });

        $categories->each(function (Category $category) {
            Product::factory()
                ->count(rand(8, 14))
                ->for($category)
                ->create();
        });

        Coupon::create([
            'code' => 'WELCOME10',
            'name' => 'New Customer 10% Off',
            'description' => 'Save 10% on your first order.',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount_value' => 35,
            'max_redemptions' => 500,
            'minimum_cart_total' => 60,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonths(6),
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'name' => 'Free Express Shipping',
            'description' => 'Complimentary express shipping on orders over $120.',
            'discount_type' => 'fixed',
            'discount_value' => 15,
            'max_discount_value' => 15,
            'minimum_cart_total' => 120,
            'is_stackable' => true,
            'starts_at' => now()->subDays(5),
            'expires_at' => now()->addMonths(3),
        ]);

        $products = Product::all();

        foreach (range(1, 6) as $i) {
            $customer = $customers->random();
            $items = $products->random(rand(2, 4));

            $orderTotals = $items->reduce(function (array $carry, Product $product) {
                $quantity = rand(1, 3);
                $lineTotal = $product->price * $quantity;

                $carry['items'][] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];

                $carry['subtotal'] += $lineTotal;

                return $carry;
            }, ['items' => [], 'subtotal' => 0.0]);

            $tax = round($orderTotals['subtotal'] * 0.1, 2);
            $shipping = $orderTotals['subtotal'] > 120 ? 0 : 12.95;
            $grandTotal = $orderTotals['subtotal'] + $tax + $shipping;

            $order = Order::create([
                'user_id' => $customer->id,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'customer_name' => $customer->name,
                'status' => Arr::random(['processing', 'shipped', 'completed']),
                'payment_status' => Arr::random(['paid', 'refunded', 'paid']),
                'payment_method' => 'manual',
                'currency' => 'AUD',
                'subtotal' => $orderTotals['subtotal'],
                'discount_total' => 0,
                'tax_total' => $tax,
                'shipping_total' => $shipping,
                'grand_total' => $grandTotal,
                'billing_address' => $customer->default_billing_address,
                'shipping_address' => $customer->default_shipping_address,
                'placed_at' => now()->subDays(rand(1, 30)),
                'paid_at' => now()->subDays(rand(1, 30)),
            ]);

            foreach ($orderTotals['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'sku' => $item['product']->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['product']->price,
                    'discount_total' => 0,
                    'line_total' => $item['line_total'],
                ]);
            }
        }
    }
}
