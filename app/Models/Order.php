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
        'nit',
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

    /**
     * Calcula el dígito de verificación oficial (Módulo 11) de Colombia para NITs.
     */
    public static function calculateNitVerificationDigit(string $baseNit): int
    {
        $weights = [41, 37, 29, 23, 19, 17, 13, 7, 3];
        $cleanBase = preg_replace('/\D/', '', $baseNit) ?? '';
        $digits = str_split(str_pad($cleanBase, 9, '0', STR_PAD_LEFT));
        $sum = 0;
        foreach ($weights as $i => $weight) {
            $sum += ((int) ($digits[$i] ?? 0)) * $weight;
        }
        $remainder = $sum % 11;
        if ($remainder <= 1) {
            return $remainder;
        }
        return 11 - $remainder;
    }

    /**
     * Genera un NIT comercial único e irrepetible para cada pedido.
     */
    public static function generateUniqueNit(): string
    {
        do {
            // Base numérica de 9 dígitos colombiana (rango institucional 901xxxxxx)
            $randomPart = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $base = '901' . $randomPart;
            $dv = self::calculateNitVerificationDigit($base);
            $formattedNit = substr($base, 0, 3) . '.' . substr($base, 3, 3) . '.' . substr($base, 6, 3) . '-' . $dv;
        } while (self::where('nit', $formattedNit)->orWhere('nit', $base . '-' . $dv)->exists());

        return $formattedNit;
    }

    /**
     * Busca un pedido de manera flexible por NIT formateado, NIT plano o número de orden.
     */
    public static function findByNitOrNumber(string $query): ?self
    {
        $trimmed = trim($query);
        if (empty($trimmed)) {
            return null;
        }

        // Búsqueda directa exacta
        $order = self::where('nit', $trimmed)
            ->orWhere('order_number', $trimmed)
            ->first();

        if ($order) {
            return $order;
        }

        // Búsqueda desglosada sin puntos ni guiones (usando REPLACE estándar SQL)
        $cleanNumeric = preg_replace('/\D/', '', $trimmed);
        if (!empty($cleanNumeric) && strlen($cleanNumeric) >= 5) {
            $order = self::whereRaw("REPLACE(REPLACE(nit, '.', ''), '-', '') = ?", [$cleanNumeric])
                ->first();
            if ($order) {
                return $order;
            }
        }

        return null;
    }
}
