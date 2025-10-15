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

    protected $fillable = [
        'name',
        'type',
        'provider',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
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
}
