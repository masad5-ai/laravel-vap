<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    public const TYPE_PAYMENT = 'payment';
    public const TYPE_EMAIL = 'email';
    public const TYPE_SMS = 'sms';
    public const TYPE_WHATSAPP = 'whatsapp';

    public const DRIVER_BUILTIN = 'builtin';
    public const DRIVER_CUSTOM_HTTP = 'custom-http';

    protected $fillable = [
        'name',
        'type',
        'driver',
        'provider',
        'settings',
        'is_active',
        'endpoint_url',
        'endpoint_method',
        'endpoint_headers',
        'endpoint_payload_template',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'endpoint_headers' => 'array',
    ];

    public static function types(): array
    {
        return [
            self::TYPE_PAYMENT,
            self::TYPE_EMAIL,
            self::TYPE_SMS,
            self::TYPE_WHATSAPP,
        ];
    }

    public static function drivers(): array
    {
        return [
            self::DRIVER_BUILTIN,
            self::DRIVER_CUSTOM_HTTP,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function requiresEndpointConfiguration(): bool
    {
        return $this->driver === self::DRIVER_CUSTOM_HTTP;
    }
}
