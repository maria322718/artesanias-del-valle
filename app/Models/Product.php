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

    /**
     * Galería de hasta 3 imágenes reales y representativas por pieza artesanal.
     *
     * @return array<int, string>
     */
    public function getGalleryImagesAttribute(): array
    {
        $galleries = [
            'sombrero-vueltiao-21-vueltas' => [
                '/images/artesanias/sombrero-vueltiao-21.jpg',
            ],
            'mochila-wayuu-susu-una-hebra' => [
                '/images/artesanias/mochila-wayuu-una-hebra.jpg',
            ],
            'vajilla-ceramica-negra-raquira' => [
                'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=800&q=80',
            ],
            'chiva-barro-pitalito' => [
                '/images/artesanias/chiva-mediana-artesanal.png',
            ],
            'plato-barniz-de-pasto-mopa-mopa' => [
                '/images/artesanias/plato-barniz-de-pasto-1.jpg',
                '/images/artesanias/plato-barniz-de-pasto-2.jpg',
                '/images/artesanias/plato-barniz-de-pasto-3.jpg',
            ],
            'hamaca-sanjacintera-telar' => [
                '/images/artesanias/hamaca-san-jacinto.jpg',
            ],
        ];

        if (isset($galleries[$this->slug])) {
            return $galleries[$this->slug];
        }

        return !empty($this->image_url) ? [$this->image_url] : [];
    }
}
