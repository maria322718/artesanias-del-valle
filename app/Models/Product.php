<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Dominio: Artesanía Tradicional Colombiana.
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'artisan_name',
        'origin_region',
        'technique',
        'price',
        'stock',
        'image_url',
        'is_fragile',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_fragile' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 0, ',', '.') . ' COP';
    }
}
