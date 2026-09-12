<?php

declare(strict_types=1);

namespace App\Patterns\Adapter;

/**
 * Adaptee (Clase Incompatible de Terceros) del patrón Adapter (GoF).
 * 
 * Simula el SDK propietario y cerrado de una entidad bancaria tradicional de Colombia.
 * Posee métodos arcaicos en snake_case, parámetros en español sin tipar y retornos
 * con códigos de error crudos en mayúsculas (ej. COD_AUT_200, COD_ERR_504).
 * 
 * NO PUEDE SER MODIFICADO (simula un paquete cerrado o API legacy del banco).
 */
class ExternalLegacyBankGateway
{
    private string $merchantId;

    public function __construct(string $merchantId = 'BANCO-COMERCIO-77821')
    {
        $this->merchantId = $merchantId;
    }

    /**
     * Método arcaico incompatible con la interfaz esperada por el dominio moderno.
     * 
     * @param array<string, mixed> $payload Estructura esperada: ['monto', 'divisa', 'ref', 'fecha']
     * @return array<string, mixed>
     */
    public function execute_transaction_v2(array $payload): array
    {
        $monto = (float) ($payload['monto'] ?? 0.0);
        $divisa = (string) ($payload['divisa'] ?? 'COP');
        $referencia = (string) ($payload['ref'] ?? '');

        if ($monto <= 0) {
            return [
                'COD_RESPUESTA' => 'COD_ERR_400',
                'MENSAJE' => 'MONTO_INVALIDO_O_EN_CERO',
                'NUM_AUTORIZACION' => null,
                'COMERCIO' => $this->merchantId,
            ];
        }

        if ($divisa !== 'COP') {
            return [
                'COD_RESPUESTA' => 'COD_ERR_422',
                'MENSAJE' => 'DIVISA_NO_SOPORTADA_SOLO_PESOS_COLOMBIANOS',
                'NUM_AUTORIZACION' => null,
                'COMERCIO' => $this->merchantId,
            ];
        }

        // Simula autorización exitosa del banco tradicional
        $authNumber = 'AUTH-' . date('Ymd') . '-' . mt_rand(10000, 99999);

        return [
            'COD_RESPUESTA' => 'COD_AUT_200',
            'MENSAJE' => 'TRANSACCION_APROBADA_POR_EL_BANCO',
            'NUM_AUTORIZACION' => $authNumber,
            'COMERCIO' => $this->merchantId,
            'TIMESTAMP_BANCO' => time(),
            'REF_ORIGEN' => $referencia,
        ];
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }
}
