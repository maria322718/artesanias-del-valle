<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Dominio: Pedido de Artesanías.
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_type',
        'shipping_cost',
        'subtotal',
        'additional_costs',
        'total_amount',
        'applied_decorators',
        'payment_method',
        'payment_reference',
        'invoice_type',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'additional_costs' => 'float',
        'total_amount' => 'float',
        'applied_decorators' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_amount, 0, ',', '.') . ' COP';
    }
}
