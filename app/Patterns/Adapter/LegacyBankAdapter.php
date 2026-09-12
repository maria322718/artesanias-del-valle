<?php

declare(strict_types=1);

namespace App\Patterns\Adapter;

/**
 * Adaptador (Adapter) del patrón Adapter (GoF).
 * Implementa la interfaz esperada por el dominio (PaymentGatewayInterface)
 * y delega la ejecución a la clase incompatible (ExternalLegacyBankGateway),
 * actuando como una Capa Anticorrupción (Anti-Corruption Layer).
 */
class LegacyBankAdapter implements PaymentGatewayInterface
{
    public function __construct(
        private readonly ExternalLegacyBankGateway $adaptee
    ) {}

    public function processPayment(float $amount, string $currency, array $metadata = []): GatewayResponse
    {
        // 1. Traducción del contrato del dominio al formato arcaico del SDK bancario
        $legacyPayload = [
            'monto' => $amount,
            'divisa' => strtoupper($currency),
            'ref' => (string) ($metadata['order_reference'] ?? ('ORD-' . uniqid())),
            'fecha' => date('Y-m-d H:i:s'),
            'origen' => 'Tienda Artesanías del Valle Web',
        ];

        // 2. Invocación sobre el método arcaico incompatible
        $rawResponse = $this->adaptee->execute_transaction_v2($legacyPayload);

        // 3. Normalización hacia el objeto de valor de dominio GatewayResponse
        $isSuccess = ($rawResponse['COD_RESPUESTA'] ?? '') === 'COD_AUT_200';
        $authNumber = (string) ($rawResponse['NUM_AUTORIZACION'] ?? '');
        $bankMessage = (string) ($rawResponse['MENSAJE'] ?? 'Error no tipificado');

        $humanMessage = match ($rawResponse['COD_RESPUESTA'] ?? '') {
            'COD_AUT_200' => 'Transacción bancaria aprobada con éxito por la entidad financiera.',
            'COD_ERR_400' => 'El monto enviado al banco no es válido.',
            'COD_ERR_422' => 'La divisa seleccionada no es admitida por este banco.',
            default => "Error en pasarela bancaria heredada: {$bankMessage}",
        };

        return new GatewayResponse(
            isSuccess: $isSuccess,
            transactionId: $authNumber,
            message: $humanMessage,
            rawPayload: $rawResponse
        );
    }

    public function getGatewayName(): string
    {
        return 'Red Bancaria Tradicional Colombiana (Vía LegacyBankAdapter)';
    }

    public function getAdaptee(): ExternalLegacyBankGateway
    {
        return $this->adaptee;
    }
}
